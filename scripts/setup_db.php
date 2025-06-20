<?php
// Simple migration runner
require_once __DIR__ . '/../core/Config.php';
require_once __DIR__ . '/../core/Database.php';

use Core\Config;
use Core\Database;

$pdo = Database::getConnection();
$dir = __DIR__ . '/../database/migrations';
$files = glob($dir . '/*.sql');
if (!$files) {
    echo "No migrations found in $dir\n";
    exit;
}

// Sort by filename to ensure order (001_, 002_, ...)
usort($files, fn($a, $b) => strcmp($a, $b));

foreach ($files as $file) {
    echo "Running " . basename($file) . " ... ";
    $sql = file_get_contents($file);
    try {
        $pdo->exec($sql);
        echo "✔\n";
    } catch (PDOException $e) {
        echo "Failed: " . $e->getMessage() . "\n";
        exit(1);
    }
}

echo "\nAll migrations executed.✔\n";
