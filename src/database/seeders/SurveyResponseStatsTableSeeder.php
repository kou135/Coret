<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class SurveyResponseStatsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $randomSentCounts = [200, 150, 100];

        for ($i = 1; $i <= 30; $i++) {
            $sent = $randomSentCounts[array_rand($randomSentCounts)];
            $answered = rand(5, $sent);
            $rate = round(($answered / $sent) * 100, 2);

            DB::table('survey_response_stats')->insert([
                'survey_id' => rand(1, 5),
                'company_id' => rand(1, 5),
                'organization_hierarchy_id' => rand(1, 5),
                'organization_names_id' => rand(1, 5),
                'sent_count' => $sent,
                'answered_count' => $answered,
                'response_rate' => $rate,
                'collected_at' => Carbon::now()->subMonths(rand(0, 3))->startOfMonth(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
