<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UsersSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'first_name' => 'Admin',
                'last_name' => 'User',
                'email' => 'admin@watotochurch.com',
                'phone' => '+256778208159',
                'password' => password_hash('admin123', PASSWORD_DEFAULT),
                'role' => 'admin',
                'is_active' => true,
                'email_verified_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'first_name' => 'John',
                'last_name' => 'Doe',
                'email' => 'john@example.com',
                'phone' => '+256700123456',
                'password' => password_hash('password123', PASSWORD_DEFAULT),
                'role' => 'user',
                'is_active' => true,
                'email_verified_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'first_name' => 'Sarah',
                'last_name' => 'Johnson',
                'email' => 'sarah@example.com',
                'phone' => '+256701234567',
                'password' => password_hash('password123', PASSWORD_DEFAULT),
                'role' => 'user',
                'is_active' => true,
                'email_verified_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'first_name' => 'Grace',
                'last_name' => 'Nakamura',
                'email' => 'grace@example.com',
                'phone' => '+256702345678',
                'password' => password_hash('password123', PASSWORD_DEFAULT),
                'role' => 'user',
                'is_active' => true,
                'email_verified_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('users')->insertBatch($data);
    }
}
