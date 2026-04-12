<?php

declare(strict_types=1);

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Parent alive/deceased, separate father/mother contact phones, witness fields as marital status.
 */
class FamilyParentsAndWitnessMaritalStatus extends Migration
{
    public function up(): void
    {
        if (! $this->db->tableExists('bookings')) {
            return;
        }

        if (! $this->db->fieldExists('bride_father_status', 'bookings')) {
            $this->db->query('ALTER TABLE `bookings` ADD COLUMN `bride_father_status` VARCHAR(20) NULL DEFAULT NULL AFTER `bride_father_occupation`');
        }
        if (! $this->db->fieldExists('bride_mother_status', 'bookings')) {
            $this->db->query('ALTER TABLE `bookings` ADD COLUMN `bride_mother_status` VARCHAR(20) NULL DEFAULT NULL AFTER `bride_mother_occupation`');
        }
        if (! $this->db->fieldExists('groom_father_status', 'bookings')) {
            $this->db->query('ALTER TABLE `bookings` ADD COLUMN `groom_father_status` VARCHAR(20) NULL DEFAULT NULL AFTER `groom_father_occupation`');
        }
        if (! $this->db->fieldExists('groom_mother_status', 'bookings')) {
            $this->db->query('ALTER TABLE `bookings` ADD COLUMN `groom_mother_status` VARCHAR(20) NULL DEFAULT NULL AFTER `groom_mother_occupation`');
        }
        if (! $this->db->fieldExists('bride_father_phone', 'bookings')) {
            $this->db->query('ALTER TABLE `bookings` ADD COLUMN `bride_father_phone` VARCHAR(30) NULL DEFAULT NULL AFTER `bride_family_phone`');
        }
        if (! $this->db->fieldExists('bride_mother_phone', 'bookings')) {
            $this->db->query('ALTER TABLE `bookings` ADD COLUMN `bride_mother_phone` VARCHAR(30) NULL DEFAULT NULL AFTER `bride_father_phone`');
        }
        if (! $this->db->fieldExists('groom_father_phone', 'bookings')) {
            $this->db->query('ALTER TABLE `bookings` ADD COLUMN `groom_father_phone` VARCHAR(30) NULL DEFAULT NULL AFTER `groom_family_phone`');
        }
        if (! $this->db->fieldExists('groom_mother_phone', 'bookings')) {
            $this->db->query('ALTER TABLE `bookings` ADD COLUMN `groom_mother_phone` VARCHAR(30) NULL DEFAULT NULL AFTER `groom_father_phone`');
        }

        // Copy legacy single family line into both parent phones when new fields are empty
        $this->db->query(
            'UPDATE `bookings` SET `bride_father_phone` = `bride_family_phone`, `bride_mother_phone` = `bride_family_phone` '
            . 'WHERE `bride_family_phone` IS NOT NULL AND TRIM(`bride_family_phone`) <> \'\' '
            . 'AND (`bride_father_phone` IS NULL OR TRIM(`bride_father_phone`) = \'\') '
            . 'AND (`bride_mother_phone` IS NULL OR TRIM(`bride_mother_phone`) = \'\')'
        );
        $this->db->query(
            'UPDATE `bookings` SET `groom_father_phone` = `groom_family_phone`, `groom_mother_phone` = `groom_family_phone` '
            . 'WHERE `groom_family_phone` IS NOT NULL AND TRIM(`groom_family_phone`) <> \'\' '
            . 'AND (`groom_father_phone` IS NULL OR TRIM(`groom_father_phone`) = \'\') '
            . 'AND (`groom_mother_phone` IS NULL OR TRIM(`groom_mother_phone`) = \'\')'
        );

        if ($this->db->fieldExists('witness1_relationship', 'bookings')
            && ! $this->db->fieldExists('witness1_marital_status', 'bookings')) {
            $this->db->query(
                'ALTER TABLE `bookings` CHANGE `witness1_relationship` `witness1_marital_status` VARCHAR(50) NULL DEFAULT NULL'
            );
        }
        if ($this->db->fieldExists('witness2_relationship', 'bookings')
            && ! $this->db->fieldExists('witness2_marital_status', 'bookings')) {
            $this->db->query(
                'ALTER TABLE `bookings` CHANGE `witness2_relationship` `witness2_marital_status` VARCHAR(50) NULL DEFAULT NULL'
            );
        }
    }

    public function down(): void
    {
        // Irreversible without data loss; restore from backup if needed.
    }
}
