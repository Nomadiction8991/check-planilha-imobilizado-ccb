<?php

/**
 * Script para corrigir encoding corrompido em planilha_visualizar.php
 * Execuo nica - apagar aps uso
 */

$filePath = __DIR__ . '/../app/views/planilhas/planilha_visualizar.php';

if (!file_exists($filePath)) {
    die("Arquivo no encontrado: $filePath\n");
}

$content = file_get_contents($filePath);
$originalSize = strlen($content);

echo "Tamanho original: $originalSize bytes\n";

// Lista de substituies: encoding corrompido => texto correto
$replacements = [
    // Bloco CSS corrompido com cdigo PHP inserido (linhas 293-305)
    // Este  o mais crtico - cdigo PHP dentro do CSS
    '.acao-container .btn:not([disabled]):not(.disabled):hover {
        transform: translateY(-1px);
        "¤ // Iniciar buffer"rios
        $headerActions .=\'
 <li><a class="dropdown-item" href="../planilhas/relatorio141_view.php?id=\' . $comum_id . \'&comum_id=\' . $comum_id . \'"><i class="bi bi-file-earmark-pdf me-2"></i>\' . htmlspecialchars(to_uppercase(\' Relatrio 14.1\'), ENT_QUOTES, \' UTF-8\') . \'
        </a>$headerActions .=\'
 <li><hr class="dropdown-divider"></li><li><a class="dropdown-item" href="../../../logout.php"><i class="bi bi-box-arrow-right me-2"></i>Sair </a></li></ul></div>\';

        // Iniciar buffer para capturar o conte"do
        ob_start();
        ?><style>

        /* Estilos para o boto de microfone */'
    =>
    '.acao-container .btn:not([disabled]):not(.disabled):hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    }

    /* Estilos para o boto de microfone */',

    // Comentrios com encoding corrompido
    'importa§£o' => 'importao',
    'c³digo' => 'cdigo',
    'conte"do' => 'contedo',
    'boto' => 'boto',
    'bot"es' => 'botes',
    'no' => 'no',
    'espao' => 'espao',
    'padro' => 'padro',
    'C"digo' => 'Cdigo',
    'Edio' => 'Edio',
    'contr"­rio' => 'contrrio',
    'verso' => 'verso',
    'din"³mica' => 'dinmica',
    'Informa"es' => 'Informaes',
    'c"³mera' => 'cmera',
    'BOT"¢O' => 'BOTO',
    'C"©MERA' => 'CMERA',
    'MUDAN"§A' => 'MUDANA',
    'r"­pido' => 'rpido',

    // Emojis corrompidos em console.log
    '­´©' => '',
    '­¼' => '',
    '­' => '',
    '£ ' => '',
    '­´£' => '',
];

$count = 0;
foreach ($replacements as $bad => $good) {
    $occurrences = substr_count($content, $bad);
    if ($occurrences > 0) {
        $content = str_replace($bad, $good, $content);
        echo "Substitudo '$bad' => '$good' ($occurrences ocorrncias)\n";
        $count += $occurrences;
    }
}

if ($count === 0) {
    echo "Nenhuma substituio feita. Verificando padres alternativos...\n";

    // Tentar encontrar o bloco corrompido de outra forma
    if (preg_match('/\.acao-container \.btn:not\(\[disabled\]\):not\(\.disabled\):hover \{[^}]+buffer/s', $content, $matches)) {
        echo "Bloco corrompido encontrado via regex:\n";
        echo substr($matches[0], 0, 200) . "...\n";
    }
} else {
    $newSize = strlen($content);
    echo "\nTotal de substituies: $count\n";
    echo "Tamanho novo: $newSize bytes (diferena: " . ($originalSize - $newSize) . " bytes)\n";

    // Backup
    $backupPath = $filePath . '.backup.' . date('Ymd_His');
    copy($filePath, $backupPath);
    echo "Backup criado: $backupPath\n";

    // Salvar
    file_put_contents($filePath, $content);
    echo "Arquivo salvo com sucesso!\n";
}
