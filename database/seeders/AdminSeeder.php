<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // Contoh password kuat 8 karakter: J&j7!k9A
        // Kombinasi: Huruf Besar, Karakter Khusus, Huruf Kecil, Angka
        $strongPassword = 'J&j7!k9A'; 

        User::updateOrCreate(
            ['email' => 'admin@jnjgroup.com'],
            [
                'name' => 'Premium Admin',
                'password' => Hash::make($strongPassword),
                'role' => User::ROLE_ADMIN,
            ]
        );
    }
}