<?php

/**
 * Script para corrigir planilha_visualizar.php
 * Remove o bloco duplicado/corrompido e mantm a estrutura correta
 */

$file = dirname(__DIR__) . '/app/views/planilhas/planilha_visualizar.php';
$content = file_get_contents($file);

// Backup
$backup = $file . '.bak_v3_' . date('YmdHis');
copy($file, $backup);
echo "Backup criado: $backup\n";

// Converter para linhas
$lines = explode("\n", $content);
$totalLines = count($lines);
echo "Total de linhas original: $totalLines\n";

// Encontrar a linha 293 (ndice 292) que contm o CSS correto
// e a linha 294 (ndice 293) que est corrompida
$newLines = [];
$skipUntil = -1;

for ($i = 0; $i < $totalLines; $i++) {
    $line = $lines[$i];
    $lineNum = $i + 1;

    // Pular linhas at o ponto definido
    if ($skipUntil > 0 && $i < $skipUntil) {
        continue;
    }
    $skipUntil = -1;

    // Linha 293:  o CSS correto, mas linha 294 est corrompida
    // Se a linha atual termina com "hover {" e a prxima tem lixo, tratar
    if (strpos($line, '.acao-container .btn:not([disabled]):not(.disabled):hover {') !== false) {
        // Verificar se a prxima linha tem corrupo
        $nextLine = isset($lines[$i + 1]) ? $lines[$i + 1] : '';
        if (
            strpos($nextLine, 'transform: translateY(-1px);') !== false &&
            (strpos($nextLine, '') !== false || strpos($nextLine, '') !== false || strpos($nextLine, 'Iniciar buffer') !== false)
        ) {

            echo "Linha $lineNum: Detectado incio do bloco corrompido\n";

            // Adicionar a abertura do seletor correto
            $newLines[] = $line;

            // Encontrar onde o bloco duplicado termina (segunda ocorrncia de .acao-container .btn {)
            for ($j = $i + 1; $j < $totalLines; $j++) {
                // Procurar pelo segundo bloco .acao-container .btn { (que seria o incio do CSS correto novamente)
                if (strpos($lines[$j], '/* Aes: usar padro Bootstrap para botes') !== false) {
                    $skipUntil = $j;
                    echo "Linha " . ($j + 1) . ": Encontrado ponto de retomada do CSS correto\n";
                    break;
                }
            }

            // Adicionar o contedo correto do hover
            $newLines[] = "        transform: translateY(-1px);";
            $newLines[] = "        box-shadow: 0 6px 18px rgba(15, 23, 42, 0.12);";
            $newLines[] = "    }";
            $newLines[] = "";
            $newLines[] = "    .acao-container .btn[disabled],";
            $newLines[] = "    .acao-container .btn.disabled {";
            $newLines[] = "        pointer-events: none;";
            $newLines[] = "        opacity: 0.55;";
            $newLines[] = "    }";
            $newLines[] = "";
            $newLines[] = "    /* Boto visualmente desabilitado (mas clicvel quando necessrio, ex: imprimir que autocheca) */";
            $newLines[] = "    .acao-container .btn.disabled-visually {";
            $newLines[] = "        pointer-events: auto;";
            $newLines[] = "        opacity: 0.45;";
            $newLines[] = "        filter: grayscale(0.18);";
            $newLines[] = "    }";
            $newLines[] = "";
            $newLines[] = "    .acao-container .btn.disabled-visually:hover {";
            $newLines[] = "        transform: none;";
            $newLines[] = "        box-shadow: none;";
            $newLines[] = "    }";
            $newLines[] = "";
            $newLines[] = "    /* Cores dos botes (paleta coerente com tema) */";
            $newLines[] = "    .acao-container .action-check button {";
            $newLines[] = "        border-color: #28A745;";
            $newLines[] = "        color: #28A745;";
            $newLines[] = "    }";
            $newLines[] = "";
            $newLines[] = "    .acao-container .action-check button.active,";
            $newLines[] = "    .acao-container .action-check button:hover {";
            $newLines[] = "        background: #28A745;";
            $newLines[] = "        color: #fff;";
            $newLines[] = "    }";
            $newLines[] = "";
            $newLines[] = "    .acao-container .action-imprimir button {";
            $newLines[] = "        border-color: #0D6EFD;";
            $newLines[] = "        color: #0D6EFD;";
            $newLines[] = "    }";
            $newLines[] = "";
            $newLines[] = "    .acao-container .action-imprimir button.active,";
            $newLines[] = "    .acao-container .action-imprimir button:hover {";
            $newLines[] = "        background: #0D6EFD;";
            $newLines[] = "        color: #fff;";
            $newLines[] = "    }";
            $newLines[] = "";
            $newLines[] = "    /* Aparncia quando o boto de imprimir estiver bloqueado (produto editado) */";
            $newLines[] = "    .acao-container .action-imprimir button[disabled] {";
            $newLines[] = "        opacity: 0.45;";
            $newLines[] = "        cursor: not-allowed;";
            $newLines[] = "        filter: grayscale(20%);";
            $newLines[] = "    }";
            $newLines[] = "";
            $newLines[] = "    .acao-container .action-observacao {";
            $newLines[] = "        border-color: #FB8C00 !important;";
            $newLines[] = "        color: #FB8C00 !important;";
            $newLines[] = "    }";
            $newLines[] = "";
            $newLines[] = "    .acao-container .action-observacao:hover {";
            $newLines[] = "        background: #FB8C00 !important;";
            $newLines[] = "        color: #fff !important;";
            $newLines[] = "    }";
            $newLines[] = "";
            $newLines[] = "    .acao-container .action-etiqueta {";
            $newLines[] = "        border-color: #6F42C1 !important;";
            $newLines[] = "        color: #6F42C1 !important;";
            $newLines[] = "    }";
            $newLines[] = "";
            $newLines[] = "    .acao-container .action-etiqueta:hover {";
            $newLines[] = "        background: #6F42C1 !important;";
            $newLines[] = "        color: #fff !important;";
            $newLines[] = "    }";
            $newLines[] = "";
            $newLines[] = "    .acao-container .action-signatarios {";
            $newLines[] = "        border-color: #17A2B8 !important;";
            $newLines[] = "        color: #17A2B8 !important;";
            $newLines[] = "    }";
            $newLines[] = "";
            $newLines[] = "    .acao-container .action-signatarios:hover {";
            $newLines[] = "        background: #17A2B8 !important;";
            $newLines[] = "        color: #fff !important;";
            $newLines[] = "    }";
            $newLines[] = "";
            $newLines[] = "    .acao-container .action-editar {";
            $newLines[] = "        border-color: #6C757D !important;";
            $newLines[] = "        color: #6C757D !important;";
            $newLines[] = "    }";
            $newLines[] = "";
            $newLines[] = "    .acao-container .action-editar:hover {";
            $newLines[] = "        background: #6C757D !important;";
            $newLines[] = "        color: #fff !important;";
            $newLines[] = "    }";
            $newLines[] = "";
            $newLines[] = "    /* Indicadores de estado (para botes com toggle) */";
            $newLines[] = "    .acao-container .btn.active {";
            $newLines[] = "        transform: scale(1.05);";
            $newLines[] = "        box-shadow: 0 0 0 3px rgba(40, 167, 69, 0.18);";
            $newLines[] = "    }";
            $newLines[] = "";
            continue;
        }
    }

    $newLines[] = $line;
}

$newContent = implode("\n", $newLines);
$newTotal = count($newLines);
echo "Total de linhas novo: $newTotal\n";
echo "Linhas removidas: " . ($totalLines - $newTotal) . "\n";

// Salvar arquivo
file_put_contents($file, $newContent);
echo "Arquivo salvo.\n";

// Verificar sintaxe
$output = [];
$ret = 0;
exec("php -l " . escapeshellarg($file) . " 2>&1", $output, $ret);
echo "\nVerificao de sintaxe:\n";
foreach ($output as $line) {
    echo $line . "\n";
}

if ($ret === 0) {
    echo "\n Arquivo corrigido com sucesso!\n";
} else {
    echo "\n Ainda h erros. Restaure o backup se necessrio.\n";
}
