<?php

declare(strict_types=1);

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddWeddingRoleToUsers extends Migration
{
    public function up(): void
    {
        if (! $this->db->tableExists('users') || $this->db->fieldExists('wedding_role', 'users')) {
            return;
        }

        $this->forge->addColumn('users', [
            'wedding_role' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'null'       => true,
                'after'      => 'password',
            ],
        ]);
    }

    public function down(): void
    {
        if (! $this->db->tableExists('users') || ! $this->db->fieldExists('wedding_role', 'users')) {
            return;
        }

        $this->forge->dropColumn('users', 'wedding_role');
    }
}
