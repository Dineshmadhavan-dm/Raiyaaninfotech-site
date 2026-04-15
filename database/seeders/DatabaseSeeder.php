<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\Department;
use App\Models\Designation;
use App\Models\User;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use SebastianBergmann\CodeCoverage\Report\Xml\Project;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        $this->call(PermissionsTableSeeder::class);
        $this->call(RolePermissionSeeder::class);
        $this->call(BranchesTableSeeder::class);
        $this->call(DepartmentsTableSeeder::class);
        $this->call(DesignationsTableSeeder::class);
        $this->call(RoleSeeder::class);
        $this->call(BloodGroupSeeder::class);
        $this->call(RelationshipSeeder::class);
        $this->call(
            JobTypeSeeder::class,
        );
        $this->call(QualificationSeeder::class);
        $this->call(HolidayTypeSeeder::class);

                $this->call(ClientSeeder::class);


    }
}
