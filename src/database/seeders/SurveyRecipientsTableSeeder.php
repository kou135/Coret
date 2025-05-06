<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class SurveyRecipientsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('survey_response_stats')->insert([
            [
                'survey_id' => 1,
                'company_id' => 1,
                'organization_hierarchy_id' => 2,
                'organization_names_id' => 2,
                'sent_count' => 200,
                'answered_count' => 150,
                'response_rate' => 70.00,
                'collected_at' => Carbon::now()->subDays(50),
                'created_at' => Carbon::now()->subDays(50),
                'updated_at' => Carbon::now()->subDays(50),
            ],
            [
                'survey_id' => 1,
                'company_id' => 1,
                'organization_hierarchy_id' => 2,
                'organization_names_id' => 2,
                'sent_count' => 200,
                'answered_count' => 170,
                'response_rate' => 85.00,
                'collected_at' => Carbon::now()->subDays(20),
                'created_at' => Carbon::now()->subDays(20),
                'updated_at' => Carbon::now()->subDays(20),
            ],
            [
                'survey_id' => 2,
                'company_id' => 2,
                'organization_hierarchy_id' => 4,
                'organization_names_id' => 5,
                'sent_count' => 120,
                'answered_count' => 100,
                'response_rate' => 83.33,
                'collected_at' => Carbon::now()->subDays(100),
                'created_at' => Carbon::now()->subDays(100),
                'updated_at' => Carbon::now()->subDays(100),
            ],
        ]);
    }
}
