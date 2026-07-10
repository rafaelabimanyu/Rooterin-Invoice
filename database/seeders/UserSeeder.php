<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $usersData = [
            [
                'name' => 'J&J GROUP Owner',
                'email' => 'owner@jnjgroup.com',
                'password' => Hash::make('password'),
                'role' => User::ROLE_OWNER,
                'is_active' => 1,
                'locale' => 'id',
            ],
            [
                'name' => 'System Admin',
                'email' => 'admin@jnjgroup.com',
                'password' => Hash::make('J&j7!k9A'), // Password kuat sesuai instruksi
                'role' => User::ROLE_ADMIN,
                'is_active' => 1,
                'locale' => 'id',
            ],
            [
                'name' => 'Staff Member 1',
                'email' => 'staff1@jnjgroup.com',
                'password' => Hash::make('password'),
                'role' => User::ROLE_STAFF,
                'is_active' => 1,
                'locale' => 'id',
            ],
            [
                'name' => 'Staff Member 2',
                'email' => 'staff2@jnjgroup.com',
                'password' => Hash::make('password'),
                'role' => User::ROLE_STAFF,
                'is_active' => 1,
                'locale' => 'en',
            ],
            [
                'name' => 'Staff Member 3',
                'email' => 'staff3@jnjgroup.com',
                'password' => Hash::make('password'),
                'role' => User::ROLE_STAFF,
                'is_active' => 1,
                'locale' => 'id',
            ],
            [
                'name' => 'Roni Wijaya',
                'email' => 'roni@jnjgroup.com',
                'password' => Hash::make('password'),
                'role' => User::ROLE_STAFF,
                'is_active' => 1,
                'locale' => 'id',
            ],
            [
                'name' => 'Sarah Siregar',
                'email' => 'sarah@jnjgroup.com',
                'password' => Hash::make('password'),
                'role' => User::ROLE_STAFF,
                'is_active' => 1,
                'locale' => 'en',
            ],
            [
                'name' => 'Muhammad Fikri',
                'email' => 'fikri@jnjgroup.com',
                'password' => Hash::make('password'),
                'role' => User::ROLE_STAFF,
                'is_active' => 1,
                'locale' => 'id',
            ],
            [
                'name' => 'Clarissa Utama',
                'email' => 'clarissa@jnjgroup.com',
                'password' => Hash::make('password'),
                'role' => User::ROLE_ADMIN,
                'is_active' => 1,
                'locale' => 'id',
            ],
        ];

        foreach ($usersData as $data) {
            User::updateOrCreate(
                ['email' => $data['email']],
                $data
            );
        }
    }
}
