<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DesignationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $designations = [
            'Software Architect',
            'UX Researcher',
            'CI/CD Pipeline Specialist',
            'AI Prompt Engineer',
            'Cybersecurity Analyst',
            'Full Stack Developer',
            'Product Designer',
            'DevOps Engineer',
            'Data Engineer',
            'Cloud Security Engineer',
            'Software Engineer'

        ];

        // Optional: Remove duplicates
        $designations = array_unique($designations);

        foreach ($designations as $designation) {
            DB::table('designations')->insert([
                'des_name' => $designation,
                'delete_status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
