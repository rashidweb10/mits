<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\TeamCategory;

class TeamCategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TeamCategory::create(['slug' => 'founders-desk', 'name' => "Founder's Desk", 'company_id' => 3]);
        TeamCategory::create(['slug' => 'presidents-desk', 'name' => "President's Desk", 'company_id' => 3]);
        TeamCategory::create(['slug' => 'principals-desk', 'name' => "Principal's Desk", 'company_id' => 3]);
        TeamCategory::create(['slug' => 'management', 'name' => "Management", 'company_id' => 3]);
        TeamCategory::create(['slug' => 'our-team', 'name' => "Our Team", 'company_id' => 3]);
        TeamCategory::create(['slug' => 'leadership', 'name' => "Leadership", 'company_id' => 3]);
    }
}
