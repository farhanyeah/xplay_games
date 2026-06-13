<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::insert([

            [
                'name' => 'Owner XPLAY',
                'email' => 'owner@xplay.com',
                'password' => Hash::make('password123'),
                'role' => 'owner',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'name' => 'Staf Admin',
                'email' => 'staf@xplay.com',
                'password' => Hash::make('password123'),
                'role' => 'staf',
                'created_at' => now(),
                'updated_at' => now(),
            ],

        ]);
    }
}