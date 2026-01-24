<?php
/**
 * Script de diagnóstico para identificar problemas no servidor
 * Acesse: https://checkplanilha.anvy.com.br/scripts/diagnose.php?debug=1
 */

// Proteção: exija ?debug=1
if (!isset($_GET['debug']) || $_GET['debug'] != '1') {
    http_response_code(403);
    echo "Adicione ?debug=1 na URL para ver diagnóstico.";
    exit;
}

error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<pre style='font-family: monospace; background: #f5f5f5; padding: 20px;'>\n";
echo "=== DIAGNÓSTICO DO SISTEMA ===\n\n";

// 1. Versão do PHP
echo "1. PHP Versão: " . PHP_VERSION . "\n";

// 2. Extensões carregadas
echo "\n2. Extensões necessárias:\n";
$required = ['pdo', 'pdo_mysql', 'mbstring', 'iconv', 'json'];
foreach ($required as $ext) {
    $status = extension_loaded($ext) ? '✓ OK' : '✗ FALTANDO';
    echo "   - $ext: $status\n";
}

// 3. Arquivos de bootstrap
echo "\n3. Arquivos de configuração:\n";
$files = [
    __DIR__ . '/../config/bootstrap.php',
    __DIR__ . '/../config/database.php',
    __DIR__ . '/../app/bootstrap.php',
    __DIR__ . '/../app/helpers/uppercase_helper.php',
    __DIR__ . '/../app/helpers/comum_helper.php',
    __DIR__ . '/../vendor/autoload.php',
];
foreach ($files as $f) {
    $status = file_exists($f) ? '✓ existe' : '✗ NÃO EXISTE';
    echo "   - " . basename($f) . ": $status\n";
}

// 4. Testar carregamento do bootstrap
echo "\n4. Testando carregamento do bootstrap:\n";
try {
    require_once __DIR__ . '/../config/bootstrap.php';
    echo "   - config/bootstrap.php: ✓ OK\n";
} catch (Throwable $e) {
    echo "   - config/bootstrap.php: ✗ ERRO: " . $e->getMessage() . "\n";
}

// 5. Testar conexão com banco
echo "\n5. Testando conexão com banco de dados:\n";
try {
    if (isset($conexao) && $conexao instanceof PDO) {
        $result = $conexao->query("SELECT 1")->fetchColumn();
        echo "   - Conexão: ✓ OK (SELECT 1 = $result)\n";
        
        // Testar tabela comums/comuns
        echo "\n6. Verificando tabela comums/comuns:\n";
        foreach (['comums', 'comuns'] as $table) {
            try {
                $count = $conexao->query("SELECT COUNT(*) FROM `$table`")->fetchColumn();
                echo "   - $table: ✓ existe ($count registros)\n";
            } catch (Exception $e) {
                echo "   - $table: ✗ não existe\n";
            }
        }
    } else {
        echo "   - Conexão: ✗ \$conexao não definida ou inválida\n";
    }
} catch (Throwable $e) {
    echo "   - Conexão: ✗ ERRO: " . $e->getMessage() . "\n";
}

// 7. Testar helper uppercase
echo "\n7. Testando uppercase_helper:\n";
try {
    if (function_exists('to_uppercase')) {
        $test = to_uppercase('ação');
        echo "   - to_uppercase('ação') = '$test' " . ($test === 'AÇÃO' ? '✓' : '✗') . "\n";
    } else {
        echo "   - to_uppercase: ✗ função não existe\n";
    }
} catch (Throwable $e) {
    echo "   - uppercase_helper: ✗ ERRO: " . $e->getMessage() . "\n";
}

// 8. Testar sessão
echo "\n8. Testando sessão:\n";
try {
    if (session_status() === PHP_SESSION_ACTIVE) {
        echo "   - Sessão: ✓ ativa (ID: " . session_id() . ")\n";
        echo "   - usuario_id: " . ($_SESSION['usuario_id'] ?? 'não definido') . "\n";
        echo "   - usuario_tipo: " . ($_SESSION['usuario_tipo'] ?? 'não definido') . "\n";
    } else {
        echo "   - Sessão: ✗ não ativa\n";
    }
} catch (Throwable $e) {
    echo "   - Sessão: ✗ ERRO: " . $e->getMessage() . "\n";
}

// 9. Verificar headers
echo "\n9. Headers enviados:\n";
if (headers_sent($file, $line)) {
    echo "   - Headers já enviados em: $file:$line\n";
} else {
    echo "   - Headers: ✓ ainda não enviados\n";
}

// 10. Últimos erros do log
echo "\n10. Últimos erros do log (se disponível):\n";
$logFile = __DIR__ . '/../storage/logs/app.log';
if (file_exists($logFile) && is_readable($logFile)) {
    $content = file_get_contents($logFile);
    $lines = explode("\n", $content);
    $lastLines = array_slice($lines, -20);
    foreach ($lastLines as $line) {
        if (trim($line)) {
            echo "   " . htmlspecialchars($line) . "\n";
        }
    }
} else {
    echo "   - Log não encontrado ou não legível\n";
}

echo "\n=== FIM DO DIAGNÓSTICO ===\n";
echo "</pre>";
