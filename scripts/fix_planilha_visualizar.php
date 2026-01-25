<?php

/**
 * Script para corrigir encoding corrompido em planilha_visualizar.php
 */

$filePath = __DIR__ . '/../app/views/planilhas/planilha_visualizar.php';

if (!file_exists($filePath)) {
    die("Arquivo no encontrado: $filePath\n");
}

echo "Lendo arquivo...\n";
$content = file_get_contents($filePath);

if ($content === false) {
    die("Erro ao ler o arquivo.\n");
}

$originalSize = strlen($content);
echo "Tamanho original: $originalSize bytes\n";

// Mapeamento de strings corrompidas para corrigidas
$replacements = [
    // Padro: boto
    'boto' => 'boto',
    'Boto' => 'Boto',
    'bot"es' => 'botes',
    'Bot"es' => 'Botes',

    // Padro: cdigo
    'c"digo' => 'cdigo',
    'C"digo' => 'Cdigo',
    'c"digos' => 'cdigos',
    'C"digos' => 'Cdigos',

    // Padro: no
    'no' => 'no',
    'No' => 'No',

    // Padro: contedo
    'conte"do' => 'contedo',
    'Conte"do' => 'Contedo',

    // Padro: temporrio
    'tempor"­rio' => 'temporrio',
    'Tempor"­rio' => 'Temporrio',

    // Padro: Paginao
    'Paginao' => 'Paginao',
    'paginao' => 'paginao',

    // Padro: cmera
    'c"³mera' => 'cmera',
    'C"³mera' => 'Cmera',

    // Padro: espao
    'espao' => 'espao',
    'Espao' => 'Espao',
    'espaos' => 'espaos',

    // Padro: verso
    'verso' => 'verso',
    'Verso' => 'Verso',

    // Padro: edio
    'Edio' => 'Edio',
    'edio' => 'edio',

    // Padro: informaes
    'Informa"³es' => 'Informaes',
    'informa"³es' => 'informaes',

    // Padro: contrrio
    'contr"­rio' => 'contrrio',

    // Padro: dinmica
    'din"³mica' => 'dinmica',

    // Padro: padro
    'padro' => 'padro',
    'Padro' => 'Padro',

    // Padro: funo
    'Funo' => 'Funo',
    'funo' => 'funo',

    // Padro: traos
    'traos' => 'traos',

    // Padro: disponveis
    'dispon"¡veis' => 'disponveis',
    'dispon"¡vel' => 'disponvel',

    // Padro: hfen
    'h"¡fen' => 'hfen',

    // Padro: trs
    'tr"¬s' => 'trs',

    // Padro: vrgula
    'v"¡rgula' => 'vrgula',

    // Padro: vdeo
    'v"¡deo' => 'vdeo',

    // Padro: frequncia
    'frequ"¬ncia' => 'frequncia',

    // Padro: localizao
    'localizao' => 'localizao',

    // Padro: seleo
    'seleo' => 'seleo',

    // Padro: est
    'est"­' => 'est',

    // Padro: j
    'j"­' => 'j',

    // Padro: possvel
    'poss"¡vel' => 'possvel',

    // Padro: rpido
    'r"­pido' => 'rpido',

    // Padro: mdio
    'm"®dio' => 'mdio',

    // Padro: voc
    'voc"¬' => 'voc',

    // Padro: permisso
    'permisso' => 'permisso',

    // Padro: mudana
    'mudana' => 'mudana',
    'Mudana' => 'Mudana',

    // Padro: MUDANA
    'MUDAN"§A' => 'MUDANA',

    // cones corrompidos - pode haver emojis malformados tambm
    '­´£' => '',
    '®' => '',
    '£ ' => '',
    '­¸¦' => '',
    '»´©' => '',
    '"¡´©' => '',
    '­"¬' => '',
    '­¼' => '',
    '­' => '',
    '­«' => '',
    '­´©' => '',
];

$count = 0;
foreach ($replacements as $search => $replace) {
    $found = substr_count($content, $search);
    if ($found > 0) {
        echo "Encontrado '$search' -> '$replace' ($found ocorrncias)\n";
        $content = str_replace($search, $replace, $content);
        $count += $found;
    }
}

echo "\nTotal de substituies: $count\n";

// Agora, remover o bloco duplicado
// O bloco duplicado est entre as linhas 139-156 aprox
// Vou procurar e remover o padro especfico do bloco duplicado

