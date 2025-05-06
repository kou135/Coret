<?php

namespace App\Console\Commands;

use App\Models\Survey;
use App\Models\SurveyAnswerUser;
use App\Models\SurveyRecipient;
use App\Models\SurveyResponseStat;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CollectSurveyResponseStats extends Command
{
    protected $signature = 'stats:collect-response';

    protected $description = '3ヶ月以内のアンケート回答率を新規記録';

    public function handle()
    {
        $threeMonthsAgo = Carbon::now()->subMonths(3);
        $surveys = Survey::where('created_at', '>=', $threeMonthsAgo)->get();

        foreach ($surveys as $survey) {
            $recipients = SurveyRecipient::where('company_id', $survey->company_id)->get();

            $grouped = $recipients->groupBy(function ($item) {
                return $item->organization_names_id ?? 'no_org';
            });

            foreach ($grouped as $orgNameId => $group) {
                $sentCount = $group->count();

                $answeredCount = SurveyAnswerUser::where('survey_id', $survey->id)
                    ->when($orgNameId !== 'no_org', fn ($q) => $q->where('organization_names_id', $orgNameId))
                    ->count();

                $responseRate = $sentCount > 0 ? round($answeredCount / $sentCount * 100, 2) : 0;

                SurveyResponseStat::create([
                    'survey_id' => $survey->id,
                    'company_id' => $survey->company_id,
                    'organization_names_id' => $orgNameId !== 'no_org' ? $orgNameId : null,
                    'organization_hierarchy_id' => $group->first()->organization_hierarchy_id ?? null,
                    'sent_count' => $sentCount,
                    'answered_count' => $answeredCount,
                    'response_rate' => $responseRate,
                    'collected_at' => Carbon::today(),
                ]);
            }
        }

        $this->info('回答率の集計が完了しました');
    }
}
