<?php

/**
 * Script definitivo para corrigir planilha_visualizar.php
 * Remove linhas corrompidas e corrige encoding
 */

$file = dirname(__DIR__) . '/app/views/planilhas/planilha_visualizar.php';
$content = file_get_contents($file);

// Converter para array de linhas
$lines = explode("\n", $content);
$newLines = [];
$skipUntilNextBuffer = false;
$lineCount = count($lines);

echo "Total de linhas: $lineCount\n";

for ($i = 0; $i < $lineCount; $i++) {
    $line = $lines[$i];

    // Detectar a linha corrompida que comea com "';"
    if (strpos($line, "';") !== false && (
        strpos($line, "") !== false ||
        strpos($line, "") !== false ||
        mb_detect_encoding($line, 'UTF-8', true) !== 'UTF-8' ||
        preg_match('/[^\x00-\x7F\xC0-\xFF]/', $line)
    )) {
        echo "Linha $i corrompida detectada: " . substr($line, 0, 50) . "...\n";
        $skipUntilNextBuffer = true;
        continue;
    }

    // Parar de pular quando encontrar "// Iniciar buffer" vlido
    if ($skipUntilNextBuffer && strpos($line, '// Iniciar buffer') !== false) {
        // Verificar se  uma linha limpa
        if (strpos($line, '') === false && strpos($line, '') === false) {
            $skipUntilNextBuffer = false;
            $newLines[] = '';  // linha em branco antes
            $newLines[] = '// Iniciar buffer para capturar o contedo';
            echo "Restaurado comentrio na linha $i\n";
            continue;
        }
    }

    if ($skipUntilNextBuffer) {
        echo "Pulando linha $i\n";
        continue;
    }

    // Corrigir encoding na linha
    $line = str_replace('conte"do', 'contedo', $line);
    $line = str_replace('boto', 'boto', $line);
    $line = str_replace('Paginao', 'Paginao', $line);
    $line = str_replace('c"digo', 'cdigo', $line);
    $line = str_replace('Conte"do', 'Contedo', $line);
    $line = str_replace('tempor"­rio', 'temporrio', $line);
    $line = str_replace('o', 'o', $line);
    $line = str_replace('', '', $line);
    $line = str_replace('', '', $line);
    $line = str_replace('', '', $line);
    $line = str_replace('', '', $line);
    $line = str_replace('', '', $line);
    $line = str_replace('', '', $line);
    $line = str_replace('', '', $line);
    $line = str_replace('', '', $line);

    $newLines[] = $line;
}

$newContent = implode("\n", $newLines);
echo "Novas linhas: " . count($newLines) . "\n";

// Backup
copy($file, $file . '.bak_' . date('YmdHis'));

// Salvar
file_put_contents($file, $newContent);
echo "Arquivo salvo.\n";

// Verificar sintaxe
$output = shell_exec('php -l ' . escapeshellarg($file) . ' 2>&1');
echo "Verificao de sintaxe: $output\n";
