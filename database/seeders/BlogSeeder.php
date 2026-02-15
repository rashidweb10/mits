<?php

namespace Database\Seeders;

use App\Models\Blog;
use App\Models\BlogCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BlogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $companyId = 1;

        $categoriesBySlug = BlogCategory::where('is_active', 1)
            ->where('company_id', $companyId)
            ->get()
            ->keyBy('slug');

        $items = [
            [
                'title' => 'Welcome to our Blog',
                'excerpt' => '<p>A quick introduction and what you can expect from us.</p>',
                'content' => '<p>This is a demo blog post for testing your frontend listing and detail pages.</p><p>You can edit or replace this content from the backend.</p>',
                'seo_title' => 'Welcome to our Blog',
                'seo_description' => 'A quick introduction and what you can expect from us.',
                'published_days_ago' => 2,
                'category_slugs' => ['news', 'tips'],
            ],
            [
                'title' => 'Upcoming Workshop Schedule',
                'excerpt' => '<p>See what workshops are coming up this month.</p>',
                'content' => '<p>We regularly host workshops and seminars. This post is seeded as dummy content.</p>',
                'seo_title' => 'Upcoming Workshop Schedule',
                'seo_description' => 'See what workshops are coming up this month.',
                'published_days_ago' => 5,
                'category_slugs' => ['events'],
            ],
            [
                'title' => '5 Study Tips to Stay Consistent',
                'excerpt' => '<p>Simple tips to keep your learning on track.</p>',
                'content' => '<p>Consistency beats intensity. This is dummy content to test formatting on the frontend.</p>',
                'seo_title' => '5 Study Tips to Stay Consistent',
                'seo_description' => 'Simple tips to keep your learning on track.',
                'published_days_ago' => 8,
                'category_slugs' => ['tips'],
            ],
            [
                'title' => 'Placement Prep: Resume Basics',
                'excerpt' => '<p>Start with the fundamentals of a strong resume.</p>',
                'content' => '<p>A resume is your first impression. This post is seeded for testing.</p>',
                'seo_title' => 'Placement Prep: Resume Basics',
                'seo_description' => 'Start with the fundamentals of a strong resume.',
                'published_days_ago' => 12,
                'category_slugs' => ['careers', 'tips'],
            ],
            [
                'title' => 'Campus Event Highlights',
                'excerpt' => '<p>A recap of recent campus activities.</p>',
                'content' => '<p>Highlights and photos can be added here. Dummy seeded blog for frontend testing.</p>',
                'seo_title' => 'Campus Event Highlights',
                'seo_description' => 'A recap of recent campus activities.',
                'published_days_ago' => 15,
                'category_slugs' => ['events', 'news'],
            ],
            [
                'title' => 'How to Choose the Right Course',
                'excerpt' => '<p>Questions to ask before you enroll.</p>',
                'content' => '<p>Choosing the right course depends on your goals. Dummy content seeded for testing.</p>',
                'seo_title' => 'How to Choose the Right Course',
                'seo_description' => 'Questions to ask before you enroll.',
                'published_days_ago' => 20,
                'category_slugs' => ['tips'],
            ],
            [
                'title' => 'Industry Updates and Trends',
                'excerpt' => '<p>What is changing and why it matters.</p>',
                'content' => '<p>This seeded post helps test pagination and category filtering.</p>',
                'seo_title' => 'Industry Updates and Trends',
                'seo_description' => 'What is changing and why it matters.',
                'published_days_ago' => 25,
                'category_slugs' => ['news'],
            ],
            [
                'title' => 'Interview Prep Checklist',
                'excerpt' => '<p>A practical checklist for interview day.</p>',
                'content' => '<p>This seeded blog post is for testing. Add your real checklist later.</p>',
                'seo_title' => 'Interview Prep Checklist',
                'seo_description' => 'A practical checklist for interview day.',
                'published_days_ago' => 30,
                'category_slugs' => ['careers'],
            ],
        ];

        foreach ($items as $item) {
            $slug = Str::slug($item['title']);

            $blog = Blog::updateOrCreate(
                ['slug' => $slug, 'company_id' => $companyId],
                [
                    'title' => $item['title'],
                    'excerpt' => $item['excerpt'],
                    'content' => $item['content'],
                    'image' => null,
                    'seo_title' => $item['seo_title'],
                    'seo_description' => $item['seo_description'],
                    'published_at' => now()->subDays($item['published_days_ago']),
                    'is_active' => 1,
                ]
            );

            $categoryIds = collect($item['category_slugs'])
                ->map(function ($slug) use ($categoriesBySlug) {
                    return $categoriesBySlug[$slug]->id ?? null;
                })
                ->filter()
                ->values()
                ->toArray();

            if (!empty($categoryIds)) {
                $blog->categories()->sync($categoryIds);
            }
        }
    }
}
