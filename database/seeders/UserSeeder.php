<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Jalankan seeder untuk membuat akun user default
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'user@example.com'],
            [
                'name'     => 'User Default',
                'email'    => 'user@example.com',
                'password' => Hash::make('password123'),
            ]
        );

        $this->command->info('User default berhasil dibuat:');
        $this->command->info('Email    : user@example.com');
        $this->command->info('Password : password123');
    }
}