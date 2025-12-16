<?php
require_once __DIR__ . '/../app/bootstrap.php';

echo "Running uppercase migration for usuarios.email...\n";
try {
    $stmt = $conexao->prepare("UPDATE usuarios SET email = UPPER(email)");
    $stmt->execute();
    $count = $stmt->rowCount();
    echo "Updated $count rows.\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

echo "Done.\n";
