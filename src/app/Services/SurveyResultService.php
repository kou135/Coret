<?php

namespace App\Services;

use App\Models\OrganizationName;
use App\Models\SurveyAnswerUser;
use App\Models\SurveyMeasure;
use App\Models\SurveyQuestion;
use App\Models\SurveyResponseStat;
use App\Models\SurveyResult;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class SurveyResultService
{
    /*
     * 月次でsurvey_resultsにスコアを集計・保存
     */
    public function aggregateMonthlyResults(): void
    {
        // 月単位で対象のsurvey_answer_usersを取得
        $users = SurveyAnswerUser::with('answers')
            ->whereYear('created_at', now()->year)
            ->whereMonth('created_at', now()->month)
            ->get();

        // 組み合わせ単位でまとめる（company_id, hierarchy_id, org_id, question_id）
        $grouped = $users->flatMap(function ($user) {
            return $user->answers->map(function ($answer) use ($user) {
                return [
                    'survey_id' => $answer->survey_id,
                    'question_id' => $answer->question_id,
                    'company_id' => $user->company_id,
                    'organization_hierarchy_id' => $user->organization_hierarchy_id,
                    'organization_names_id' => $user->organization_names_id,
                    'answer_content' => $answer->answer_content,
                ];
            });
        })->groupBy(function ($row) {
            return implode('-', [
                $row['survey_id'],
                $row['question_id'],
                $row['company_id'],
                $row['organization_hierarchy_id'],
                $row['organization_names_id'],
            ]);
        });

        // トランザクションでまとめて保存
        DB::transaction(function () use ($grouped) {
            foreach ($grouped as $key => $items) {
                $first = $items[0];
                $answers = collect($items)->pluck('answer_content')->filter();

                $average = $answers->isNotEmpty()
                    ? round($answers->average(), 2)
                    : null;

                SurveyResult::updateOrCreate(
                    [
                        'survey_id' => $first['survey_id'],
                        'question_id' => $first['question_id'],
                        'company_id' => $first['company_id'],
                        'organization_hierarchy_id' => $first['organization_hierarchy_id'],
                        'organization_names_id' => $first['organization_names_id'],
                    ],
                    [
                        'average_score' => $average,
                        'response_count' => $answers->count(),
                        'updated_at' => now(),
                    ]
                );
            }
        });
    }

    /**
     * 指定された範囲の全体スコア（合計）を取得する
     */
    public function getTotalBeforeAfterScore(
        int $companyId,
        ?int $hierarchyId,
        ?int $organizationId
    ): Collection {
        $targetOrganizationIds = $this->getAllChildOrganizationIds($organizationId);

        $results = SurveyResult::where('company_id', $companyId)
            ->whereIn('organization_names_id', $targetOrganizationIds)
            ->orderBy('created_at', 'asc')
            ->get();

        if ($results->isEmpty()) {
            return collect();
        }

        $groupedByTime = $results->groupBy(function ($item) {
            return $item->created_at->format('Y-m-d H:i:s');
        })->sortKeys();

        if ($groupedByTime->count() === 1) {
            $only = $groupedByTime->first();
            $total = $only->avg('average_score') * 20;

            return collect([[
                'before' => null,
                'after' => (int) round($total),
            ]]);
        }

        $latestTwo = $groupedByTime->take(-2)->values();

        $before = $latestTwo[0];
        $after = $latestTwo[1];

        $beforeTotal = $before->avg('average_score') * 20;
        $afterTotal = $after->avg('average_score') * 20;

        return collect([[
            'before' => (int) round($beforeTotal),
            'after' => (int) round($afterTotal),
        ]]);
    }

    public function getItemBeforeAfterScores(int $companyId, ?int $hierarchyId, ?int $organizationId): Collection
    {
        $targetOrganizationIds = $this->getAllChildOrganizationIds($organizationId);

        // created_atでグルーピングしてセット単位で処理
        $results = SurveyResult::where('company_id', $companyId)
            ->whereIn('organization_names_id', $targetOrganizationIds)
            ->orderBy('created_at', 'asc')
            ->get();

        if ($results->isEmpty()) {
            return collect();
        }

        $groupedByTime = $results->groupBy(function ($item) {
            return $item->created_at->format('Y-m-d H:i:s'); // 秒単位でまとめる
        })->sortKeys();

        if ($groupedByTime->count() === 1) {
            $only = $groupedByTime->first();

            return $only->groupBy('question_id')->map(function ($items, $questionId) {
                $question = SurveyQuestion::find($questionId);  // 質問情報を取得

                return [
                    'question_id' => $questionId,
                    'before' => null,
                    'after' => round($items->avg('average_score') * 20, 1),
                    'question_number' => $question ? $question->question_number : null,  // 質問番号
                    'question_text' => $question ? $question->question_text : null,      // 質問内容
                ];
            })->values();
        }

        // 最新2セットを比較
        $latestTwo = $groupedByTime->take(-2)->values();

        $before = $latestTwo[0]->groupBy('question_id')->map(fn ($items) => round($items->avg('average_score') * 20, 1));
        $after = $latestTwo[1]->groupBy('question_id')->map(fn ($items) => round($items->avg('average_score') * 20, 1));

        $allQuestionIds = collect($before->keys())->merge($after->keys())->unique();

        return $allQuestionIds->map(function ($questionId) use ($before, $after) {
            $question = SurveyQuestion::find($questionId);  // 質問情報を取得

            return [
                'question_id' => $questionId,
                'before' => $before->has($questionId) ? (int) round($before[$questionId]) : null,
                'after' => $after->has($questionId) ? (int) round($after[$questionId]) : null,
                'question_number' => $question ? $question->question_number : null,  // 質問番号
                'question_text' => $question ? $question->question_text : null,      // 質問内容
            ];
        });
    }

    /**
     * 再帰的に子組織のIDを取得（指定ID含む）
     */
    private function getAllChildOrganizationIds(?int $organizationId): array
    {
        if (! $organizationId) {
            return [];
        }

        $ids = [$organizationId];

        $children = OrganizationName::where('parent_id', $organizationId)->pluck('id')->toArray();

        foreach ($children as $childId) {
            $ids = array_merge($ids, $this->getAllChildOrganizationIds($childId));
        }

        return array_unique($ids);
    }

    /**
     * スコアマップ用データ取得
     * 各項目の現在スコア × スコアの変化量（上昇／下降）を返す
     */
    public function getScoreMapData(
        int $companyId,
        ?int $hierarchyId,
        ?int $organizationId
    ): Collection {
        $targetOrganizationIds = $this->getAllChildOrganizationIds($organizationId);

        // created_at でグルーピングし、最新2セット取得
        $results = SurveyResult::where('company_id', $companyId)
            ->whereIn('organization_names_id', $targetOrganizationIds)
            ->orderBy('created_at', 'asc')
            ->get();

        if ($results->isEmpty()) {
            return collect();
        }

        $groupedByTime = $results->groupBy(function ($item) {
            return $item->created_at->format('Y-m-d H:i:s');
        })->sortKeys();

        if ($groupedByTime->count() < 2) {
            return collect(); // 比較対象がない
        }

        // 最新2つの回答セットを取得
        $latestTwo = $groupedByTime->take(-2)->values();

        $before = $latestTwo[0]->groupBy('question_id')->map(
            fn ($items) => round($items->avg('average_score') * 20, 1)
        );

        $after = $latestTwo[1]->groupBy('question_id')->map(
            fn ($items) => round($items->avg('average_score') * 20, 1)
        );

        // 各質問項目ごとに score（今のスコア）と change（変化量）を算出
        return $after->map(function ($score, $questionId) use ($before) {
            $questionText = SurveyQuestion::where('id', $questionId)->first()->question_text ?? 'No text available';

            return [
                'question_id' => $questionId,
                'score' => $score,
                'change' => $before->has($questionId)
                    ? round($score - $before[$questionId], 1)
                    : null,
                'questionText' => $questionText,
            ];
        })->values();
    }

    public function getScoreBeforeAfter(int $companyId, int $organizationId, int $questionId): array
    {
        $targetOrganizationIds = $this->getAllChildOrganizationIds($organizationId);

        $results = SurveyResult::where('company_id', $companyId)
            ->whereIn('organization_names_id', $targetOrganizationIds)
            ->where('question_id', $questionId)
            ->orderBy('created_at', 'asc')
            ->get();

        if ($results->isEmpty()) {
            return ['before' => null, 'after' => null, 'score_diff_text' => 'データなし'];
        }

        $latestTwo = $results->take(-2);

        // ここでのスコアはすでに100点満点換算されているため、再度20倍しない
        $before = $latestTwo->count() >= 2 ? round($latestTwo[0]->average_score) : null;
        $after = round($latestTwo->last()->average_score);

        $scoreDiffText = null;
        if (! is_null($before) && ! is_null($after)) {
            $diff = $after - $before;
            $scoreDiffText = $diff !== 0 ? abs($diff) : 'スコアに変化なし';
        }

        return [
            'before' => $before,
            'after' => $after,
            'score_diff_text' => $scoreDiffText,
        ];
    }

    public function getrate($companyId)
    {
        $response = SurveyResponseStat::where('company_id', $companyId)
            ->get(); // データを取得

        // レスポンスが空でない場合、'response_rate'フィールドを取得
        $rates = $response->pluck('response_rate')->toArray();

        // 結果を返す
        return [
            'responserate' => $rates, // 取得した回答率を返す
        ];
    }

    /**
     * 前回施策情報を取得
     */
    public function getLastMeasure(int $companyId, int $hierarchyId, int $organizationId, int $questionId): ?SurveyMeasure
    {
        return SurveyMeasure::where('survey_id', 1) // $surveyId
            ->where('company_id', $companyId)
            ->where('organization_hierarchy_id', $hierarchyId)
            ->where('organization_names_id', $organizationId)
            ->where('question_id', $questionId)
            ->orderBy('created_at', 'desc')
            ->first();
    }

    public function getQuestionData(int $questionId)
    {
        return SurveyQuestion::where('id', $questionId)
            ->select('question_number', 'question_text', 'question_document')
            ->first();
    }
}
