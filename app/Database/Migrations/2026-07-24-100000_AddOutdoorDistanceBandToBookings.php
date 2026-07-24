<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddOutdoorDistanceBandToBookings extends Migration
{
    public function up()
    {
        $fields = $this->db->getFieldNames('bookings');
        if (in_array('outdoor_distance_band', $fields, true)) {
            return;
        }

        $this->forge->addColumn('bookings', [
            'outdoor_distance_band' => [
                'type'       => 'ENUM',
                'constraint' => ['within_20km', '20_50km'],
                'null'       => true,
                'after'      => 'outdoor_venue_address',
            ],
        ]);
    }

    public function down()
    {
        $fields = $this->db->getFieldNames('bookings');
        if (in_array('outdoor_distance_band', $fields, true)) {
            $this->forge->dropColumn('bookings', 'outdoor_distance_band');
        }
    }
}
