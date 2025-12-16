<?php
// Do not require full app bootstrap (avoid headers/session in CLI).
$target = $argv[1] ?? 'weverto';
$q = strtoupper(trim($target));

echo "Checking user: $target (search key: $q)\n";

// DB connection settings (defaults mirror config/database.php)
$host = getenv('DB_HOST') ?: 'anvy.com.br';
$db   = getenv('DB_NAME') ?: 'anvycomb_checkplanilha';
$user = getenv('DB_USER') ?: 'anvycomb_checkplanilha';
$pass = getenv('DB_PASS') ?: 'uGyzaCndm7EDahptkBZd';
$charset = 'utf8mb4';

try {
    $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES $charset COLLATE utf8mb4_unicode_ci",
    ]);

    $stmt = $pdo->prepare('SELECT id, nome, email, senha, ativo FROM usuarios WHERE UPPER(email) = ? OR UPPER(nome) = ? LIMIT 1');
    $stmt->execute([$q, $q]);
    $user = $stmt->fetch();
    if (!$user) {
        echo "User not found by exact email or exact name. Trying partial match...\n";
        $like = "%$q%";
        $stmt2 = $pdo->prepare('SELECT id, nome, email, senha, ativo FROM usuarios WHERE UPPER(email) LIKE ? OR UPPER(nome) LIKE ? LIMIT 10');
        $stmt2->execute([$like, $like]);
        $rows = $stmt2->fetchAll();
        if (!$rows) {
            echo "No partial matches found for '$target'.\n";
            exit(1);
        }

        echo "Partial matches (up to 10):\n";
        foreach ($rows as $r) {
            echo "  id={$r['id']} nome={$r['nome']} email={$r['email']} ativo={$r['ativo']} senha_len=" . strlen($r['senha']) . "\n";
        }
        exit(0);
    }

    echo "Found user:\n";
    echo "  id: " . $user['id'] . "\n";
    echo "  nome: " . $user['nome'] . "\n";
    echo "  email (raw): " . $user['email'] . "\n";
    echo "  ativo: " . $user['ativo'] . "\n";

    $senha = $user['senha'] ?? '';
    $len = strlen($senha);
    $prefix = substr($senha, 0, 6);
    echo "  senha length: $len\n";
    echo "  senha prefix (masked): " . str_repeat('*', max(0, $len - 6)) . $prefix . "\n";

    // Guess hash algorithm
    if (preg_match('/^\$2[aby]\$[0-9]{2}\$/', $senha)) {
        echo "  guessed algorithm: bcrypt (password_hash compatible)\n";
    } elseif (preg_match('/^\$argon2/', $senha)) {
        echo "  guessed algorithm: argon2 (password_hash compatible)\n";
    } elseif (preg_match('/^[0-9a-f]{64}$/i', $senha)) {
        echo "  guessed algorithm: SHA-256 hex (NOT password_hash compatible)\n";
    } else {
        echo "  guessed algorithm: unknown format\n";
    }

    // Check whether email is uppercase in DB
    if ($user['email'] === strtoupper($user['email'])) {
        echo "  email already uppercase in DB.\n";
    } else {
        echo "  email not uppercase in DB (consider migrating to uppercase).\n";
    }

    // Additional check: activo flag
    if ((int)$user['ativo'] !== 1) {
        echo "  WARNING: usuario not active (ativo != 1). This will block login.\n";
    }

    echo "\nAnalysis/next steps:\n";
    echo " - If 'guessed algorithm' is SHA-256, current code using password_verify() will not match the old hash. You must migrate hashes (re-hash on next login or force password reset).\n";
    echo " - If user not active, activate with: UPDATE usuarios SET ativo = 1 WHERE id = {$user['id']};\n";
    echo " - To normalize emails now, run scripts/uppercase_emails.php or run SQL: UPDATE usuarios SET email = UPPER(email);\n";

} catch (Exception $e) {
    echo "Error connecting or querying DB: " . $e->getMessage() . "\n";
    exit(1);
}
