#!/usr/bin/env php
<?php
/**
 * Script to fix planilha_visualizar.php by removing corrupted lines 297-461
 */

$file = dirname(__DIR__) . '/app/views/planilhas/planilha_visualizar.php';
$backup = $file . '.bak2';

echo "Reading file: $file\n";

if (!file_exists($file)) {
    die("ERROR: File not found!\n");
}

// Create backup
copy($file, $backup);
echo "Backup created: $backup\n";

// Read all lines
$lines = file($file, FILE_IGNORE_NEW_LINES);
$total = count($lines);
echo "Total lines: $total\n";

// Keep lines 1-296 and 462+
$output = [];

// Lines 1-296 (index 0-295)
for ($i = 0; $i < 296; $i++) {
    $output[] = $lines[$i];
}

echo "Kept first 296 lines\n";

// Lines 462+ (index 461+)
for ($i = 461; $i < $total; $i++) {
    $output[] = $lines[$i];
}

echo "Kept lines from 462 onwards\n";

// Write back
$content = implode("\n", $output);
file_put_contents($file, $content);

$newTotal = count($output);
echo "New total lines: $newTotal\n";
echo "Removed " . ($total - $newTotal) . " lines (297-461)\n";
echo "DONE!\n";
