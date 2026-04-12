<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateBlockedDatesTable extends Migration
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
            'campus_id' => [
                'type' => 'INT',
                'constraint' => 5,
                'unsigned' => true,
                'null' => true,
            ],
            'date' => [
                'type' => 'DATE',
            ],
            'reason' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
            ],
            'is_recurring' => [
                'type' => 'BOOLEAN',
                'default' => false,
            ],
            'recurrence_pattern' => [
                'type' => 'VARCHAR',
                'constraint' => '50',
                'null' => true,
                'comment' => 'weekly, monthly, yearly, etc.',
            ],
            'is_active' => [
                'type' => 'BOOLEAN',
                'default' => true,
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
        $this->forge->addForeignKey('campus_id', 'campuses', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('blocked_dates');
    }

    public function down()
    {
        $this->forge->dropTable('blocked_dates');
    }
}
