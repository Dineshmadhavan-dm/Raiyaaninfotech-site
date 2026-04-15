<?php

namespace Database\Seeders;


use App\Models\Holidaytype;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class HolidayTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $holidaytypes = [
            'Local Holiday',
            'National Holiday',
            'Religious Holiday',

        ];

        foreach ($holidaytypes as $holidaytype) {
            Holidaytype::create(['holidaytype_name' => $holidaytype]);
        }
    }
}
