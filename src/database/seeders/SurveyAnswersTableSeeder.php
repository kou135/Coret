<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class SurveyAnswersTableSeeder extends Seeder
{
    public function run(): void
    {
        $answers = [];
        for ($i = 1; $i <= 500; $i++) {
            $randomDays = [150, 100, 50];
            $randomDay = $randomDays[array_rand($randomDays)];

            $answers[] = [
                'survey_id' => 1,
                'question_id' => rand(1, 16),
                'survey_answer_user_id' => rand(1, 30),
                'answer_content' => rand(1, 5),
                'text_answer' => null,
                'created_at' => Carbon::now()->subDays($randomDay),
                'updated_at' => Carbon::now()->subDays($randomDay),
            ];
        }

        DB::table('survey_answers')->insert($answers);
    }
}
