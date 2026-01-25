<?php

/**
 * Script temporário para remover o bloco else
 */

$file = '/home/weverton/Documentos/Repositorios/GitHub/check-planilha-imobilizado-ccb/app/views/planilhas/planilha_visualizar.php';

$content = file_get_contents($file);
$lines = explode("\n", $content);

// Encontrar a linha que contém "} else {" após "Imprimir Alteração"
$found = false;
$startIdx = -1;

for ($i = 0; $i < count($lines); $i++) {
    if (trim($lines[$i]) === '} else {' && $i > 100 && $i < 150) {
        // Verificar se a linha anterior contém algo relacionado ao menu
        if (strpos($lines[$i - 1], "li>'") !== false) {
            $startIdx = $i;
            break;
        }
    }
}

if ($startIdx !== -1) {
    // Encontrar a linha de fechamento "}"
    $endIdx = $startIdx;
    for ($j = $startIdx + 1; $j < $startIdx + 15; $j++) {
        if (trim($lines[$j]) === '}') {
            $endIdx = $j;
            break;
        }
    }

    // Remover as linhas
    $linesToRemove = $endIdx - $startIdx + 1;
    array_splice($lines, $startIdx, $linesToRemove);

    file_put_contents($file, implode("\n", $lines));
    file_put_contents('/tmp/fix_result.txt', "Removidas $linesToRemove linhas a partir do índice $startIdx\n");
} else {
    file_put_contents('/tmp/fix_result.txt', "Padrão não encontrado\n");
}
