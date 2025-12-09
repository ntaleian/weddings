<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddEmailVerificationToUsers extends Migration
{
    public function up()
    {
        $this->forge->addColumn('users', [
            'email_verified_at' => [
                'type' => 'DATETIME',
                'null' => true,
                'after' => 'email'
            ],
            'is_email_verified' => [
                'type' => 'BOOLEAN',
                'default' => false,
                'after' => 'email_verified_at'
            ]
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('users', ['email_verified_at', 'is_email_verified']);
    }
}
