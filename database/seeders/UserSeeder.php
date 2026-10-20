<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Cek apakah user admin sudah ada
        $existingAdmin = User::where('email', 'admin@bulog.co.id')->first();

        if (!$existingAdmin) {
            // Buat user admin utama
            User::create([
                'name' => 'Administrator',
                'email' => 'admin@bulog.co.id',
                'password' => Hash::make('password123'),
                'role' => 'admin',
                'is_active' => true,
                'phone' => '+62 21 12345678',
                'address' => 'Kantor Pusat Bulog, Jakarta',
                'email_verified_at' => now(),
            ]);

            $this->command->info('User admin berhasil dibuat!');
            $this->command->info('Email: admin@bulog.co.id');
            $this->command->info('Password: password123');
            $this->command->warn('⚠️ Jangan lupa ganti password setelah login pertama!');
        } else {
            $this->command->info('User admin sudah ada dalam database.');
        }

        // Optional: Tambahkan user contoh lainnya (jika diperlukan untuk development)
        if (app()->environment('local')) {
            $this->createSampleUsers();
        }
    }

    /**
     * Create sample users for development environment
     */
    private function createSampleUsers(): void
    {
        $sampleUsers = [
            [
                'name' => 'Staff Operasional',
                'email' => 'staff@bulog.co.id',
                'password' => Hash::make('password123'),
                'role' => 'staff', // HANYA staff
                'is_active' => true,
                'phone' => '+62 21 34567890',
                'address' => 'Divisi Operasional, Kantor Pusat Bulog',
                'email_verified_at' => now(),
            ],
            // HAPUS manager dan viewer
        ];

        foreach ($sampleUsers as $user) {
            $existingUser = User::where('email', $user['email'])->first();

            if (!$existingUser) {
                User::create($user);
            }
        }

        $this->command->info('Sample users berhasil dibuat untuk environment development.');
    }
}
