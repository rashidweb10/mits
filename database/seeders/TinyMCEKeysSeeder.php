<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\TinyMCEKey;
use Carbon\Carbon;

class TinyMCEKeysSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $keys = [
            '9tmptd0mfn1if1dhtwwvea0fmr4upqbefnen7r2nj7racku5',
            'oao7dvt6bwvrchihs4fuxq9hs7cx1gad1ogh6zjhouovy9ub'
        ];

        $now = Carbon::now();
        foreach ($keys as $key) {
            TinyMCEKey::firstOrCreate([
                'api_key' => $key,
                'month'   => $now->month,
                'year'    => $now->year
            ], [
                'count'   => 0
            ]);
        }
    }
}
