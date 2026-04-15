<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Client;

class ClientSeeder extends Seeder
{
    public function run(): void
    {
        $clients = [
            [
                'cl_name' => 'Arun Kumar',
                'cl_email' => 'arun@example.com',
                'cl_password' => 'password123',
                'cl_image' => null,
            ],
            [
                'cl_name' => 'Priya Sharma',
                'cl_email' => 'priya@example.com',
                'cl_password' => 'password123',
                'cl_image' => null,
            ],
            [
                'cl_name' => 'Ravi Patel',
                'cl_email' => 'ravi@example.com',
                'cl_password' => 'password123',
                'cl_image' => null,
            ],
            [
                'cl_name' => 'Sneha Reddy',
                'cl_email' => 'sneha@example.com',
                'cl_password' => 'password123',
                'cl_image' => null,
            ],
            [
                'cl_name' => 'Manoj Singh',
                'cl_email' => 'manoj@example.com',
                'cl_password' => 'password123',
                'cl_image' => null,
            ],
            [
                'cl_name' => 'Neha Verma',
                'cl_email' => 'neha@example.com',
                'cl_password' => 'password123',
                'cl_image' => null,
            ],
            [
                'cl_name' => 'Rahul Mehta',
                'cl_email' => 'rahul@example.com',
                'cl_password' => 'password123',
                'cl_image' => null,
            ],
        ];

        foreach ($clients as $client) {
            Client::create($client);
        }
    }
}
