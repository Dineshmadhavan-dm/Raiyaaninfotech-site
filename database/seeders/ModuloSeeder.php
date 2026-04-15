<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Modulo;
use Carbon\Carbon;

class ModuloSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $modulos = [
            // Project 1: E-Commerce Platform Development (members: [3, 6, 7, 8, 9, 10, 11])
            [
                'mod_name' => 'User Authentication Module',
                'mod_desc' => 'Development of user registration, login, and authentication system with role-based access control.',
                'mod_deadline' => Carbon::now()->addMonths(1),
                'mod_project' => 1,
                'mod_member' => json_encode(["3", "6", "7"]),
                'mod_accessmod' => 0,
                'delete_status' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'mod_name' => 'Payment Gateway Integration',
                'mod_desc' => 'Integration of multiple payment gateways including Stripe, PayPal, and bank transfer options.',
                'mod_deadline' => Carbon::now()->addMonths(2),
                'mod_project' => 1,
                'mod_member' => json_encode(["8", "9", "10"]),
                'mod_accessmod' => 1,
                'delete_status' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'mod_name' => 'Product Catalog Management',
                'mod_desc' => 'Development of product listing, categorization, search, and filtering functionalities.',
                'mod_deadline' => Carbon::now()->addMonths(1),
                'mod_project' => 1,
                'mod_member' => json_encode(["7", "10", "11"]),
                'mod_accessmod' => 0,
                'delete_status' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],

            // Project 2: Mobile Banking App (members: [5, 12, 13, 14, 15, 16])
            [
                'mod_name' => 'Fund Transfer Module',
                'mod_desc' => 'Development of secure fund transfer between accounts with transaction history and limits.',
                'mod_deadline' => Carbon::now()->addMonths(3),
                'mod_project' => 2,
                'mod_member' => json_encode(["5", "12", "13"]),
                'mod_accessmod' => 1,
                'delete_status' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'mod_name' => 'Bill Payment System',
                'mod_desc' => 'Implementation of bill payment features for utilities, credit cards, and other services.',
                'mod_deadline' => Carbon::now()->addMonths(4),
                'mod_project' => 2,
                'mod_member' => json_encode(["14", "15", "16"]),
                'mod_accessmod' => 1,
                'delete_status' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'mod_name' => 'Security & Authentication',
                'mod_desc' => 'Development of biometric authentication, PIN security, and fraud detection systems.',
                'mod_deadline' => Carbon::now()->addMonths(2),
                'mod_project' => 2,
                'mod_member' => json_encode(["5", "14", "16"]),
                'mod_accessmod' => 1,
                'delete_status' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],

            // Project 3: Healthcare Management System (members: [7, 8, 9, 17, 18, 19, 20])
            [
                'mod_name' => 'Patient Records Management',
                'mod_desc' => 'Development of electronic health records system with patient history and medical data.',
                'mod_deadline' => Carbon::now()->addMonths(4),
                'mod_project' => 3,
                'mod_member' => json_encode(["7", "8", "17"]),
                'mod_accessmod' => 1,
                'delete_status' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'mod_name' => 'Appointment Scheduling',
                'mod_desc' => 'Implementation of doctor appointment booking system with calendar integration.',
                'mod_deadline' => Carbon::now()->addMonths(3),
                'mod_project' => 3,
                'mod_member' => json_encode(["9", "18", "19"]),
                'mod_accessmod' => 0,
                'delete_status' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'mod_name' => 'Billing & Insurance',
                'mod_desc' => 'Development of billing system with insurance claim processing and payment tracking.',
                'mod_deadline' => Carbon::now()->addMonths(5),
                'mod_project' => 3,
                'mod_member' => json_encode(["17", "19", "20"]),
                'mod_accessmod' => 1,
                'delete_status' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],

            // Project 4: School Management Portal (members: [9, 10, 11, 12, 13])
            [
                'mod_name' => 'Student Enrollment System',
                'mod_desc' => 'Development of online student registration and enrollment process with document upload.',
                'mod_deadline' => Carbon::now()->addMonths(1),
                'mod_project' => 4,
                'mod_member' => json_encode(["9", "10", "11"]),
                'mod_accessmod' => 0,
                'delete_status' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'mod_name' => 'Grade Management',
                'mod_desc' => 'Implementation of grade book system for teachers to input and manage student grades.',
                'mod_deadline' => Carbon::now()->addMonths(1),
                'mod_project' => 4,
                'mod_member' => json_encode(["11", "12", "13"]),
                'mod_accessmod' => 1,
                'delete_status' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'mod_name' => 'Attendance Tracking',
                'mod_desc' => 'Development of daily attendance tracking system with reporting and notifications.',
                'mod_deadline' => Carbon::now()->addMonths(2),
                'mod_project' => 4,
                'mod_member' => json_encode(["9", "12", "13"]),
                'mod_accessmod' => 0,
                'delete_status' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],

            // Project 5: Inventory Tracking System (members: [11, 14, 15, 16, 17, 18])
            [
                'mod_name' => 'Barcode Scanning System',
                'mod_desc' => 'Development of barcode generation and scanning functionality for inventory items.',
                'mod_deadline' => Carbon::now()->addMonths(2),
                'mod_project' => 5,
                'mod_member' => json_encode(["11", "14", "15"]),
                'mod_accessmod' => 0,
                'delete_status' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'mod_name' => 'Stock Alert System',
                'mod_desc' => 'Implementation of low stock alerts and automatic reordering notifications.',
                'mod_deadline' => Carbon::now()->addMonths(3),
                'mod_project' => 5,
                'mod_member' => json_encode(["15", "16", "17"]),
                'mod_accessmod' => 0,
                'delete_status' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'mod_name' => 'Reporting Dashboard',
                'mod_desc' => 'Development of analytics and reporting dashboard for inventory performance.',
                'mod_deadline' => Carbon::now()->addMonths(4),
                'mod_project' => 5,
                'mod_member' => json_encode(["16", "17", "18"]),
                'mod_accessmod' => 1,
                'delete_status' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];

        foreach ($modulos as $modulo) {
            Modulo::create($modulo);
        }
    }
}
