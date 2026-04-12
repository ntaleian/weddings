<?php

declare(strict_types=1);

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdateWeddingDaysToFridaySaturday extends Migration
{
    public function up(): void
    {
        if (! $this->db->tableExists('settings')) {
            return;
        }

        $row = $this->db->table('settings')->where('setting_key', 'wedding_days_allowed')->get()->getRowArray();
        if ($row) {
            $this->db->table('settings')->where('setting_key', 'wedding_days_allowed')->update([
                'setting_value' => 'friday,saturday',
                'description'   => 'Days of week when weddings are allowed (comma-separated weekday names)',
            ]);
        } else {
            $this->db->table('settings')->insert([
                'setting_key'   => 'wedding_days_allowed',
                'setting_value' => 'friday,saturday',
                'setting_type'  => 'string',
                'description'   => 'Days of week when weddings are allowed (comma-separated weekday names)',
                'category'      => 'wedding',
                'is_active'     => 1,
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s'),
            ]);
        }

        $this->db->table('settings')->where('setting_key', 'wedding_time_slots')->update([
            'setting_value' => '["09:00","11:00","13:00"]',
            'setting_type'  => 'json',
            'description'   => 'Available wedding ceremony start times (9:00, 11:00, 13:00)',
        ]);
    }

    public function down(): void
    {
        if (! $this->db->tableExists('settings')) {
            return;
        }

        $this->db->table('settings')->where('setting_key', 'wedding_days_allowed')->update([
            'setting_value' => 'saturday',
            'description'   => 'Days of week when weddings are allowed',
        ]);
    }
}
