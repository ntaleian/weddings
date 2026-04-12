<?php

declare(strict_types=1);

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use mysqli;

/**
 * Applies app/Database/Schema/baseline_structure.sql when the database has no `users` table yet.
 * If you maintain schema from files/watotochurch_weddings.sql (or an import), this migration is a no-op
 * but is still recorded so new incremental migrations can run after it.
 *
 * When the canonical dump changes: regenerate Schema/baseline_structure.sql from the dump
 * (see comment block in files/truncate_transactional_data.sql) or edit the SQL file by hand.
 */
class BaselineWeddingSchema extends Migration
{
    public function up(): void
    {
        if ($this->db->tableExists('users')) {
            return;
        }

        $path = APPPATH . 'Database/Schema/baseline_structure.sql';
        if (! is_file($path)) {
            throw new \RuntimeException('Missing baseline SQL: ' . $path);
        }

        $sql = file_get_contents($path);
        if ($sql === false || $sql === '') {
            throw new \RuntimeException('Could not read baseline SQL: ' . $path);
        }

        $conn = $this->db->connID;
        if (! $conn instanceof mysqli) {
            throw new \RuntimeException('Baseline migration requires MySQLi (current: ' . get_debug_type($conn) . ').');
        }

        $conn->multi_query($sql);
        while ($conn->more_results()) {
            $conn->next_result();
            if ($res = $conn->store_result()) {
                $res->free();
            }
        }
        if ($conn->errno) {
            throw new \RuntimeException('Baseline SQL failed: ' . $conn->error);
        }
    }

    public function down(): void
    {
        // Irreversible: dropping the full schema would destroy data. Use a DB restore instead.
    }
}
