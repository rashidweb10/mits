<?php

namespace Database\Seeders;

use App\Models\BlogCategory;
use Illuminate\Database\Seeder;

class BlogCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $companyId = 1;

        $categories = [
            [
                'slug' => 'news',
                'name' => 'News',
                'description' => 'Latest updates and announcements.',
                'meta_title' => 'News',
                'meta_description' => 'Latest updates and announcements.',
            ],
            [
                'slug' => 'events',
                'name' => 'Events',
                'description' => 'Workshops, seminars, and upcoming events.',
                'meta_title' => 'Events',
                'meta_description' => 'Workshops, seminars, and upcoming events.',
            ],
            [
                'slug' => 'tips',
                'name' => 'Tips & Guides',
                'description' => 'Helpful tips and guides for students.',
                'meta_title' => 'Tips & Guides',
                'meta_description' => 'Helpful tips and guides for students.',
            ],
            [
                'slug' => 'careers',
                'name' => 'Careers',
                'description' => 'Career advice and placement insights.',
                'meta_title' => 'Careers',
                'meta_description' => 'Career advice and placement insights.',
            ],
        ];

        foreach ($categories as $data) {
            BlogCategory::updateOrCreate(
                ['slug' => $data['slug'], 'company_id' => $companyId],
                [
                    'name' => $data['name'],
                    'description' => $data['description'],
                    'meta_title' => $data['meta_title'],
                    'meta_description' => $data['meta_description'],
                    'is_active' => 1,
                ]
            );
        }
    }
}
