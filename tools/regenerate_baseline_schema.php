<?php

/**
 * Regenerate app/Database/Schema/baseline_structure.sql from files/watotochurch_weddings.sql
 * Run from project root: php tools/regenerate_baseline_schema.php
 */

declare(strict_types=1);

$root = dirname(__DIR__);
$path = $root . '/files/watotochurch_weddings.sql';
$s    = file_get_contents($path);
if ($s === false) {
    fwrite(STDERR, "Cannot read: {$path}\n");
    exit(1);
}

$skip = ['complete_applications', 'pending_applications', 'migrations'];

$out   = [];
$out[] = 'SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";';
$out[] = 'SET NAMES utf8mb4;';
$out[] = 'SET FOREIGN_KEY_CHECKS = 0;';
$out[] = 'START TRANSACTION;';
$out[] = '';

if (preg_match_all('/CREATE TABLE `([^`]+)`\s*\((?:[^;]|\([^)]*\))*\)\s*ENGINE=[^;]+;/s', $s, $m, PREG_SET_ORDER)) {
    foreach ($m as $block) {
        if (in_array($block[1], $skip, true)) {
            continue;
        }
        $out[] = $block[0];
        $out[] = '';
    }
}

if (preg_match_all('/ALTER TABLE `([^`]+)`[^;]+;/s', $s, $m2, PREG_SET_ORDER)) {
    foreach ($m2 as $block) {
        if ($block[1] === 'migrations') {
            continue;
        }
        $out[] = $block[0];
        $out[] = '';
    }
}

if (preg_match_all('/ALTER TABLE `([^`]+)`\s*MODIFY[^;]+;/s', $s, $m3, PREG_SET_ORDER)) {
    foreach ($m3 as $block) {
        if ($block[1] === 'migrations') {
            continue;
        }
        $line = preg_replace('/,\s*AUTO_INCREMENT=\d+/', '', $block[0]);
        $out[] = $line;
        $out[] = '';
    }
}

$out[] = 'COMMIT;';
$out[] = 'SET FOREIGN_KEY_CHECKS = 1;';

$dir    = $root . '/app/Database/Schema';
$target = $dir . '/baseline_structure.sql';
if (! is_dir($dir)) {
    mkdir($dir, 0777, true);
}

$header = "-- Structure derived from files/watotochurch_weddings.sql (excludes migrations table, legacy MyISAM tables).\n";
file_put_contents($target, $header . implode("\n", $out));

echo "Wrote {$target}\n";
