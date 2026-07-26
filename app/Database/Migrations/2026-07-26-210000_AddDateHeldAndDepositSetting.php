<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddDateHeldAndDepositSetting extends Migration
{
    public function up()
    {
        $fields = $this->db->getFieldNames('bookings');

        if (! in_array('date_held', $fields, true)) {
            $this->forge->addColumn('bookings', [
                'date_held' => [
                    'type'       => 'TINYINT',
                    'constraint' => 1,
                    'default'    => 0,
                    'null'       => false,
                    'after'      => 'payment_status',
                ],
            ]);
        }

        if (! in_array('date_held_at', $fields, true)) {
            $this->forge->addColumn('bookings', [
                'date_held_at' => [
                    'type' => 'DATETIME',
                    'null' => true,
                    'after' => 'date_held',
                ],
            ]);
        }

        $existing = $this->db->table('settings')
            ->where('setting_key', 'deposit_amount')
            ->countAllResults();

        if ($existing === 0) {
            $now = date('Y-m-d H:i:s');
            $this->db->table('settings')->insert([
                'setting_key'   => 'deposit_amount',
                'setting_value' => '300000',
                'setting_type'  => 'number',
                'description'   => 'Non-refundable deposit required before a preferred wedding date is held',
                'category'      => 'wedding_fees',
                'is_active'     => 1,
                'created_at'    => $now,
                'updated_at'    => $now,
            ]);
        }
    }

    public function down()
    {
        $fields = $this->db->getFieldNames('bookings');
        $toDrop = array_intersect(['date_held', 'date_held_at'], $fields);
        if ($toDrop !== []) {
            $this->forge->dropColumn('bookings', $toDrop);
        }

        $this->db->table('settings')->where('setting_key', 'deposit_amount')->delete();
    }
}
