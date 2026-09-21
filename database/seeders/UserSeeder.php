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
            ['email' => 'admin@toolsearch.online'],
            [
                'name'     => 'Admin User',
                'email'    => 'admin@toolsearch.online',
                'password' => Hash::make('password123'),
                'role'     => 'admin',
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );
    }
}
