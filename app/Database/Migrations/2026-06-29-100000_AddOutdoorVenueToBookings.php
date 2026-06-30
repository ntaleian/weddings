<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddOutdoorVenueToBookings extends Migration
{
    public function up()
    {
        $db = \Config\Database::connect();

        // Make campus_id nullable (INT UNSIGNED to match campuses.id).
        // Re-create FK with ON DELETE SET NULL so NULL is valid for outdoor bookings.
        $fkExists = $db->query(
            "SELECT 1 FROM information_schema.TABLE_CONSTRAINTS
             WHERE TABLE_SCHEMA = DATABASE()
               AND TABLE_NAME = 'bookings'
               AND CONSTRAINT_NAME = 'bookings_campus_id_fk'
               AND CONSTRAINT_TYPE = 'FOREIGN KEY'"
        )->getRow();

        if ($fkExists) {
            $db->query('ALTER TABLE `bookings` DROP FOREIGN KEY `bookings_campus_id_fk`');
        }

        $db->query('ALTER TABLE `bookings` MODIFY `campus_id` INT UNSIGNED NULL DEFAULT NULL');
        $db->query('ALTER TABLE `bookings` ADD CONSTRAINT `bookings_campus_id_fk` FOREIGN KEY (`campus_id`) REFERENCES `campuses`(`id`) ON DELETE SET NULL ON UPDATE CASCADE');

        // Add new columns only if not already present
        $existingFields = $db->getFieldNames('bookings');

        if (!in_array('venue_type', $existingFields)) {
            $this->forge->addColumn('bookings', [
                'venue_type' => [
                    'type'       => 'ENUM',
                    'constraint' => ['campus', 'outdoor'],
                    'default'    => 'campus',
                    'null'       => false,
                    'after'      => 'campus_id',
                ],
            ]);
        }

        if (!in_array('outdoor_venue_name', $existingFields)) {
            $this->forge->addColumn('bookings', [
                'outdoor_venue_name' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 255,
                    'null'       => true,
                    'default'    => null,
                    'after'      => 'venue_type',
                ],
            ]);
        }

        if (!in_array('outdoor_venue_address', $existingFields)) {
            $this->forge->addColumn('bookings', [
                'outdoor_venue_address' => [
                    'type' => 'TEXT',
                    'null' => true,
                    'after' => 'outdoor_venue_name',
                ],
            ]);
        }
    }

    public function down()
    {
        $db = \Config\Database::connect();

        $fkExists = $db->query(
            "SELECT 1 FROM information_schema.TABLE_CONSTRAINTS
             WHERE TABLE_SCHEMA = DATABASE()
               AND TABLE_NAME = 'bookings'
               AND CONSTRAINT_NAME = 'bookings_campus_id_fk'
               AND CONSTRAINT_TYPE = 'FOREIGN KEY'"
        )->getRow();

        if ($fkExists) {
            $db->query('ALTER TABLE `bookings` DROP FOREIGN KEY `bookings_campus_id_fk`');
        }

        $existing = $db->getFieldNames('bookings');
        $toDrop = array_intersect(['venue_type', 'outdoor_venue_name', 'outdoor_venue_address'], $existing);
        if ($toDrop) {
            $this->forge->dropColumn('bookings', array_values($toDrop));
        }

        $db->query('ALTER TABLE `bookings` MODIFY `campus_id` INT UNSIGNED NOT NULL');
        $db->query('ALTER TABLE `bookings` ADD CONSTRAINT `bookings_campus_id_fk` FOREIGN KEY (`campus_id`) REFERENCES `campuses`(`id`) ON DELETE CASCADE ON UPDATE CASCADE');
    }
}
