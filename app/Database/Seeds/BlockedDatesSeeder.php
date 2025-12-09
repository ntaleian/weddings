<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class BlockedDatesSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'campus_id' => null, // All campuses
                'date' => '2024-12-25',
                'reason' => 'Christmas Day',
                'is_recurring' => true,
                'recurrence_pattern' => 'yearly',
                'is_active' => true,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'campus_id' => null, // All campuses
                'date' => '2024-12-31',
                'reason' => 'New Year\'s Eve',
                'is_recurring' => true,
                'recurrence_pattern' => 'yearly',
                'is_active' => true,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'campus_id' => null, // All campuses
                'date' => '2025-01-01',
                'reason' => 'New Year\'s Day',
                'is_recurring' => true,
                'recurrence_pattern' => 'yearly',
                'is_active' => true,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'campus_id' => 1, // Downtown Campus only
                'date' => '2025-02-15',
                'reason' => 'Campus Maintenance',
                'is_recurring' => false,
                'recurrence_pattern' => null,
                'is_active' => true,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'campus_id' => null, // All campuses
                'date' => '2025-04-18',
                'reason' => 'Good Friday',
                'is_recurring' => false,
                'recurrence_pattern' => null,
                'is_active' => true,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('blocked_dates')->insertBatch($data);
    }
}
