<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class SurveyAnswerUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $answerUsers = [];
        $randomDays = [150, 100, 50];

        for ($i = 1; $i <= 50; $i++) {
            $randomDay = $randomDays[array_rand($randomDays)];

            $answerUsers[] = [
                'survey_id' => rand(1, 5),
                'organization_hierarchy_id' => rand(1, 3),
                'organization_names_id' => rand(1, 5),
                'created_at' => Carbon::now()->subDays($randomDay),
                'updated_at' => Carbon::now()->subDays($randomDay),
            ];
        }

        DB::table('survey_answer_users')->insert($answerUsers);
    }
}
