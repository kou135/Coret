<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'first_name' => '山田',
                'last_name' => '太郎',
                'company_id' => 1,
                'organization_hierarchy_id' => 2,
                'organization_names_id' => 2,
                'position' => '現場管理職',
                'email' => 'test@example.com',
                'password' => bcrypt('password'),
                'created_at' => Carbon::now()->subDays(300),
                'updated_at' => Carbon::now()->subDays(300),
            ],
            [
                'first_name' => '花子',
                'last_name' => '佐藤',
                'company_id' => 2,
                'organization_hierarchy_id' => 4,
                'organization_names_id' => 5,
                'position' => '社員',
                'email' => 'hanako.sato@example.com',
                'password' => bcrypt('password'),
                'created_at' => Carbon::now()->subDays(300),
                'updated_at' => Carbon::now()->subDays(300),
            ],
            [
                'first_name' => '次郎',
                'last_name' => '鈴木',
                'company_id' => 1,
                'organization_hierarchy_id' => 1,
                'organization_names_id' => 1,
                'position' => 'マネージャー',
                'email' => 'jiro.suzuki@example.com',
                'password' => bcrypt('password'),
                'created_at' => Carbon::now()->subDays(300),
                'updated_at' => Carbon::now()->subDays(300),
            ],
        ]);
    }
}
