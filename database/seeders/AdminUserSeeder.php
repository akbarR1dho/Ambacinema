<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\User::create([
            'name' => 'Admin Cinema',
            'email' => 'admin@ambacinema.com',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
            'role' => 'admin',
        ]);
    }
}
