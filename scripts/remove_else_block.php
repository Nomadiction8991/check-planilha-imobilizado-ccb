<?php
// Script para editar planilha_visualizar.php
$file = '/home/weverton/Documentos/Repositorios/GitHub/check-planilha-imobilizado-ccb/app/views/planilhas/planilha_visualizar.php';
$content = file_get_contents($file);
$lines = explode("\n", $content);

echo "Total de linhas: " . count($lines) . "\n";

// Mostrar linhas ao redor da linha 132
for ($i = 129; $i <= 145; $i++) {
    if (isset($lines[$i])) {
        echo "Linha " . ($i + 1) . ": " . $lines[$i] . "\n";
    }
}

// Encontrar e remover as linhas problemáticas
// Procurar pela linha que contém "Doador"
$toRemoveStart = -1;
$toRemoveEnd = -1;

for ($i = 0; $i < count($lines); $i++) {
    if (strpos($lines[$i], 'Doador') !== false) {
        echo "Encontrado 'Doador' na linha " . ($i + 1) . "\n";
        $toRemoveStart = $i;
        // Encontrar a linha de fechamento - procurar por "}" isolado
        for ($j = $i + 1; $j < $i + 15 && $j < count($lines); $j++) {
            if (trim($lines[$j]) === '}') {
                $toRemoveEnd = $j;
                echo "Encontrado '}' na linha " . ($j + 1) . "\n";
                break;
            }
        }
        break;
    }
}

if ($toRemoveStart !== -1 && $toRemoveEnd !== -1) {
    echo "Removendo linhas " . ($toRemoveStart + 1) . " a " . ($toRemoveEnd + 1) . "\n";
    // Remover as linhas
    array_splice($lines, $toRemoveStart, $toRemoveEnd - $toRemoveStart + 1);
    file_put_contents($file, implode("\n", $lines));
    echo "Arquivo salvo. Novas linhas: " . count($lines) . "\n";
} else {
    echo "Bloco não encontrado para remoção.\n";
}
