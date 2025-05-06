<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class OrganizationNamesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('organization_names')->insert([
            [
                'company_id' => 1,
                'organization_hierarchy_id' => 1,
                'parent_id' => null,
                'name' => '開発部',
                'organization_size' => '500人',
                'remote_work_status' => 'オフィス勤務のみ可',
                'flex_time_status' => 'コアタイムあり',
                'one_on_one_frequency' => '半年に一回',
                'age_distribution' => '40代以上が中心',
                'average_overtime_hours' => '月10時間程度',
                'created_at' => Carbon::now()->subDays(300),
                'updated_at' => Carbon::now()->subDays(300),
            ],
            [
                'company_id' => 1,
                'organization_hierarchy_id' => 2,
                'parent_id' => 1,
                'name' => '事業開発化',
                'organization_size' => '100人',
                'remote_work_status' => 'フルリモート化',
                'flex_time_status' => 'コアタイムあり',
                'one_on_one_frequency' => '半年に一回',
                'age_distribution' => '50代が中心',
                'average_overtime_hours' => '月40時間以上',
                'created_at' => Carbon::now()->subDays(300),
                'updated_at' => Carbon::now()->subDays(300),
            ],
            [
                'company_id' => 1,
                'organization_hierarchy_id' => 3,
                'parent_id' => 2,
                'name' => '事業推進係',
                'organization_size' => '50〜100人',
                'remote_work_status' => '可',
                'flex_time_status' => 'あり',
                'one_on_one_frequency' => '月1回',
                'age_distribution' => '20〜30代',
                'average_overtime_hours' => '30時間',
                'created_at' => Carbon::now()->subDays(300),
                'updated_at' => Carbon::now()->subDays(300),
            ],
            [
                'company_id' => 1,
                'organization_hierarchy_id' => 3,
                'parent_id' => 2,
                'name' => 'メンテナンス係',
                'organization_size' => '10〜20人',
                'remote_work_status' => '可',
                'flex_time_status' => 'あり',
                'one_on_one_frequency' => '月1回',
                'age_distribution' => '20代が中心',
                'average_overtime_hours' => '10時間',
                'created_at' => Carbon::now()->subDays(300),
                'updated_at' => Carbon::now()->subDays(300),
            ],
            [
                'company_id' => 2,
                'organization_hierarchy_id' => 4,
                'parent_id' => null,
                'name' => 'マーケティング部',
                'organization_size' => '15〜30人',
                'remote_work_status' => '不可',
                'flex_time_status' => 'なし',
                'one_on_one_frequency' => '月1回',
                'age_distribution' => '30〜40代',
                'average_overtime_hours' => '20時間',
                'created_at' => Carbon::now()->subDays(300),
                'updated_at' => Carbon::now()->subDays(300),
            ],
            [
                'company_id' => 3,
                'organization_hierarchy_id' => 5,
                'parent_id' => null,
                'name' => '社長',
                'organization_size' => '30人',
                'remote_work_status' => '可',
                'flex_time_status' => 'あり',
                'one_on_one_frequency' => '月2回',
                'age_distribution' => '20〜30代',
                'average_overtime_hours' => '50時間',
                'created_at' => Carbon::now()->subDays(300),
                'updated_at' => Carbon::now()->subDays(300),
            ],
        ]);
    }
}
