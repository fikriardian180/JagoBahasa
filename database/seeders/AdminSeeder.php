<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $email = config('admin.email');
        $password = config('admin.password');

        if (! $email || ! $password) {
            $this->command->warn('ADMIN_EMAIL atau ADMIN_PASSWORD belum diatur di file .env');
            return;
        }

        User::updateOrCreate(
            [
                'email' => $email,
                'role' => 'admin',
            ],
            [
                'name' => config('admin.name'),
                'password' => Hash::make($password),
            ]
        );

        $this->command->info("Akun Administrator ({$email}) berhasil disinkronkan dari .env.");
    }
}