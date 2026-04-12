<?php

declare(strict_types=1);

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

/**
 * Truncates transactional tables and removes all users except the system admin
 * (role admin + email admin@watoto.com), matching files/truncate_transactional_data.sql.
 */
class TruncateTransactionalData extends BaseCommand
{
    protected $group       = 'Database';
    protected $name        = 'db:truncate-transactional';
    protected $description = 'Truncate app data; keep campuses, settings, and system admin user';
    protected $usage       = 'db:truncate-transactional [options]';
    protected $arguments   = [];
    protected $options     = [
        '--force' => 'Run without confirmation',
    ];

    public function run(array $params): void
    {
        if (! CLI::getOption('force')) {
            CLI::write('This will DELETE all users except admin@watoto.com and TRUNCATE transactional tables.', 'yellow');
            CLI::write('Campuses and settings are kept. Type yes to continue:', 'yellow');
            $answer = CLI::prompt('', ['yes', 'no']);
            if ($answer !== 'yes') {
                CLI::write('Aborted.', 'green');

                return;
            }
        }

        $db = db_connect();

        $tables = [
            'application_documents',
            'application_logs',
            'application_drafts',
            'messages',
            'notifications',
            'payments',
            'bookings',
            'blocked_dates',
            'email_verifications',
            'pastors',
            'venue_time_slots',
            'database_versions',
        ];

        $db->query('SET FOREIGN_KEY_CHECKS = 0');
        foreach ($tables as $table) {
            if ($db->tableExists($table)) {
                $db->query('TRUNCATE TABLE `' . $table . '`');
                CLI::write('Truncated: ' . $table, 'dark_gray');
            }
        }
        $db->query('SET FOREIGN_KEY_CHECKS = 1');

        $db->query("DELETE FROM `users` WHERE NOT (`role` = 'admin' AND `email` = 'admin@watoto.com')");
        $db->query('ALTER TABLE `users` AUTO_INCREMENT = 2');

        CLI::write('Done. Campuses, settings, and system admin user preserved.', 'green');
    }
}
