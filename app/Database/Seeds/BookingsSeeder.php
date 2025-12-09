<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class BookingsSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'user_id' => 2, // John Doe
                'campus_id' => 1, // Downtown Campus
                'pastor_id' => 1, // Pastor Julius
                'groom_name' => 'John Doe',
                'bride_name' => 'Sarah Johnson',
                'wedding_date' => '2024-12-15',
                'wedding_time' => '10:00:00',
                'guest_count' => 150,
                'special_requirements' => 'Need assistance with decorations and photography setup.',
                'status' => 'pending',
                'total_cost' => 2500000.00,
                'payment_status' => 'pending',
                'admin_notes' => null,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'user_id' => 4, // Grace Nakamura
                'campus_id' => 2, // Ntinda Campus
                'pastor_id' => 2, // Pastor Sarah
                'groom_name' => 'David Mukasa',
                'bride_name' => 'Grace Nakamura',
                'wedding_date' => '2024-12-22',
                'wedding_time' => '14:00:00',
                'guest_count' => 200,
                'special_requirements' => 'Outdoor ceremony if weather permits.',
                'status' => 'approved',
                'total_cost' => 1800000.00,
                'payment_status' => 'partial',
                'admin_notes' => 'Approved. Couple has made initial payment.',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'user_id' => 3, // Sarah Johnson (another user)
                'campus_id' => 3, // Bweyogerere Campus
                'pastor_id' => 3, // Pastor David
                'groom_name' => 'Peter Ssali',
                'bride_name' => 'Mary Nakato',
                'wedding_date' => '2025-01-05',
                'wedding_time' => '11:00:00',
                'guest_count' => 300,
                'special_requirements' => 'Traditional ceremony followed by modern reception.',
                'status' => 'pending',
                'total_cost' => 2000000.00,
                'payment_status' => 'pending',
                'admin_notes' => null,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('bookings')->insertBatch($data);
    }
}
