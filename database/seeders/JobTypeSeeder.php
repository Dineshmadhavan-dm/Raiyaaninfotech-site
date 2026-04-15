<?php

namespace Database\Seeders;

use App\Models\Jobtype;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class JobTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            'Full-time employment',
            'Part-time employment',
            'Contract employment',
            'Contingent employment',
            'Seasonal employment',
            'Probation',
            'Traineeship',
            'Internship',
            'Apprenticeship',
        ];

        foreach ($types as $type) {
            Jobtype::create(['jobtype_name' => trim($type)]);
        }
    }
}
