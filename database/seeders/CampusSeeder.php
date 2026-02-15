<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Campus;

class CampusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Campus::insert([
            [
                'name' => 'Main Campus',
                'description' => 'The main campus of the university.',
                'gallery' => null,
                'is_active' => true,
                'company_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'East Campus',
                'description' => 'The east campus of the university.',
                'gallery' => null,
                'is_active' => true,
                'company_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
