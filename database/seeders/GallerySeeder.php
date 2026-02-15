<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Gallery;

class GallerySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Gallery::create([
            'name' => 'Nature Gallery',
            'description' => 'A collection of breathtaking nature photographs.',
            'thumbnail' => null,
            'gallery' => null,
            'year' => '2023',
            'is_active' => true,
            'company_id' => 1,
        ]);

        Gallery::create([
            'name' => 'Urban Life',
            'description' => 'Photos capturing the essence of urban living.',
            'thumbnail' => null,
            'gallery' => null,
            'year' => '2022',
            'is_active' => true,
            'company_id' => 2,
        ]);

        Gallery::create([
            'name' => 'Wildlife Adventures',
            'description' => 'Explore the wildlife through stunning photography.',
            'thumbnail' => null,
            'gallery' => null,
            'year' => '2021',
            'is_active' => false,
            'company_id' => null,
        ]);
    }
}
