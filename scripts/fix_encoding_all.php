<?php

/**
 * Script para corrigir encoding UTF-8 corrompido em todos os arquivos PHP
 * Corrige apenas textos em comentrios e strings, preservando cdigo
 */

$baseDir = dirname(__DIR__);

// Mapeamento de caracteres corrompidos para corretos
$replacements = [
    // Dupla corrupo (UTF-8 duplamente codificado)
    'Autentica§£o' => 'Autenticao',
    'â"â¤' => '',
    'â"º' => '',
    'â"º' => '',

    // Corrupo simples
    'ã' => '',
    'ç' => '',
    'ê' => '',
    'ó' => '',
    'ú' => '',
    'í' => '',
    'á' => '',
    'é' => '',
    '' => '',
    'õ' => '',
    'â' => '',
    'ô' => '',

    // Palavras especficas com problemas
    'Autenticação' => 'Autenticao',
    'Validação' => 'Validao',
    'Validações' => 'Validaes',
    'definição' => 'definio',
    'Definição' => 'Definio',
    'descrição' => 'descrio',
    'Descrição' => 'Descrio',
    'dependência' => 'dependncia',
    'Dependência' => 'Dependncia',
    'dependências' => 'dependncias',
    'Dependências' => 'Dependncias',
    'pág' => 'pg',
    'página' => 'pgina',
    'Página' => 'Pgina',
    'exclusão' => 'excluso',
    'Exclusão' => 'Excluso',
    'botões' => 'botes',
    'Botões' => 'Botes',
    'exibição' => 'exibio',
    'Exibição' => 'Exibio',
    'edição' => 'edio',
    'Edição' => 'Edio',
    'opções' => 'opes',
    'Opções' => 'Opes',
    'opção' => 'opo',
    'Opção' => 'Opo',
    'Função' => 'Funo',
    'função' => 'funo',
    'última' => 'ltima',
    'último' => 'ltimo',
    'Avançados' => 'Avanados',
    'avançados' => 'avanados',
    'disponíveis' => 'disponveis',
    'Disponíveis' => 'Disponveis',
    'formulário' => 'formulrio',
    'Formulário' => 'Formulrio',
    'obrigatório' => 'obrigatrio',
    'Obrigatório' => 'Obrigatrio',
    'obrigatória' => 'obrigatria',
    'parâmetros' => 'parmetros',
    'Parâmetros' => 'Parmetros',
    'parâmetro' => 'parmetro',
    'Usuário' => 'Usurio',
    'usuário' => 'usurio',
    'usuários' => 'usurios',
    'Usuários' => 'Usurios',
    'Método' => 'Mtodo',
    'método' => 'mtodo',
    'cônjuge' => 'cnjuge',
    'Cônjuge' => 'Cnjuge',
    'Endereço' => 'Endereo',
    'endereço' => 'endereo',
    'básico' => 'bsico',
    'básicas' => 'bsicas',
    'público' => 'pblico',
    'Público' => 'Pblico',
    'não' => 'no',
    'Não' => 'No',
    'são' => 'so',
    'São' => 'So',
    'já' => 'j',
    'Já' => 'J',
    'há' => 'h',
    'Há' => 'H',
    'dígito' => 'dgito',
    'hífen' => 'hfen',
    'mantém' => 'mantm',
    'após' => 'aps',
    'Após' => 'Aps',
    'múltiplos' => 'mltiplos',
    'será' => 'ser',
    'padrão' => 'padro',
    'Padrão' => 'Padro',
    'inválida' => 'invlida',
    'Inválida' => 'Invlida',
    'inválido' => 'invlido',
    'específica' => 'especfica',
    'Relatório' => 'Relatrio',
    'relatório' => 'relatrio',
    'Relatórios' => 'Relatrios',
    'variáveis' => 'variveis',
    'Ação' => 'Ao',
    'ação' => 'ao',
    'informação' => 'informao',
    'Informação' => 'Informao',
    'validação' => 'validao',
    'correção' => 'correo',
    'Correção' => 'Correo',
    'autenticação' => 'autenticao',
    'condição' => 'condio',
    'Condição' => 'Condio',
    'PAGINAO' => 'PAGINAO',

    // Caracteres especiais em HTML comments e strings (no CSS)
    'BOTO' => 'BOTO',
    'EXCLUSO' => 'EXCLUSO',
];

// Encontrar todos os arquivos PHP (exceto vendor e __legacy)
$iterator = new RecursiveIteratorIterator(
    new RecursiveCallbackFilterIterator(
        new RecursiveDirectoryIterator($baseDir, RecursiveDirectoryIterator::SKIP_DOTS),
        function ($file, $key, $iterator) {
            $path = $file->getPathname();
            // Excluir vendor, __legacy, e o prprio script
            if (
                strpos($path, '/vendor/') !== false ||
                strpos($path, '/__legacy') !== false ||
                strpos($path, '__pending_review__') !== false ||
                basename($path) === 'fix_encoding_all.php'
            ) {
                return false;
            }
            return $file->isDir() || $file->getExtension() === 'php';
        }
    )
);

$fixedFiles = [];
$totalReplacements = 0;

foreach ($iterator as $file) {
    if ($file->isFile() && $file->getExtension() === 'php') {
        $content = file_get_contents($file->getPathname());
        $originalContent = $content;

        $fileReplacements = 0;

        // Aplicar substituies
        foreach ($replacements as $bad => $good) {
            $count = 0;
            $content = str_replace($bad, $good, $content, $count);
            $fileReplacements += $count;
        }

        // Se houve alteraes, salvar
        if ($content !== $originalContent) {
            file_put_contents($file->getPathname(), $content);
            $relativePath = str_replace($baseDir . '/', '', $file->getPathname());
            $fixedFiles[] = $relativePath . " ($fileReplacements substituies)";
            $totalReplacements += $fileReplacements;
        }
    }
}

echo "=== Correo de Encoding Concluda ===\n\n";
echo "Total de arquivos corrigidos: " . count($fixedFiles) . "\n";
echo "Total de substituies: $totalReplacements\n\n";

if (!empty($fixedFiles)) {
    echo "Arquivos modificados:\n";
    foreach ($fixedFiles as $f) {
        echo "  - $f\n";
    }
}
