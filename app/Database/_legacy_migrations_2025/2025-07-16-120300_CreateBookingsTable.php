<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateBookingsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 5,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'user_id' => [
                'type' => 'INT',
                'constraint' => 5,
                'unsigned' => true,
            ],
            'campus_id' => [
                'type' => 'INT',
                'constraint' => 5,
                'unsigned' => true,
            ],
            'pastor_id' => [
                'type' => 'INT',
                'constraint' => 5,
                'unsigned' => true,
                'null' => true,
            ],
            'groom_name' => [
                'type' => 'VARCHAR',
                'constraint' => '100',
            ],
            'bride_name' => [
                'type' => 'VARCHAR',
                'constraint' => '100',
            ],
            'wedding_date' => [
                'type' => 'DATE',
            ],
            'wedding_time' => [
                'type' => 'TIME',
            ],
            'guest_count' => [
                'type' => 'INT',
                'constraint' => 5,
                'unsigned' => true,
            ],
            'special_requirements' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['pending', 'approved', 'rejected', 'cancelled', 'completed'],
                'default' => 'pending',
            ],
            'total_cost' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'default' => 0.00,
            ],
            'payment_status' => [
                'type' => 'ENUM',
                'constraint' => ['pending', 'partial', 'completed', 'refunded'],
                'default' => 'pending',
            ],
            'admin_notes' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('campus_id', 'campuses', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('pastor_id', 'pastors', 'id', 'SET NULL', 'CASCADE');
        $this->forge->createTable('bookings');
    }

    public function down()
    {
        $this->forge->dropTable('bookings');
    }
}
