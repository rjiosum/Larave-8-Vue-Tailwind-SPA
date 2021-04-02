<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::factory()->create([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john@gmail.com',
        ]);

        User::factory()->create([
            'first_name' => 'Oliver',
            'last_name' => 'Bernard',
            'email' => 'oli@gmail.com',
        ]);
    }
}
