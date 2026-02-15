<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Team;
use App\Models\TeamCategory;

class TeamsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = TeamCategory::pluck('id'); // Get all category IDs

        $teams = [
            [
                'name' => 'John Doe',
                'slug' => 'john-doe',
                'designation' => 'CEO',
                'description' => 'Leader of the company',
                'company_id' => 3
            ],
            [
                'name' => 'Jane Smith',
                'slug' => 'jane-smith',
                'designation' => 'CTO',
                'description' => 'In charge of technology',
                'company_id' => 3
            ],
            [
                'name' => 'Michael Johnson',
                'slug' => 'michael-johnson',
                'designation' => 'COO',
                'description' => 'Handles operations',
                'company_id' => 3
            ],
            [
                'name' => 'Emily Brown',
                'slug' => 'emily-brown',
                'designation' => 'CMO',
                'description' => 'Marketing expert',
                'company_id' => 3
            ]
        ];

        foreach ($teams as $teamData) {
            $team = Team::create($teamData);
            $team->categories()->attach($categories); // Attach all categories to each team
        }
    }
}