<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    // public function run(): void
    // {
    //     $superAdminRole = Role::firstOrCreate(['name' => 'Super admin', 'guard_name' => 'web']);
    //     $permissions = Permission::all();
    //     $superAdminRole->syncPermissions($permissions);
    // }


    public function run(): void
    {
        // Create or get the Super admin role
        // Create or get the Super admin role
        // Roles and permissions
        $superAdminRole = Role::firstOrCreate(['name' => 'Super admin', 'guard_name' => 'web']);
        $permissions = Permission::all();
        $superAdminRole->syncPermissions($permissions);

        // Create Super Admin user with categorie = 1
        $user = User::firstOrCreate(
            ['email' => 'superadmin@raiyaaninfotech.com'],
            [
                'name' => 'Raiyaaninfotech',
                'password' => bcrypt('(1y%wuef28C3'),
                'email_verified_at' => now(),
                'categorie' => 1, // 👈 Save as number
            ]
        );

        $user->assignRole($superAdminRole);
    }
}
