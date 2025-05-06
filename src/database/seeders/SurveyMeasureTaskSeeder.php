<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class SurveyMeasureTaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tasks = [];

        for ($i = 1; $i <= 80; $i++) {
            $tasks[] = [
                'measure_id' => rand(4, 14),
                'task_text' => "タスク $i の内容",
                'status' => ['未完了', '完了'][rand(0, 1)],
                'deadline_date' => Carbon::now()->addDays(rand(1, 14)),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        }

        DB::table('survey_measure_tasks')->insert($tasks);
    }
}
