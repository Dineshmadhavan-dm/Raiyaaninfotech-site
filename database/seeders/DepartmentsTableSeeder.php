<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DepartmentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departments = [
            'Software Development',
            'UI/UX',
            'DevOps',
            'Data & AI',
            'Cybersecurity',
'IT',

        ];

        // Remove duplicates
        $departments = array_unique($departments);

        foreach ($departments as $department) {
            DB::table('departments')->insert([
                'dep_name' => $department,
                'delete_status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
