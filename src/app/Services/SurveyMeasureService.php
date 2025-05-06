<?php

namespace App\Services;

use App\Models\SurveyMeasure;
use App\Models\SurveyMeasureTask;
use App\Models\SurveyQuestion;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator as LaravelPaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class SurveyMeasureService
{
    public function getQuestionText(?int $questionId): string
    {
        return SurveyQuestion::find($questionId)?->question_text ?? '質問内容が見つかりません';
    }

    public function createWithTasks(array $validated): SurveyMeasure
    {
        return DB::transaction(function () use ($validated) {
            $measure = SurveyMeasure::create([
                'survey_id' => $validated['survey_id'],
                'company_id' => $validated['company_id'],
                'organization_hierarchy_id' => $validated['organization_hierarchy_id'],
                'organization_names_id' => $validated['organization_names_id'],
                'question_id' => $validated['question_id'] ?? null,
                'measure_title' => $validated['measure_title'],
                'measure_description' => $validated['measure_description'],
                'target_scope' => $validated['target_scope'] ?? null,
                'user_id' => $validated['user_id'],
                'status' => '実施中', // デフォルトで下書き状態
                'start_date' => $validated['start_date'] ?? null,
                'end_date' => $validated['end_date'] ?? null,
            ]);

            foreach ($validated['tasks'] as $task) {
                if (! empty($task['task_text'])) {
                    $measure->tasks()->create([
                        'task_text' => $task['task_text'],
                        'deadline_date' => $task['deadline_date'] ?? null,
                        'status' => '未完了',
                    ]);
                }
            }

            return $measure;
        });
    }

    public function getRawMeasuresFromSession(): LaravelPaginator
    {
        // $admin_affiliation = Session::get('admin_affiliation');
        // if (! $admin_affiliation) {
        //   throw new \Exception('セッション情報が不足しています');
        // }

        $affiliation = session('admin_affiliation');

        $companyId = $affiliation['company_id'];
        $hierarchyId = $affiliation['organization_hierarchy_id'];
        $organizationId = $affiliation['organization_names_id'];

        return SurveyMeasure::with('tasks', 'user') // `question` を省略
            ->where('company_id', $companyId)// admin_affiliation['company_id'])
            ->where('organization_hierarchy_id', $hierarchyId)// $admin_affiliation['organization_hierarchy_id'])
            ->where('organization_names_id', $organizationId)// $admin_affiliation['organization_names_id'])
            ->orderByRaw("FIELD(status, '実施中') DESC") // 実施中を上に表示
            ->latest()
            ->paginate(13);
    }

    public function getFormattedMeasuresForList(): LaravelPaginator
    {
        $paginator = $this->getRawMeasuresFromSession();

        $collection = $paginator->getCollection()->transform(function ($measure) {
            // タスク数と完了数を取得
            $total = $measure->tasks->count();
            $completed = $measure->tasks->where('status', '完了')->count();

            // 「完了タスク数/全タスク数」の形式で表示
            $progressRate = ($total > 0) ? "$completed/$total" : "0/$total";

            return [
                'id' => $measure->id,
                'measure_title' => $measure->measure_title,
                'progress_rate' => $progressRate,  // 完了タスク数/全タスク数
                'start_date' => $measure->start_date,
                'end_date' => $measure->end_date,
                'status' => $measure->status,
                'date_range' => $this->formatDateRange($measure->start_date, $measure->end_date),
                'user_name' => optional($measure->user)->last_name.optional($measure->user)->first_name, // 登録者情報
            ];
        });

        // コレクションを詰め直す
        $paginator->setCollection($collection);

        return $paginator;
    }

    private function formatDateRange($start, $end)
    {
        if (! $start || ! $end) {
            return '';
        }

        $startDate = Carbon::parse($start)->format('Y/n/j');
        $endDate = Carbon::parse($end)->format('Y/n/j');

        $startMonth = Carbon::parse($start)->format('Y/n');
        $endMonth = Carbon::parse($end)->format('Y/n');

        return ($startMonth == $endMonth)
            ? $startMonth
            : ($startDate.'-'.$endDate);
    }

    public function getDetailById(int $id): SurveyMeasure
    {
        $measure = SurveyMeasure::with(['question.survey', 'tasks', 'user'])->findOrFail($id);

        // start_date と end_date を Y-m-d にフォーマット
        if ($measure->start_date) {
            $measure->start_date = \Carbon\Carbon::parse($measure->start_date)->format('Y-m-d');
        }

        if ($measure->end_date) {
            $measure->end_date = \Carbon\Carbon::parse($measure->end_date)->format('Y-m-d');
        }

        return $measure;
    }

    public function calculateProgress(SurveyMeasure $measure)
    {
        // すべてのタスクを取得
        $allTasks = $measure->tasks;

        // 完了したタスクを取得
        $completedTasks = $allTasks->where('status', '完了');

        // 総タスク数と完了タスク数を計算
        $totalTasks = $allTasks->count();
        $completedTasksCount = $completedTasks->count();

        // 残りタスク数を計算
        $remainingTasks = $totalTasks - $completedTasksCount;

        // 進捗を返す
        return [
            'totalTasks' => $totalTasks,
            'completedTasksCount' => $completedTasksCount,
            'remainingTasks' => $remainingTasks,
        ];
    }

    public function updateTaskStatus($taskId)
    {
        $task = SurveyMeasureTask::findOrFail($taskId);
        $task->status = '完了';
        $task->save();

        // 全てのタスクが完了した場合、施策のステータスを「実施済み」に更新
        $measure = $task->measure;
        $allTasks = $measure->tasks;
        $completedTasks = $allTasks->where('status', '完了');

        if ($allTasks->count() === $completedTasks->count()) {
            $measure->status = '実施済み';
            $measure->save();
        }
    }
}
