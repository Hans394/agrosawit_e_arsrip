<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Jalankan seeder untuk membuat akun admin default
     */
    public function run(): void
    {
        Admin::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name'     => 'Super Admin',
                'email'    => 'admin@example.com',
                'password' => Hash::make('password123'),
            ]
        );

        $this->command->info('Admin default berhasil dibuat:');
        $this->command->info('Email    : admin@example.com');
        $this->command->info('Password : password123');
    }
}