<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            CompaniesTableSeeder::class,
            OrganizationHierarchiesSeeder::class,
            OrganizationNamesSeeder::class,
            UsersTableSeeder::class,
            SurveysTableSeeder::class,
            SurveyQuestionsTableSeeder::class,
            SurveyAnswerUserSeeder::class,
            SurveyAnswersTableSeeder::class,
            SurveyResultsTableSeeder::class,
            IndustryCategoriesTableSeeder::class,
            SurveyMeasureSeeder::class,
            SurveyMeasureTaskSeeder::class,
            SurveyRecipientsTableSeeder::class,
            SurveyResponseStatsTableSeeder::class,
        ]);
    }
}
