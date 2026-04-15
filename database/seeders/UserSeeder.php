<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User; // Import your model
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Raiyaaninfotech',
            'email' => 'superadmin@raiyaaninfotech.com',
            'password' => Hash::make('(1y%wuef28C3'),
        ]);
    }
}
