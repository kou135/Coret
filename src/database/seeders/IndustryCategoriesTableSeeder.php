<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class IndustryCategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $industries = [
            ['organization_name_id' => 2, 'name' => 'IT'],
            ['organization_name_id' => 2, 'name' => '製造'],
            ['organization_name_id' => 1, 'name' => '小売'],
            ['organization_name_id' => 1, 'name' => '金融'],
            ['organization_name_id' => 1, 'name' => '教育'],
            ['organization_name_id' => 3, 'name' => '医療'],
            ['organization_name_id' => 3, 'name' => 'エンタメ'],
            ['organization_name_id' => 2, 'name' => '不動産'],
            ['organization_name_id' => 4, 'name' => '食品'],
            ['organization_name_id' => 4, 'name' => '自動車'],
        ];

        foreach ($industries as &$industry) {
            $industry['created_at'] = Carbon::now()->subDays(300);
            $industry['updated_at'] = Carbon::now()->subDays(300);
        }

        DB::table('industry_categories')->insert($industries);
    }
}
