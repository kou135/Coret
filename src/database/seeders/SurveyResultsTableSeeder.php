<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class SurveyResultsTableSeeder extends Seeder
{
    public function run(): void
    {
        $results = [];

        for ($i = 1; $i <= 31; $i++) {
            $question_id = ($i <= 16) ? $i : ($i - 16);

            $date_offset = ($i <= 16) ? 60 : 10;

            $results[] = [
                'survey_id' => 1,
                'question_id' => $question_id,
                'company_id' => 1,
                'organization_hierarchy_id' => 2,
                'organization_names_id' => 2,
                'average_score' => round(mt_rand(200, 500) / 100, 2),
                'response_count' => rand(5, 50),
                'created_at' => Carbon::now()->subDays($date_offset),
                'updated_at' => Carbon::now()->subDays($date_offset),
            ];
        }

        DB::table('survey_results')->insert($results);
    }
}
