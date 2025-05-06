<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class SurveysTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('surveys')->insert([
            [
                'company_id' => 1,
                'name' => '鈴木商事サーベイ',
                'created_at' => Carbon::now()->subDays(300),
                'updated_at' => Carbon::now()->subDays(300),
            ],
            [
                'company_id' => 2,
                'name' => 'サーベイ 2',
                'created_at' => Carbon::now()->subDays(300),
                'updated_at' => Carbon::now()->subDays(300),
            ],
            [
                'company_id' => 3,
                'name' => 'サーベイ 3',
                'created_at' => Carbon::now()->subDays(300),
                'updated_at' => Carbon::now()->subDays(300),
            ],
            [
                'company_id' => 4,
                'name' => 'サーベイ 4',
                'created_at' => Carbon::now()->subDays(300),
                'updated_at' => Carbon::now()->subDays(300),
            ],
            [
                'company_id' => 5,
                'name' => 'サーベイ 5',
                'created_at' => Carbon::now()->subDays(300),
                'updated_at' => Carbon::now()->subDays(300),
            ],
        ]);
    }
}
