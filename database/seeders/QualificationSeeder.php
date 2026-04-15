<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Qualification; // ✅ Add this line

class QualificationSeeder extends Seeder
{
    public function run(): void
    {
        $qualifications = [
            'B.Tech',
            'MBA',
            'B.SC',
            'M.Tech',
            'B.Com',
            'MCA',
            'BA',
            'BBA',
            'M.Sc',
            'M.Com',
            'B.Ed',
            'M.A',
            'Diploma',
            'Ph.D.',
            'BCA',
            'MBBS',
            'B.Arch',
            'LLB',
            'M.Ed',
            'CA',
            'B.Sc. Mathematics
'
, 'Bsc'
        ];

        foreach ($qualifications as $qualification) {
            Qualification::create(['qua_name' => trim($qualification)]);
        }
    }
}
