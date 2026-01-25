<?php

/**
 * Script de reparo do arquivo planilha_visualizar.php
 * 
 * Este script corrige a corrupo no arquivo removendo linhas duplicadas
 * e caracteres corrompidos.
 */

$targetFile = dirname(__DIR__) . '/app/views/planilhas/planilha_visualizar.php';

if (!file_exists($targetFile)) {
    die("Arquivo no encontrado: $targetFile\n");
}

$content = file_get_contents($targetFile);

// Encontrar o final correto do headerActions (antes da corrupo)
$correctEnd = "        </ul>\n    </div>\n';";

// O incio da seo de comentrio
$commentStart = "// Iniciar buffer para capturar o conte";

// Padro para encontrar o trecho corrompido
// Procurar por '; e qualquer coisa at o prximo "// Iniciar buffer"
$pattern = "/        <\\/ul>\n    <\\/div>\n';\n[^\\n]*\\/\\/ Iniciar buffer[^\\n]*\n.*?\\n';\n\n\\/\\/ Iniciar buffer/s";

// Substituio correta
$replacement = "        </ul>\n    </div>\n';\n\n// Iniciar buffer";

// Tentar a substituio
$newContent = preg_replace($pattern, $replacement, $content, 1, $count);

if ($count > 0) {
    echo "Padro encontrado e substitudo.\n";
} else {
    echo "Padro no encontrado. Tentando abordagem alternativa...\n";

    // Abordagem 2: Ler linha por linha e reconstruir
    $lines = explode("\n", $content);
    $newLines = [];
    $skipMode = false;
    $skippedCount = 0;

    for ($i = 0; $i < count($lines); $i++) {
        $line = $lines[$i];

        // Detectar incio da corrupo (linha com '; e caracteres estranhos)
        if (strpos($line, "';") !== false && (
            strpos($line, "") !== false ||
            strpos($line, "") !== false ||
            strpos($line, "¤") !== false
        )) {
            $skipMode = true;
            echo "Detectada corrupo na linha " . ($i + 1) . "\n";
            continue;
        }

        // Detectar o fim do bloco corrompido (segunda ocorrncia de "// Iniciar buffer")
        if ($skipMode && strpos($line, "// Iniciar buffer") !== false) {
            $skipMode = false;
            // Reconstruir a linha correta
            $newLines[] = "";
            $newLines[] = "// Iniciar buffer para capturar o contedo";
            echo "Fim da corrupo detectado na linha " . ($i + 1) . ", puladas $skippedCount linhas\n";
            continue;
        }

        if ($skipMode) {
            $skippedCount++;
            continue;
        }

        $newLines[] = $line;
    }

    $newContent = implode("\n", $newLines);
}

// Verificar se ainda existe caracteres corrompidos
if (strpos($newContent, "") !== false || strpos($newContent, "") !== false) {
    echo "AVISO: Ainda existem caracteres corrompidos no arquivo.\n";
} else {
    echo "Arquivo limpo de caracteres corrompidos.\n";
}

// Fazer backup
$backupFile = $targetFile . '.backup_' . date('Ymd_His');
copy($targetFile, $backupFile);
echo "Backup criado: $backupFile\n";

// Salvar o arquivo corrigido
file_put_contents($targetFile, $newContent);
echo "Arquivo corrigido salvo.\n";

// Verificar sintaxe PHP
$output = [];
$returnCode = 0;
exec("php -l " . escapeshellarg($targetFile) . " 2>&1", $output, $returnCode);
echo "Verificao de sintaxe: " . implode("\n", $output) . "\n";

if ($returnCode === 0) {
    echo "SUCESSO: Arquivo reparado com sucesso!\n";
} else {
    echo "ERRO: O arquivo ainda possui erros de sintaxe.\n";
}
