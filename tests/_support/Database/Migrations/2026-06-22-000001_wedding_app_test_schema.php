<?php

namespace Tests\Support\Database\Migrations;

use CodeIgniter\Database\Migration;

class WeddingAppTestSchema extends Migration
{
    protected $DBGroup = 'tests';

    public function up(): void
    {
        $this->createUsersTable();
        $this->createCampusesTable();
        $this->createBookingsTable();
        $this->createPaymentsTable();
        $this->createSettingsTable();
        $this->createBlockedDatesTable();
        $this->createEmailVerificationsTable();
    }

    public function down(): void
    {
        $this->forge->dropTable('email_verifications', true);
        $this->forge->dropTable('blocked_dates', true);
        $this->forge->dropTable('payments', true);
        $this->forge->dropTable('bookings', true);
        $this->forge->dropTable('settings', true);
        $this->forge->dropTable('campuses', true);
        $this->forge->dropTable('users', true);
    }

    private function createUsersTable(): void
    {
        $this->forge->addField('id');
        $this->forge->addField([
            'first_name'            => ['type' => 'varchar', 'constraint' => 100],
            'last_name'             => ['type' => 'varchar', 'constraint' => 100],
            'email'                 => ['type' => 'varchar', 'constraint' => 255],
            'phone'                 => ['type' => 'varchar', 'constraint' => 30, 'null' => true],
            'password'              => ['type' => 'varchar', 'constraint' => 255],
            'wedding_role'          => ['type' => 'varchar', 'constraint' => 20, 'null' => true],
            'role'                  => ['type' => 'varchar', 'constraint' => 20, 'default' => 'user'],
            'is_active'             => ['type' => 'integer', 'constraint' => 1, 'default' => 1],
            'is_email_verified'     => ['type' => 'integer', 'constraint' => 1, 'default' => 0],
            'email_verified_at'     => ['type' => 'datetime', 'null' => true],
            'email_notifications'   => ['type' => 'integer', 'constraint' => 1, 'default' => 1],
            'sms_notifications'     => ['type' => 'integer', 'constraint' => 1, 'default' => 0],
            'marketing_emails'      => ['type' => 'integer', 'constraint' => 1, 'default' => 0],
            'profile_visibility'    => ['type' => 'varchar', 'constraint' => 50, 'default' => 'private'],
            'password_changed_at'   => ['type' => 'datetime', 'null' => true],
            'created_at'            => ['type' => 'datetime', 'null' => true],
            'updated_at'            => ['type' => 'datetime', 'null' => true],
        ]);
        $this->forge->addUniqueKey('email');
        $this->forge->createTable('users');
    }

    private function createCampusesTable(): void
    {
        $this->forge->addField('id');
        $this->forge->addField([
            'name'             => ['type' => 'varchar', 'constraint' => 100],
            'location'         => ['type' => 'varchar', 'constraint' => 255],
            'address'          => ['type' => 'text', 'null' => true],
            'capacity'         => ['type' => 'integer', 'default' => 0],
            'cost'             => ['type' => 'decimal', 'constraint' => '10,2', 'default' => 0],
            'cost_per_wedding' => ['type' => 'decimal', 'constraint' => '10,2', 'default' => 0],
            'description'      => ['type' => 'text', 'null' => true],
            'facilities'       => ['type' => 'text', 'null' => true],
            'image_path'       => ['type' => 'varchar', 'constraint' => 255, 'null' => true],
            'is_active'        => ['type' => 'integer', 'constraint' => 1, 'default' => 1],
            'created_at'       => ['type' => 'datetime', 'null' => true],
            'updated_at'       => ['type' => 'datetime', 'null' => true],
        ]);
        $this->forge->createTable('campuses');
    }

