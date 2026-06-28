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

        // User Example
        User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password'
        ]);

        // Admin User Example
        User::create([
            'name' => 'Admin Amba',
            'email' => 'admin@ambacinema.com',
            'password' => 'password',
            'role' => 'admin',
        ]);
    }
}
