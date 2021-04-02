<?php

namespace Database\Seeders;

use App\Models\Country;
use Illuminate\Database\Seeder;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Country::factory()->create([
            'name' => 'United Kingdom',
            'ISOAlpha2' => 'GB',
            'ISOAlpha3' => 'GBR',
            'status' => 1,
        ]);
        Country::factory()->create([
            'name' => 'United States Of America',
            'ISOAlpha2' => 'US',
            'ISOAlpha3' => 'USA',
            'status' => 1,
        ]);
    }
}
