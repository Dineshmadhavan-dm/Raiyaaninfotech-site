<?php

namespace Database\Seeders;

use App\Models\Relationship;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RelationshipSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $relations = [
            'Mother',
            'Father',
            'Sister',
            'Brother',
            'Aunty',
            'Uncle',
            'Grandmother',
            'Grandfather',
            'Niece',
            'Nephew',
            'Daughter',
            'Son',
            'Brother-in-law',
            'Sister-in-law',
            'Father-in-law',
            'Mother-in-law',
            'Stepmother'
        ];

        foreach ($relations as $relation) {
            Relationship::create(['relationship_name' => $relation]);
        }
    }
}
