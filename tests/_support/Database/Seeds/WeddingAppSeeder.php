<?php

namespace Tests\Support\Database\Seeds;

use CodeIgniter\Database\Seeder;

class WeddingAppSeeder extends Seeder
{
    public function run(): void
    {
        $now = date('Y-m-d H:i:s');

        $this->db->table('users')->insertBatch([
            [
                'first_name'        => 'Test',
                'last_name'         => 'Couple',
                'email'             => 'couple@example.com',
                'password'          => password_hash('secret123', PASSWORD_DEFAULT),
                'wedding_role'      => 'bride',
                'role'              => 'user',
                'is_active'         => 1,
                'is_email_verified' => 1,
                'created_at'        => $now,
                'updated_at'        => $now,
            ],
            [
                'first_name'        => 'Admin',
                'last_name'         => 'User',
                'email'             => 'admin@example.com',
                'password'          => password_hash('admin123', PASSWORD_DEFAULT),
                'wedding_role'      => null,
                'role'              => 'admin',
                'is_active'         => 1,
                'is_email_verified' => 1,
                'created_at'        => $now,
                'updated_at'        => $now,
            ],
        ]);

        $this->db->table('campuses')->insert([
            'name'             => 'Watoto Central',
            'location'         => 'Kampala',
            'address'          => 'Kampala Road',
            'capacity'         => 500,
            'cost'             => 600000,
            'cost_per_wedding' => 600000,
            'is_active'        => 1,
            'created_at'       => $now,
            'updated_at'       => $now,
        ]);

        $this->db->table('settings')->insertBatch([
            [
                'setting_key'   => 'advance_booking_days',
                'setting_value' => '0',
                'setting_type'  => 'number',
                'description'   => 'Minimum lead time for tests',
                'category'      => 'wedding',
                'is_active'     => 1,
                'created_at'    => $now,
                'updated_at'    => $now,
            ],
            [
                'setting_key'   => 'wedding_days_allowed',
                'setting_value' => 'friday,saturday',
                'setting_type'  => 'string',
                'description'   => 'Allowed wedding weekdays for tests',
                'category'      => 'wedding',
                'is_active'     => 1,
                'created_at'    => $now,
                'updated_at'    => $now,
            ],
            [
                'setting_key'   => 'wedding_time_slots',
                'setting_value' => '["09:00","11:00","13:00"]',
                'setting_type'  => 'json',
                'description'   => 'Allowed ceremony start times for tests',
                'category'      => 'wedding',
                'is_active'     => 1,
                'created_at'    => $now,
                'updated_at'    => $now,
            ],
        ]);
    }
}
