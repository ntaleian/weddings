<?php

declare(strict_types=1);

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddWitnessOccupationsToBookings extends Migration
{
    public function up(): void
    {
        if (! $this->db->tableExists('bookings')) {
            return;
        }

        if (! $this->db->fieldExists('witness1_occupation', 'bookings')) {
            $this->db->query(
                'ALTER TABLE `bookings` ADD COLUMN `witness1_occupation` VARCHAR(150) NULL DEFAULT NULL AFTER `witness1_phone`'
            );
        }

        if (! $this->db->fieldExists('witness2_occupation', 'bookings')) {
            $this->db->query(
                'ALTER TABLE `bookings` ADD COLUMN `witness2_occupation` VARCHAR(150) NULL DEFAULT NULL AFTER `witness2_phone`'
            );
        }
    }

    public function down(): void
    {
        if (! $this->db->tableExists('bookings')) {
            return;
        }

        if ($this->db->fieldExists('witness1_occupation', 'bookings')) {
            $this->forge->dropColumn('bookings', 'witness1_occupation');
        }

        if ($this->db->fieldExists('witness2_occupation', 'bookings')) {
            $this->forge->dropColumn('bookings', 'witness2_occupation');
        }
    }
}
