<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Subtask;
use App\Models\Task;
use App\Models\Modulo;
use Carbon\Carbon;

class SubtaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all tasks to create subtasks for
        $tasks = Task::all();

        $subtasks = [
            // Subtasks for Task 1 (Design User Registration UI)
            [
                'stask_name' => 'Create Wireframes',
                'stask_desc' => 'Design low-fidelity wireframes for user registration flow.',
                'stask_attachment' => null,
                'stask_deadline' => Carbon::now()->addDays(3),
                'stask_task' => 1,
                'stask_assignedto' => 6, // Different from task_assignedto (3)
                'stask_priority' => 2,
                'stask_accessmod' => 0,
                'delete_status' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'stask_name' => 'Design Mockups',
                'stask_desc' => 'Create high-fidelity mockups with proper styling and branding.',
                'stask_attachment' => null,
                'stask_deadline' => Carbon::now()->addDays(5),
                'stask_task' => 1,
                'stask_assignedto' => 7, // Different from task_assignedto (3)
                'stask_priority' => 2,
                'stask_accessmod' => 0,
                'delete_status' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],

            // Subtasks for Task 2 (Integrate Stripe API)
            [
                'stask_name' => 'Setup Stripe Account',
                'stask_desc' => 'Configure Stripe developer account and obtain API keys.',
                'stask_attachment' => null,
                'stask_deadline' => Carbon::now()->addDays(2),
                'stask_task' => 2,
                'stask_assignedto' => 9, // Different from task_assignedto (8)
                'stask_priority' => 1,
                'stask_accessmod' => 1,
                'delete_status' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'stask_name' => 'Implement Payment Processing',
                'stask_desc' => 'Create payment processing logic with error handling.',
                'stask_attachment' => null,
                'stask_deadline' => Carbon::now()->addDays(10),
                'stask_task' => 2,
                'stask_assignedto' => 10, // Different from task_assignedto (8)
                'stask_priority' => 3,
                'stask_accessmod' => 1,
                'delete_status' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],

            // Subtasks for Task 3 (Develop Product Search)
            [
                'stask_name' => 'Build Search Algorithm',
                'stask_desc' => 'Implement core search functionality with keyword matching.',
                'stask_attachment' => null,
                'stask_deadline' => Carbon::now()->addDays(5),
                'stask_task' => 3,
                'stask_assignedto' => 10, // Different from task_assignedto (7)
                'stask_priority' => 2,
                'stask_accessmod' => 0,
                'delete_status' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'stask_name' => 'Create Filter System',
                'stask_desc' => 'Develop category, price, and rating filters for search results.',
                'stask_attachment' => null,
                'stask_deadline' => Carbon::now()->addDays(8),
                'stask_task' => 3,
                'stask_assignedto' => 11, // Different from task_assignedto (7)
                'stask_priority' => 2,
                'stask_accessmod' => 0,
                'delete_status' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],

            // Subtasks for Task 4 (Implement Transfer Validation)
            [
                'stask_name' => 'Daily Limit Validation',
                'stask_desc' => 'Implement daily transfer limit checks and validations.',
                'stask_attachment' => null,
                'stask_deadline' => Carbon::now()->addDays(7),
                'stask_task' => 4,
                'stask_assignedto' => 12, // Different from task_assignedto (5)
                'stask_priority' => 3,
                'stask_accessmod' => 1,
                'delete_status' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'stask_name' => 'Balance Check System',
                'stask_desc' => 'Create real-time balance verification before transfers.',
                'stask_attachment' => null,
                'stask_deadline' => Carbon::now()->addDays(14),
                'stask_task' => 4,
                'stask_assignedto' => 13, // Different from task_assignedto (5)
                'stask_priority' => 3,
                'stask_accessmod' => 1,
                'delete_status' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],

            // Subtasks for Task 5 (Add Utility Bill Providers)
            [
                'stask_name' => 'Electricity Provider Integration',
                'stask_desc' => 'Integrate with major electricity providers API.',
                'stask_attachment' => null,
                'stask_deadline' => Carbon::now()->addDays(10),
                'stask_task' => 5,
                'stask_assignedto' => 15, // Different from task_assignedto (14)
                'stask_priority' => 2,
                'stask_accessmod' => 1,
                'delete_status' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'stask_name' => 'Water & Gas Provider Setup',
                'stask_desc' => 'Configure water and gas utility provider connections.',
                'stask_attachment' => null,
                'stask_deadline' => Carbon::now()->addDays(15),
                'stask_task' => 5,
                'stask_assignedto' => 16, // Different from task_assignedto (14)
                'stask_priority' => 2,
                'stask_accessmod' => 1,
                'delete_status' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],

            // Subtasks for Task 6 (Implement Biometric Auth)
            [
                'stask_name' => 'Fingerprint Authentication',
                'stask_desc' => 'Implement fingerprint recognition for mobile login.',
                'stask_attachment' => null,
                'stask_deadline' => Carbon::now()->addDays(20),
                'stask_task' => 6,
                'stask_assignedto' => 14, // Different from task_assignedto (5)
                'stask_priority' => 3,
                'stask_accessmod' => 1,
                'delete_status' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'stask_name' => 'Facial Recognition Setup',
                'stask_desc' => 'Configure facial recognition authentication system.',
                'stask_attachment' => null,
                'stask_deadline' => Carbon::now()->addDays(25),
                'stask_task' => 6,
                'stask_assignedto' => 16, // Different from task_assignedto (5)
                'stask_priority' => 3,
                'stask_accessmod' => 1,
                'delete_status' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],

            // Subtasks for Task 7 (Design EHR Database Schema)
            [
                'stask_name' => 'Patient Table Design',
                'stask_desc' => 'Create patient information table structure.',
                'stask_attachment' => null,
                'stask_deadline' => Carbon::now()->addDays(8),
                'stask_task' => 7,
                'stask_assignedto' => 7, // Different from task_assignedto (17)
                'stask_priority' => 2,
                'stask_accessmod' => 1,
                'delete_status' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'stask_name' => 'Medical Records Relationships',
                'stask_desc' => 'Define relationships between medical record tables.',
                'stask_attachment' => null,
                'stask_deadline' => Carbon::now()->addDays(12),
                'stask_task' => 7,
                'stask_assignedto' => 8, // Different from task_assignedto (17)
                'stask_priority' => 2,
                'stask_accessmod' => 1,
                'delete_status' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],

            // Subtasks for Task 8 (Develop Calendar Interface)
            [
                'stask_name' => 'Calendar UI Components',
                'stask_desc' => 'Build reusable calendar components for the interface.',
                'stask_attachment' => null,
                'stask_deadline' => Carbon::now()->addDays(7),
                'stask_task' => 8,
                'stask_assignedto' => 18, // Different from task_assignedto (9)
                'stask_priority' => 2,
                'stask_accessmod' => 0,
                'delete_status' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'stask_name' => 'Time Slot Management',
                'stask_desc' => 'Implement time slot booking and availability system.',
                'stask_attachment' => null,
                'stask_deadline' => Carbon::now()->addDays(12),
                'stask_task' => 8,
                'stask_assignedto' => 19, // Different from task_assignedto (9)
                'stask_priority' => 2,
                'stask_accessmod' => 0,
                'delete_status' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],

            // Subtasks for Task 9 (Create Insurance Claim Form)
            [
                'stask_name' => 'Digital Form Design',
                'stask_desc' => 'Design the insurance claim submission form layout.',
                'stask_attachment' => null,
                'stask_deadline' => Carbon::now()->addDays(10),
                'stask_task' => 9,
                'stask_assignedto' => 17, // Different from task_assignedto (20)
                'stask_priority' => 2,
                'stask_accessmod' => 1,
                'delete_status' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'stask_name' => 'Document Upload Feature',
                'stask_desc' => 'Implement file upload functionality for supporting documents.',
                'stask_attachment' => null,
                'stask_deadline' => Carbon::now()->addDays(18),
                'stask_task' => 9,
                'stask_assignedto' => 19, // Different from task_assignedto (20)
                'stask_priority' => 2,
                'stask_accessmod' => 1,
                'delete_status' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],

            // Subtasks for Task 10 (Build Enrollment Dashboard)
            [
                'stask_name' => 'Dashboard Layout',
                'stask_desc' => 'Create the main dashboard layout and navigation.',
                'stask_attachment' => null,
                'stask_deadline' => Carbon::now()->addDays(5),
                'stask_task' => 10,
                'stask_assignedto' => 11, // Different from task_assignedto (10)
                'stask_priority' => 2,
                'stask_accessmod' => 0,
                'delete_status' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'stask_name' => 'Application Management',
                'stask_desc' => 'Build functionality to manage student applications.',
                'stask_attachment' => null,
                'stask_deadline' => Carbon::now()->addDays(8),
                'stask_task' => 10,
                'stask_assignedto' => 12, // Different from task_assignedto (10)
                'stask_priority' => 2,
                'stask_accessmod' => 0,
                'delete_status' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],

            // Subtasks for Task 11 (Develop Grade Calculator)
            [
                'stask_name' => 'Grade Calculation Logic',
                'stask_desc' => 'Implement core grade calculation algorithms.',
                'stask_attachment' => null,
                'stask_deadline' => Carbon::now()->addDays(8),
                'stask_task' => 11,
                'stask_assignedto' => 12, // Different from task_assignedto (11)
                'stask_priority' => 2,
                'stask_accessmod' => 1,
                'delete_status' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'stask_name' => 'Weighting System',
                'stask_desc' => 'Create grade weighting and curve options.',
                'stask_attachment' => null,
                'stask_deadline' => Carbon::now()->addDays(12),
                'stask_task' => 11,
                'stask_assignedto' => 13, // Different from task_assignedto (11)
                'stask_priority' => 2,
                'stask_accessmod' => 1,
                'delete_status' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],

            // Subtasks for Task 12 (Implement QR Code Attendance)
            [
                'stask_name' => 'QR Code Generation',
                'stask_desc' => 'Develop system to generate unique QR codes for classes.',
                'stask_attachment' => null,
                'stask_deadline' => Carbon::now()->addDays(10),
                'stask_task' => 12,
                'stask_assignedto' => 9, // Different from task_assignedto (13)
                'stask_priority' => 1,
                'stask_accessmod' => 0,
                'delete_status' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'stask_name' => 'QR Scanning Interface',
                'stask_desc' => 'Create mobile interface for scanning QR codes.',
                'stask_attachment' => null,
                'stask_deadline' => Carbon::now()->addDays(15),
                'stask_task' => 12,
                'stask_assignedto' => 12, // Different from task_assignedto (13)
                'stask_priority' => 1,
                'stask_accessmod' => 0,
                'delete_status' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],

            // Subtasks for Task 13 (Create Barcode Generator)
            [
                'stask_name' => 'Barcode Algorithm',
                'stask_desc' => 'Implement barcode generation algorithm.',
                'stask_attachment' => null,
                'stask_deadline' => Carbon::now()->addDays(4),
                'stask_task' => 13,
                'stask_assignedto' => 16, // Different from task_assignedto (15)
                'stask_priority' => 2,
                'stask_accessmod' => 0,
                'delete_status' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'stask_name' => 'Barcode Printing System',
                'stask_desc' => 'Create system for printing barcode labels.',
                'stask_attachment' => null,
                'stask_deadline' => Carbon::now()->addDays(6),
                'stask_task' => 13,
                'stask_assignedto' => 17, // Different from task_assignedto (15)
                'stask_priority' => 2,
                'stask_accessmod' => 0,
                'delete_status' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],

            // Subtasks for Task 14 (Set Up Low Stock Notifications)
            [
                'stask_name' => 'Email Alert System',
                'stask_desc' => 'Configure automatic email notifications for low stock.',
                'stask_attachment' => null,
                'stask_deadline' => Carbon::now()->addDays(12),
                'stask_task' => 14,
                'stask_assignedto' => 15, // Different from task_assignedto (17)
                'stask_priority' => 3,
                'stask_accessmod' => 0,
                'delete_status' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'stask_name' => 'SMS Notification Setup',
                'stask_desc' => 'Implement SMS alerts for critical low stock levels.',
                'stask_attachment' => null,
                'stask_deadline' => Carbon::now()->addDays(18),
                'stask_task' => 14,
                'stask_assignedto' => 16, // Different from task_assignedto (17)
                'stask_priority' => 3,
                'stask_accessmod' => 0,
                'delete_status' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],

            // Subtasks for Task 15 (Build Inventory Analytics)
            [
                'stask_name' => 'Data Visualization',
                'stask_desc' => 'Create charts and graphs for inventory analytics.',
                'stask_attachment' => null,
                'stask_deadline' => Carbon::now()->addDays(15),
                'stask_task' => 15,
                'stask_assignedto' => 16, // Different from task_assignedto (18)
                'stask_priority' => 1,
                'stask_accessmod' => 1,
                'delete_status' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'stask_name' => 'Performance Metrics',
                'stask_desc' => 'Implement inventory turnover and performance calculations.',
                'stask_attachment' => null,
                'stask_deadline' => Carbon::now()->addDays(20),
                'stask_task' => 15,
                'stask_assignedto' => 17, // Different from task_assignedto (18)
                'stask_priority' => 1,
                'stask_accessmod' => 1,
                'delete_status' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];

        foreach ($subtasks as $subtask) {
            Subtask::create($subtask);
        }
    }
}
