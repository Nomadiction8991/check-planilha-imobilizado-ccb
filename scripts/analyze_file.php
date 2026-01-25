<?php

/**
 * Script simples para remover linhas específicas do arquivo
 */

$file = __DIR__ . '/../app/views/planilhas/planilha_visualizar.php';
$lines = file($file, FILE_IGNORE_NEW_LINES);
$total = count($lines);

echo "Total original: $total linhas\n";

// Remover linhas 293-314 (índices 292-313) que contêm o bloco corrompido
$linesToRemove = range(292, 313);

// Adicionar linhas 315-600 também se forem duplicadas
// Primeiro verificar quantas vezes ".acao-container .btn {" aparece
$acaoContainerCount = 0;
foreach ($lines as $i => $line) {
    if (strpos($line, '.acao-container .btn {') !== false && strpos($line, ':not') === false) {
        $acaoContainerCount++;
        echo "Encontrado '.acao-container .btn {' na linha " . ($i + 1) . "\n";
    }
}

echo "Total de '.acao-container .btn {': $acaoContainerCount\n";

// Verificar <style> tags
$styleCount = 0;
foreach ($lines as $i => $line) {
    if (strpos($line, '<style>') !== false) {
        $styleCount++;
        echo "Encontrado '<style>' na linha " . ($i + 1) . "\n";
    }
}

echo "Total de '<style>': $styleCount\n";

// Verificar </style> tags
$styleCloseCount = 0;
foreach ($lines as $i => $line) {
    if (strpos($line, '</style>') !== false) {
        $styleCloseCount++;
        echo "Encontrado '</style>' na linha " . ($i + 1) . "\n";
    }
}

echo "Total de '</style>': $styleCloseCount\n";

// Salvar resultado em arquivo de log
file_put_contents('/tmp/fix_analysis.txt', ob_get_contents());
