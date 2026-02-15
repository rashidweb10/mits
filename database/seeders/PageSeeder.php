<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Page;
use App\Models\PageMeta;

class PageSeeder extends Seeder
{
    public function run()
    {
        // Array of pages to create
        $pages = [
            [
                'slug' => 'home',
                'language' => 'en',
                'title' => 'Home',
                'content' => 'Welcome to the home page.',
                'seo_title' => 'Home - Marinarch',
                'seo_description' => 'Welcome to Marinarch - Home page.',
                'seo_keywords' => 'Home, Marinarch',
                'layout' => 'home',
                'is_active' => true,
                'company_id' => 1,
                'meta' => [],
            ],
            [
                'slug' => 'about-us',
                'language' => 'en',
                'title' => 'About Us',
                'content' => 'This is the about us page content.',
                'seo_title' => 'About Us - Marinarch',
                'seo_description' => 'Learn more about Marinarch.',
                'seo_keywords' => 'about us, Marinarch',
                'layout' => 'about',
                'is_active' => true,
                'company_id' => 2,
                'meta' => [],
            ],
            [
                'slug' => 'testimonials',
                'language' => 'en',
                'title' => 'Testimonials',
                'content' => 'This is the testimonials page content.',
                'seo_title' => 'Testimonials - Marinarch',
                'seo_description' => 'Learn more about Marinarch.',
                'seo_keywords' => 'Testimonials, Marinarch',
                'layout' => 'testimonials',
                'is_active' => true,
                'company_id' => 2,
                'meta' => [],
            ],
            [
                'slug' => 'faculties',
                'language' => 'en',
                'title' => 'Faculties',
                'content' => 'This is the faculties page content.',
                'seo_title' => 'Faculties - Marinarch',
                'seo_description' => 'Learn more about Marinarch.',
                'seo_keywords' => 'Faculties, Marinarch',
                'layout' => 'faculties',
                'is_active' => true,
                'company_id' => 2,
                'meta' => [],
            ],
            [
                'slug' => 'terms-and-conditions',
                'language' => 'en',
                'title' => 'Terms & Conditions',
                'content' => 'This is the Terms & Conditions page content.',
                'seo_title' => 'Terms & Conditions - Marinarch',
                'seo_description' => 'Learn more about Marinarch.',
                'seo_keywords' => 'Terms & Conditions, Marinarch',
                'layout' => 'default',
                'is_active' => true,
                'company_id' => 2,
                'meta' => [],
            ],
            [
                'slug' => 'privacy-policy',
                'language' => 'en',
                'title' => 'Privacy Policy',
                'content' => 'This is the Privacy Policy page content.',
                'seo_title' => 'Privacy Policy - Marinarch',
                'seo_description' => 'Learn more about Marinarch.',
                'seo_keywords' => 'Privacy Policy, Marinarch',
                'layout' => 'default',
                'is_active' => true,
                'company_id' => 2,
                'meta' => [],
            ]                                          
        ];

        // Loop through the pages and create them with metadata
        foreach ($pages as $pageData) {
            $metaData = $pageData['meta'];
            unset($pageData['meta']); // Remove meta data from the main array

            // Create the page
            $page = Page::create($pageData);

            // Add metadata for the page
            
            foreach ($metaData as $meta) {
                if(!empty($meta)) {
                    PageMeta::create([
                        'page_id' => $page->id,
                        'meta_key' => $meta['meta_key'],
                        'meta_value' => $meta['meta_value'],
                    ]);
                }
            }
        }
    }
}