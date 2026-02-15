<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Company;
use App\Models\CompanyMeta;
use App\Models\User;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $schools = [
            'School'
        ];

        User::factory()->create([
            'name' => 'Superadmin',
            'email' => 'superadmin@example.com',
            'role_id' => 1,
            'company_id' => null,
            'password' => bcrypt('superadmin@example.com'),
        ]);        

        foreach ($schools as $schoolName) {
            $company = Company::create([
                'name' => $schoolName,
                'email' => strtolower(str_replace(' ', '', $schoolName)) . '@example.com',
                'phone' => '1234567890',
                'address' => '123 Main St, City, Country',
                'website' => 'https://' . strtolower(str_replace(' ', '', $schoolName)) . '.com',
                'google_map' => '<iframe src="https://maps.google.com/..."></iframe>',
                'is_active' => 1,
            ]);

            // $metaData = [
            //     ['meta_key' => 'brochure_attachment', 'meta_value' => 'path/to/brochure.pdf'],
            //     ['meta_key' => 'email2', 'meta_value' => 'support@example.com'],
            //     ['meta_key' => 'phone2', 'meta_value' => '0987654321'],
            //     ['meta_key' => 'whatsapp2', 'meta_value' => '0987654321'],
            //     ['meta_key' => 'address2', 'meta_value' => '456 Secondary Street, City, Country'],
            //     ['meta_key' => 'google_map2', 'meta_value' => '<iframe src="https://maps.google.com" ...></iframe>'],
            //     ['meta_key' => 'facebook_url', 'meta_value' => 'https://facebook.com/' . strtolower(str_replace(' ', '', $schoolName))],
            //     ['meta_key' => 'instagram_url', 'meta_value' => 'https://instagram.com/' . strtolower(str_replace(' ', '', $schoolName))],
            //     ['meta_key' => 'linkedin_url', 'meta_value' => 'https://linkedin.com/company/' . strtolower(str_replace(' ', '', $schoolName))],
            //     ['meta_key' => 'youtube_url', 'meta_value' => 'https://youtube.com/' . strtolower(str_replace(' ', '', $schoolName))],
            // ];

            // foreach ($metaData as $meta) {
            //     $company->meta()->create($meta);
            // }

            // User::factory()->create([
            //     'name' => 'School admin ' . ($company->id),
            //     'email' => 'school' . ($company->id) . '@example.com',
            //     'role_id' => 2,
            //     'company_id' => $company->id,
            //     'password' => bcrypt('school' . ($company->id) . '@example.com'),
            // ]);            
        }
    }
}
