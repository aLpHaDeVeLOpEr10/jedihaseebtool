<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@jedisebitool.com'],
            [
                'name'     => 'Admin User',
                'email'    => 'admin@jedisebitool.com',
                'password' => Hash::make('password123'),
                'role'     => 'admin',
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );
    }
}
