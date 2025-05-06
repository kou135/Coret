<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class OrganizationHierarchiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('organization_hierarchies')->insert([
            [
                'company_id' => 1,
                'name' => '部署',
                'parent_id' => null,
                'created_at' => Carbon::now()->subDays(300),
                'updated_at' => Carbon::now()->subDays(290),
            ],
            [
                'company_id' => 1,
                'name' => '課',
                'parent_id' => 1,
                'created_at' => Carbon::now()->subDays(300),
                'updated_at' => Carbon::now()->subDays(300),
            ],
            [
                'company_id' => 1,
                'name' => '係',
                'parent_id' => 2,
                'created_at' => Carbon::now()->subDays(300),
                'updated_at' => Carbon::now()->subDays(300),
            ],
            [
                'company_id' => 2,
                'name' => '部署',
                'parent_id' => null,
                'created_at' => Carbon::now()->subDays(300),
                'updated_at' => Carbon::now()->subDays(300),
            ],
            [
                'company_id' => 3,
                'name' => '社長',
                'parent_id' => null,
                'created_at' => Carbon::now()->subDays(300),
                'updated_at' => Carbon::now()->subDays(300),
            ],
        ]);
    }
}
