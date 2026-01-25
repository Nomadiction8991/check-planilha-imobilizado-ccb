<?php

/**
 * Script para corrigir planilha_visualizar.php
 * Remove bloco duplicado e corrige encoding
 */

$file = dirname(__DIR__) . '/app/views/planilhas/planilha_visualizar.php';
$content = file_get_contents($file);

echo "Lendo arquivo: $file\n";
echo "Tamanho original: " . strlen($content) . " bytes\n";

// Backup
copy($file, $file . '.bak.' . date('YmdHis'));

// Buscar o padro corrompido
$badPattern = "';";
$badPos = strpos($content, $badPattern);

if ($badPos !== false) {
    echo "Encontrado padro corrompido na posio $badPos\n";

    // Encontrar onde termina o bloco bom (antes do lixo)
    $goodEnd = strrpos(substr($content, 0, $badPos), "';");
    if ($goodEnd !== false) {
        $goodEnd += 2; // incluir as aspas e ponto-virgula

        // Encontrar onde comea o prximo bloco bom (comentario Iniciar buffer correto)
        $nextGoodStart = strpos($content, "\n// Iniciar buffer", $badPos);
        if ($nextGoodStart !== false) {
            // Substituir o trecho
            $before = substr($content, 0, $goodEnd);
            $after = substr($content, $nextGoodStart);
            $content = $before . "\n" . $after;
            echo "Bloco duplicado removido\n";
        }
    }
} else {
    echo "Padro corrompido no encontrado\n";
}

// Agora corrigir os encodings corrompidos
$replacements = [
    'conte"do' => 'contedo',
    'boto' => 'boto',
    'Boto' => 'Boto',
    'c"³mera' => 'cmera',
    'C"³mera' => 'Cmera',
    'Paginao' => 'Paginao',
    'c"digo' => 'cdigo',
    'C"digo' => 'Cdigo',
    'no' => 'no',
    'No' => 'No',
    'tempor"­rio' => 'temporrio',
];

$totalReplacements = 0;
foreach ($replacements as $bad => $good) {
    $count = substr_count($content, $bad);
    if ($count > 0) {
        echo "Substituindo: $bad -> $good ($count)\n";
        $content = str_replace($bad, $good, $content);
        $totalReplacements += $count;
    }
}

echo "Total de substituies de encoding: $totalReplacements\n";

file_put_contents($file, $content);
echo "Arquivo salvo. Novo tamanho: " . strlen($content) . " bytes\n";

// Verificar sintaxe
$output = [];
$ret = 0;
exec("php -l " . escapeshellarg($file) . " 2>&1", $output, $ret);
echo "\nVerificao de sintaxe:\n";
echo implode("\n", $output) . "\n";

if ($ret === 0) {
    echo "\n Arquivo corrigido com sucesso!\n";
} else {
    echo "\n Possveis erros de sintaxe. Verifique o arquivo.\n";
}
