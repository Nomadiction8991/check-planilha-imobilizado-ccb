<?php

/**
 * Script para corrigir o bloco CSS corrompido em planilha_visualizar.php
 * Este script identifica e remove o bloco duplicado/corrompido de forma robusta
 * 
 * Estratégia:
 * 1. Ler o arquivo original linha por linha
 * 2. Identificar o início do bloco corrompido (onde há código PHP no CSS)
 * 3. Identificar o fim do bloco corrompido (antes da versão correta do CSS)
 * 4. Remover as linhas corrompidas e manter a versão correta
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

$filePath = __DIR__ . '/../app/views/planilhas/planilha_visualizar.php';
$backupPath = $filePath . '.backup_' . date('Ymd_His');

// Ler arquivo completo (preservando linhas vazias)
$content = file_get_contents($filePath);
$lines = explode("\n", $content);
$totalLines = count($lines);

file_put_contents(__DIR__ . '/../storage/logs/fix_css.log', "Iniciando correção...\n", FILE_APPEND);
file_put_contents(__DIR__ . '/../storage/logs/fix_css.log', "Total de linhas: $totalLines\n", FILE_APPEND);

// Fazer backup primeiro
copy($filePath, $backupPath);
file_put_contents(__DIR__ . '/../storage/logs/fix_css.log', "Backup criado: $backupPath\n", FILE_APPEND);

// Procurar o bloco corrompido
// Indicadores de corrupção: 'headerActions' ou 'ob_start()' dentro de bloco CSS
$corruptedStart = null;
$corruptedEnd = null;

for ($i = 0; $i < $totalLines; $i++) {
    $line = $lines[$i];

    // Procurar pela primeira ocorrência de .acao-container .btn:not que é seguida por código PHP corrompido
    if ($corruptedStart === null) {
        if (strpos($line, '.acao-container .btn:not([disabled]):not(.disabled):hover') !== false) {
            // Verificar próximas 10 linhas para ver se há código PHP (indicador de corrupção)
            for ($j = $i + 1; $j < min($i + 15, $totalLines); $j++) {
                if (
                    strpos($lines[$j], 'headerActions') !== false ||
                    strpos($lines[$j], 'ob_start()') !== false ||
                    strpos($lines[$j], '?><style>') !== false
                ) {
                    $corruptedStart = $i;
                    file_put_contents(__DIR__ . '/../storage/logs/fix_css.log', "Corrupção inicia na linha " . ($i + 1) . "\n", FILE_APPEND);
                    break;
                }
            }
        }
    }

    // Se já encontramos o início, procurar o fim
    if ($corruptedStart !== null && $corruptedEnd === null) {
        // O fim é identificado quando encontramos novamente .acao-container .btn:not COM box-shadow correto nas próximas linhas
        if (
            $i > $corruptedStart + 10 &&
            strpos($line, '.acao-container .btn:not([disabled]):not(.disabled):hover') !== false
        ) {
            // Verificar se as próximas 3 linhas têm transform e box-shadow (versão correta)
            $hasTransform = false;
            $hasBoxShadow = false;
            for ($j = $i + 1; $j < min($i + 4, $totalLines); $j++) {
                if (strpos($lines[$j], 'transform:') !== false) $hasTransform = true;
                if (strpos($lines[$j], 'box-shadow:') !== false) $hasBoxShadow = true;
            }
            if ($hasTransform && $hasBoxShadow) {
                // O correto começa aqui, então o corrompido termina na linha anterior
                $corruptedEnd = $i - 1;
                file_put_contents(__DIR__ . '/../storage/logs/fix_css.log', "Corrupção termina na linha " . ($corruptedEnd + 1) . "\n", FILE_APPEND);
                break;
            }
        }
    }
}

if ($corruptedStart !== null && $corruptedEnd !== null) {
    file_put_contents(__DIR__ . '/../storage/logs/fix_css.log', "Removendo linhas " . ($corruptedStart + 1) . " a " . ($corruptedEnd + 1) . "\n", FILE_APPEND);

    // Construir novo array sem as linhas corrompidas
    $newLines = [];
    for ($i = 0; $i < $totalLines; $i++) {
        if ($i < $corruptedStart || $i > $corruptedEnd) {
            $newLines[] = $lines[$i];
        }
    }

    // Salvar arquivo corrigido
    $newContent = implode("\n", $newLines);
    file_put_contents($filePath, $newContent);

    $linhasRemovidas = $corruptedEnd - $corruptedStart + 1;
    $novoTotal = count($newLines);

    file_put_contents(__DIR__ . '/../storage/logs/fix_css.log', "Arquivo corrigido!\n", FILE_APPEND);
    file_put_contents(__DIR__ . '/../storage/logs/fix_css.log', "Linhas removidas: $linhasRemovidas\n", FILE_APPEND);
    file_put_contents(__DIR__ . '/../storage/logs/fix_css.log', "Novo total: $novoTotal linhas\n", FILE_APPEND);

    echo "SUCCESS: Arquivo corrigido!\n";
    echo "Linhas removidas: $linhasRemovidas (de " . ($corruptedStart + 1) . " a " . ($corruptedEnd + 1) . ")\n";
    echo "Total de linhas agora: $novoTotal\n";
} else {
    file_put_contents(__DIR__ . '/../storage/logs/fix_css.log', "Bloco corrompido não encontrado.\n", FILE_APPEND);
    echo "INFO: Bloco corrompido não encontrado ou já foi corrigido.\n";
    echo "corruptedStart: " . ($corruptedStart ?? 'null') . "\n";
    echo "corruptedEnd: " . ($corruptedEnd ?? 'null') . "\n";
}
