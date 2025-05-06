<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class CompaniesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('companies')->insert([
            [
                'company_code' => '0001',
                'name' => '株式会社鈴木商事',
                'company_size' => '1万人',
                'business_years' => '4~10年',
                'evaluation_frequency' => '半年ごと',
                'salary_transparency' => '評価基準公開',
                'evaluation_type' => '上司による一方的な評価',
                'created_at' => Carbon::now()->subDays(300),
                'updated_at' => Carbon::now()->subDays(300),
            ],
            [
                'company_code' => '0002',
                'name' => 'Company 2',
                'company_size' => '中規模',
                'business_years' => '15年',
                'evaluation_frequency' => '年2回',
                'salary_transparency' => '不明示的',
                'evaluation_type' => '年功序列',
                'created_at' => Carbon::now()->subDays(10),
                'updated_at' => Carbon::now()->subDays(7),
            ],
            [
                'company_code' => '0003',
                'name' => 'Company 3',
                'company_size' => '小規模',
                'business_years' => '5年',
                'evaluation_frequency' => '年1回',
                'salary_transparency' => '明示的',
                'evaluation_type' => '能力主義',
                'created_at' => Carbon::now()->subDays(30),
                'updated_at' => Carbon::now()->subDays(25),
            ],
            [
                'company_code' => '0004',
                'name' => 'Company 4',
                'company_size' => '中規模',
                'business_years' => '20年',
                'evaluation_frequency' => '年2回',
                'salary_transparency' => '明示的',
                'evaluation_type' => '成果主義',
                'created_at' => Carbon::now()->subDays(15),
                'updated_at' => Carbon::now()->subDays(12),
            ],
            [
                'company_code' => '0005',
                'name' => 'Company 5',
                'company_size' => '大規模',
                'business_years' => '40年',
                'evaluation_frequency' => '半年1回',
                'salary_transparency' => '明示的',
                'evaluation_type' => '年功序列',
                'created_at' => Carbon::now()->subDays(7),
                'updated_at' => Carbon::now()->subDays(2),
            ],
        ]);
    }
}
