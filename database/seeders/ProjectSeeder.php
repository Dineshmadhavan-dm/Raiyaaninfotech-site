<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Project;
use Carbon\Carbon;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $projects = [
            [
                'pro_name' => 'E-Commerce Platform Development',
                'pro_desc' => 'Development of a comprehensive e-commerce platform with payment gateway integration, inventory management, and customer relationship management features.',
                'pro_deadline' => Carbon::now()->addMonths(3),
                'pro_client' => '1',
                'pro_head' => '2',
                'pro_lead' => '3',
                'pro_member' => json_encode(["3", "6", "7", "8", "9", "10", "11"]),
                'pro_accessmod' => 0,
                'delete_status' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'pro_name' => 'Mobile Banking App',
                'pro_desc' => 'Building a secure mobile banking application with features like fund transfer, bill payments, and investment tracking for retail banking customers.',
                'pro_deadline' => Carbon::now()->addMonths(5),
                'pro_client' => '2',
                'pro_head' => '4',
                'pro_lead' => '5',
                'pro_member' => json_encode(["5", "12", "13", "14", "15", "16"]),
                'pro_accessmod' => 1,
                'delete_status' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'pro_name' => 'Healthcare Management System',
                'pro_desc' => 'Developing a complete healthcare management system for hospitals including patient records, appointment scheduling, and billing modules.',
                'pro_deadline' => Carbon::now()->addMonths(6),
                'pro_client' => '3',
                'pro_head' => '6',
                'pro_lead' => '7',
                'pro_member' => json_encode(["7", "8", "9", "17", "18", "19", "20"]),
                'pro_accessmod' => 1,
                'delete_status' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'pro_name' => 'School Management Portal',
                'pro_desc' => 'Creating a web-based school management portal for student enrollment, grade management, attendance tracking, and parent-teacher communication.',
                'pro_deadline' => Carbon::now()->addMonths(2),
                'pro_client' => '4',
                'pro_head' => '8',
                'pro_lead' => '9',
                'pro_member' => json_encode(["9", "10", "11", "12", "13"]),
                'pro_accessmod' => 0,
                'delete_status' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'pro_name' => 'Inventory Tracking System',
                'pro_desc' => 'Implementing an inventory tracking system with barcode scanning, stock alerts, and reporting features for warehouse management.',
                'pro_deadline' => Carbon::now()->addMonths(4),
                'pro_client' => '5',
                'pro_head' => '10',
                'pro_lead' => '11',
                'pro_member' => json_encode(["11", "14", "15", "16", "17", "18"]),
                'pro_accessmod' => 0,
                'delete_status' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        ];

        foreach ($projects as $project) {
            Project::create($project);
        }
    }
}