// Padro do bloco duplicado (aps as correes acima)
$duplicatePattern = '/\'\;\n\s*\$headerActions\s*\.=\s*\'\s*<li>\s*<a\s+class="dropdown-item"\s+href="\.\.\/planilhas\/relatorio141_view\.php[^\']+\'\s*\.\s*\'\s*<\/a>\s*\$headerActions\s*\.=\s*\'\s*<li><hr\s+class="dropdown-divider"><\/li>\s*<li>\s*<a\s+class="dropdown-item"\s+href="\.\.\/\.\.\/\.\.\/logout\.php">\s*<i\s+class="bi\s+bi-box-arrow-right\s+me-2"><\/i>Sair\s*<\/a>\s*<\/li>\s*<\/ul>\s*<\/div>\s*\'\;\n\n\/\/ Iniciar buffer/s';

// Primeiro, vou verificar se o contedo duplicado ainda existe
if (strpos($content, 'Iniciar buffer') !== false) {
    echo "\nEncontrado 'Iniciar buffer' - verificando duplicao...\n";
}

// Padro simplificado para remover: a linha corrompida + bloco duplicado
// Buscar por:
// ';... (lixo) ... rios
//     $headerActions .= '
//           <li>
//               <a class="dropdown-item" href="../planilhas/relatorio141_view.php...
// ...
// ';
// e substituir apenas por:
// ';

// Usar regex mais simples para encontrar a duplicao
$pattern = '/\'\;\n([^\n]*rios\n)?\s*\$headerActions\s*\.=\s*\'\s*<li>\s*<a\s+class="dropdown-item"\s+href="\.\.\/planilhas\/relatorio141_view\.php[^\']+\'[^;]+;\n\s*\$headerActions\s*\.=\s*\'[^\']+\';\n\n\/\/ Iniciar buffer para capturar o contedo/ms';

$matched = preg_match($pattern, $content, $matches);
if ($matched) {
    echo "\nBloco duplicado encontrado. Removendo...\n";
    $content = preg_replace($pattern, "';\n\n// Iniciar buffer para capturar o contedo", $content, 1);
} else {
    echo "\nBloco duplicado no encontrado com regex. Tentando outra abordagem...\n";
}

// Verificar se h linhas com lixo de encoding
$lines = explode("\n", $content);
$fixedLines = [];
$skipUntil = -1;

for ($i = 0; $i < count($lines); $i++) {
    $line = $lines[$i];

    // Pular linhas que fazem parte do bloco duplicado
    if ($i < $skipUntil) {
        continue;
    }

    // Detectar incio do bloco duplicado (linha com lixo seguida de $headerActions duplicado)
    if (
        preg_match('/^\';\s*[^\x00-\x7F]+/', $line) &&
        isset($lines[$i + 1]) &&
        strpos($lines[$i + 1], '$headerActions .=') !== false &&
        strpos($lines[$i + 1], 'relatorio141_view') !== false
    ) {

        echo "Linha $i: Detectado incio de bloco duplicado\n";

        // Pular at encontrar a prxima ocorrncia de "// Iniciar buffer"
        for ($j = $i + 1; $j < count($lines); $j++) {
            if (strpos($lines[$j], '// Iniciar buffer') !== false) {
                $skipUntil = $j; // No incluir a linha duplicada do comentrio
                // Adicionar apenas o fechamento correto
                $fixedLines[] = "';";
                $fixedLines[] = "";
                break;
            }
        }
        continue;
    }

    $fixedLines[] = $line;
}

$content = implode("\n", $fixedLines);

// Salvar arquivo
echo "\nSalvando arquivo...\n";
$result = file_put_contents($filePath, $content);

if ($result === false) {
    die("Erro ao salvar o arquivo.\n");
}

$newSize = strlen($content);
echo "Tamanho novo: $newSize bytes\n";
echo "Diferena: " . ($originalSize - $newSize) . " bytes\n";

echo "\nArquivo corrigido com sucesso!\n";

// Verificar sintaxe PHP
echo "\nVerificando sintaxe PHP...\n";
$output = [];
$returnCode = 0;
exec("php -l " . escapeshellarg($filePath) . " 2>&1", $output, $returnCode);

echo implode("\n", $output) . "\n";

if ($returnCode !== 0) {
    echo "\n  AVISO: O arquivo pode conter erros de sintaxe. Verifique manualmente.\n";
} else {
    echo "\n Sintaxe PHP vlida!\n";
}
