<?php

/**
 * Script para corrigir encoding corrompido e bloco duplicado
 */

$file = __DIR__ . '/../app/views/planilhas/planilha_visualizar.php';

echo "Lendo arquivo: $file\n";
$content = file_get_contents($file);

if ($content === false) {
    die("Erro ao ler arquivo\n");
}

$originalSize = strlen($content);
echo "Tamanho original: $originalSize bytes\n";

$lines = explode("\n", $content);
$totalLines = count($lines);
echo "Total de linhas: $totalLines\n";

// Identificar e remover o bloco duplicado (linhas 139-155 aprox)
// Linha 139: comea com '; seguido de lixo
// Linhas 140-154: cdigo duplicado
// Linha 155: ';\n
// Linha 156: // Iniciar buffer... (com encoding corrompido)

$newLines = [];
$skipMode = false;
$skipCount = 0;

for ($i = 0; $i < $totalLines; $i++) {
    $lineNum = $i + 1;
    $line = $lines[$i];

    // Detectar incio do bloco duplicado (linha que comea com '; e tem lixo)
    if ($lineNum == 139 && strpos($line, "';") === 0 && strpos($line, "Iniciar buffer") !== false) {
        echo "Linha $lineNum: Detectado incio de bloco corrompido - removendo\n";
        $skipMode = true;
        // Adicionar apenas a linha de fechamento correta
        $newLines[] = "';";
        $newLines[] = "";
        continue;
    }

    // Se estamos pulando, verificar quando parar
    if ($skipMode) {
        // Parar quando encontrar a linha correta "// Iniciar buffer"
        if (strpos($line, "// Iniciar buffer") === 0) {
            echo "Linha $lineNum: Fim do bloco duplicado encontrado\n";
            $skipMode = false;
            // Corrigir encoding desta linha
            $line = "// Iniciar buffer para capturar o contedo";
            $skipCount++;
        } else {
            echo "Linha $lineNum: Pulando (bloco duplicado)\n";
            $skipCount++;
            continue;
        }
    }

    $newLines[] = $line;
}

echo "Linhas removidas: $skipCount\n";

// Juntar as linhas
$content = implode("\n", $newLines);

// Agora fazer as substituies de encoding corrompido
$replacements = [
    // boto, botes
    'boto' => 'boto',
    'Boto' => 'Boto',
    'bot"es' => 'botes',
    'Bot"es' => 'Botes',

    // cdigo, cdigos
    'c"digo' => 'cdigo',
    'C"digo' => 'Cdigo',
    'c"digos' => 'cdigos',

    // no
    'no' => 'no',
    'No' => 'No',

    // contedo
    'conte"do' => 'contedo',
    'Conte"do' => 'Contedo',

    // temporrio
    'tempor"­rio' => 'temporrio',

    // Paginao
    'Paginao' => 'Paginao',

    // cmera
    'c"³mera' => 'cmera',
    'C"³mera' => 'Cmera',

    // espao
    'espao' => 'espao',
    'espaos' => 'espaos',

    // verso
    'verso' => 'verso',

    // Edio
    'Edio' => 'Edio',
    'edio' => 'edio',

    // Informaes
    'Informa"³es' => 'Informaes',
    'informa"³es' => 'informaes',

    // contrrio
    'contr"­rio' => 'contrrio',

    // dinmica
    'din"³mica' => 'dinmica',

    // padro
    'padro' => 'padro',
    'Padro' => 'Padro',

    // Funo
    'Funo' => 'Funo',
    'funo' => 'funo',

    // traos
    'traos' => 'traos',

    // disponveis
    'dispon"¡veis' => 'disponveis',
    'dispon"¡vel' => 'disponvel',

    // hfen
    'h"¡fen' => 'hfen',

    // trs
    'tr"¬s' => 'trs',

    // vrgula
    'v"¡rgula' => 'vrgula',

    // vdeo
    'v"¡deo' => 'vdeo',

    // frequncia
    'frequ"¬ncia' => 'frequncia',

    // localizao
    'localizao' => 'localizao',

    // seleo
    'seleo' => 'seleo',

    // est
    'est"­' => 'est',

    // j
    'j"­' => 'j',

    // possvel
    'poss"¡vel' => 'possvel',

    // rpido
    'r"­pido' => 'rpido',

    // mdio
    'm"®dio' => 'mdio',

    // voc
    'voc"¬' => 'voc',

    // permisso
    'permisso' => 'permisso',

    // Mudana
    'Mudana' => 'Mudana',
    'MUDAN"§A' => 'MUDANA',

    // Ao, Aes
    'A"§ES' => 'AES',

    // cdigo legado
    'c³digo' => 'cdigo',

    // importao
    'importa§£o' => 'importao',
];

$totalReplacements = 0;
foreach ($replacements as $search => $replace) {
    $count = substr_count($content, $search);
    if ($count > 0) {
        echo "Substituindo '$search' -> '$replace' ($count vezes)\n";
        $content = str_replace($search, $replace, $content);
        $totalReplacements += $count;
    }
}

echo "\nTotal de substituies de encoding: $totalReplacements\n";

// Salvar o arquivo
$result = file_put_contents($file, $content);

if ($result === false) {
    die("Erro ao salvar arquivo\n");
}

$newSize = strlen($content);
echo "Tamanho novo: $newSize bytes\n";
echo "Diferena: " . ($originalSize - $newSize) . " bytes\n";

// Verificar sintaxe
echo "\nVerificando sintaxe PHP...\n";
$output = [];
$returnCode = 0;
exec("php -l " . escapeshellarg($file) . " 2>&1", $output, $returnCode);

foreach ($output as $line) {
    echo $line . "\n";
}

if ($returnCode === 0) {
    echo "\n Arquivo corrigido com sucesso!\n";
} else {
    echo "\n  AVISO: Possveis erros de sintaxe. Verifique manualmente.\n";
}
