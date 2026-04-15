<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BranchesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $branches = [
            'Bengaluru',
            'Mumbai',
            'Hyderabad',
            'Delhi',
            'Pune',
            'Chennai',
            'Gurugram',
            'Noida',
            'Kolkata',
            'Ahmedabad',
            'Jaipur',
            'Coimbatore',
            'Chandigarh',
            'Indore',
            'Nagpur',
            'Bhubaneswar',
            'Lucknow',
            'Surat',
            'Bhopal',
            'Trivandrum',
        ];

        foreach ($branches as $branch) {
            DB::table('branches')->insert([
                'branch_name' => $branch,
                'delete_status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