    private function createBookingsTable(): void
    {
        $this->forge->addField('id');
        $this->forge->addField([
            'user_id'          => ['type' => 'integer'],
            'campus_id'        => ['type' => 'integer'],
            'pastor_id'        => ['type' => 'integer', 'null' => true],
            'wedding_date'     => ['type' => 'date', 'null' => true],
            'wedding_time'     => ['type' => 'time', 'null' => true],
            'bride_name'       => ['type' => 'varchar', 'constraint' => 150, 'null' => true],
            'groom_name'       => ['type' => 'varchar', 'constraint' => 150, 'null' => true],
            'guest_count'      => ['type' => 'integer', 'default' => 0],
            'accept_terms'     => ['type' => 'integer', 'constraint' => 1, 'default' => 0],
            'application_step' => ['type' => 'integer', 'default' => 1],
            'is_draft'         => ['type' => 'integer', 'constraint' => 1, 'default' => 1],
            'status'           => ['type' => 'varchar', 'constraint' => 20, 'default' => 'draft'],
            'total_cost'       => ['type' => 'decimal', 'constraint' => '10,2', 'default' => 0],
            'payment_status'   => ['type' => 'varchar', 'constraint' => 20, 'default' => 'pending'],
            'admin_notes'      => ['type' => 'text', 'null' => true],
            'created_at'       => ['type' => 'datetime', 'null' => true],
            'updated_at'       => ['type' => 'datetime', 'null' => true],
        ]);
        $this->forge->addKey('user_id');
        $this->forge->addKey('campus_id');
        $this->forge->addKey('wedding_date');
        $this->forge->createTable('bookings');
    }

    private function createPaymentsTable(): void
    {
        $this->forge->addField('id');
        $this->forge->addField([
            'booking_id'             => ['type' => 'integer'],
            'amount'                 => ['type' => 'decimal', 'constraint' => '10,2', 'default' => 0],
            'payment_method'         => ['type' => 'varchar', 'constraint' => 30, 'default' => 'cash'],
            'transaction_reference'  => ['type' => 'varchar', 'constraint' => 255, 'null' => true],
            'status'                 => ['type' => 'varchar', 'constraint' => 20, 'default' => 'pending'],
            'payment_date'           => ['type' => 'datetime', 'null' => true],
            'notes'                  => ['type' => 'text', 'null' => true],
            'created_at'             => ['type' => 'datetime', 'null' => true],
            'updated_at'             => ['type' => 'datetime', 'null' => true],
        ]);
        $this->forge->addKey('booking_id');
        $this->forge->createTable('payments');
    }

    private function createSettingsTable(): void
    {
        $this->forge->addField('id');
        $this->forge->addField([
            'setting_key'   => ['type' => 'varchar', 'constraint' => 100],
            'setting_value' => ['type' => 'text'],
            'setting_type'  => ['type' => 'varchar', 'constraint' => 20, 'default' => 'string'],
            'description'   => ['type' => 'text', 'null' => true],
            'category'      => ['type' => 'varchar', 'constraint' => 50, 'default' => 'general'],
            'is_active'     => ['type' => 'integer', 'constraint' => 1, 'default' => 1],
            'created_at'    => ['type' => 'datetime', 'null' => true],
            'updated_at'    => ['type' => 'datetime', 'null' => true],
        ]);
        $this->forge->addUniqueKey('setting_key');
        $this->forge->createTable('settings');
    }

    private function createBlockedDatesTable(): void
    {
        $this->forge->addField('id');
        $this->forge->addField([
            'campus_id'    => ['type' => 'integer', 'null' => true],
            'blocked_date' => ['type' => 'date', 'null' => true],
            'reason'       => ['type' => 'varchar', 'constraint' => 500],
            'created_at'   => ['type' => 'datetime', 'null' => true],
            'updated_at'   => ['type' => 'datetime', 'null' => true],
        ]);
        $this->forge->addKey('campus_id');
        $this->forge->addKey('blocked_date');
        $this->forge->createTable('blocked_dates');
    }

    private function createEmailVerificationsTable(): void
    {
        $this->forge->addField('id');
        $this->forge->addField([
            'email'      => ['type' => 'varchar', 'constraint' => 255],
            'otp_code'   => ['type' => 'varchar', 'constraint' => 6],
            'expires_at' => ['type' => 'datetime'],
            'is_used'    => ['type' => 'integer', 'constraint' => 1, 'default' => 0],
            'created_at' => ['type' => 'datetime', 'null' => true],
            'updated_at' => ['type' => 'datetime', 'null' => true],
        ]);
        $this->forge->addKey('email');
        $this->forge->addKey('otp_code');
        $this->forge->createTable('email_verifications');
    }
}
