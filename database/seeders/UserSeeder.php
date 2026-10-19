<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@sipes.test'],
            [
                'name' => 'Rina Administrator',
                'password' => 'password',
                'role' => User::ROLE_ADMIN,
                'email_verified_at' => now(),
            ],
        );

        User::updateOrCreate(
            ['email' => 'user@sipes.test'],
            [
                'name' => 'Budi Koordinator',
                'password' => 'password',
                'role' => User::ROLE_USER,
                'email_verified_at' => now(),
            ],
        );
    }
}
