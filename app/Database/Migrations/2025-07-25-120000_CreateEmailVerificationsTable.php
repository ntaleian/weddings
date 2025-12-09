<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateEmailVerificationsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'email' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'otp_code' => [
                'type' => 'VARCHAR',
                'constraint' => 6,
            ],
            'expires_at' => [
                'type' => 'DATETIME',
            ],
            'is_used' => [
                'type' => 'BOOLEAN',
                'default' => false,
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

        $this->forge->addPrimaryKey('id');
        $this->forge->addKey('email');
        $this->forge->addKey('otp_code');
        $this->forge->addKey('expires_at');
        
        $this->forge->createTable('email_verifications');
    }

    public function down()
    {
        $this->forge->dropTable('email_verifications');
    }
}
