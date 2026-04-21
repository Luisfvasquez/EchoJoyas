<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Luis Vasquez',
            'email' => 'wueyluis@gmail.com',
            'password' => bcrypt('Relojeria13.'),
            'is_admin' => true,
        ]);
    }
}
