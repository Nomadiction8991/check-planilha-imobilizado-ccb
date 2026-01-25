<?php

/**
 * Script para remover verificaes de permisso isAdmin/isDoador
 * e corrigir encoding UTF-8 corrompido em todos os arquivos PHP
 */

$baseDir = dirname(__DIR__);

// Arquivo planilha_visualizar.php - remover if (isAdmin()) no menu
$file = $baseDir . '/app/views/planilhas/planilha_visualizar.php';
$content = file_get_contents($file);

// Substituio 1: Menu diferenciado -> Menu nico
$oldMenu = <<<'EOD'
// Menu diferenciado para Admin e Doador
$headerActions = '
    <div class="dropdown">
        <button class="btn-header-action" type="button" id="menuPlanilha" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="bi bi-list fs-5"></i>
        </button>
        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="menuPlanilha">';

// Aes - Apenas para Administrador/Acessor
if (isAdmin()) {
    $headerActions .= '
            <li>
                <a class="dropdown-item" href="../produtos/produtos_listar.php?comum_id=' . $comum_id . '">
                    <i class="bi bi-list-ul me-2"></i>' . htmlspecialchars(to_uppercase('Listagem de Produtos'), ENT_QUOTES, 'UTF-8') . '
                </a>
            </li>
            <li><hr class="dropdown-divider"></li>
            <li>
                <a class="dropdown-item" href="../planilhas/relatorio141_view.php?id=' . $comum_id . '&comum_id=' . $comum_id . '">
                    <i class="bi bi-file-earmark-pdf me-2"></i>' . htmlspecialchars(to_uppercase('Relatrio 14.1'), ENT_QUOTES, 'UTF-8') . '
                </a>
            </li>
            <li>
                <a class="dropdown-item" href="../planilhas/produto_copiar_etiquetas.php?id=' . $id_planilha . '&comum_id=' . $comum_id . '">
                    <i class="bi bi-tags me-2"></i>' . htmlspecialchars(to_uppercase('Copiar Etiquetas'), ENT_QUOTES, 'UTF-8') . '
                </a>
            </li>
            <li>
                <a class="dropdown-item" href="../planilhas/relatorio_imprimir_alteracao.php?id=' . $comum_id . '&comum_id=' . $comum_id . '">
                    <i class="bi bi-printer me-2"></i>' . htmlspecialchars(to_uppercase('Imprimir Alterao'), ENT_QUOTES, 'UTF-8') . '
                </a>
            </li>';
} else {
EOD;

$newMenu = <<<'EOD'
// Menu completo para todos os usurios
$headerActions = '
    <div class="dropdown">
        <button class="btn-header-action" type="button" id="menuPlanilha" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="bi bi-list fs-5"></i>
        </button>
        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="menuPlanilha">';

// Todas as aes disponveis para todos os usurios
$headerActions .= '
            <li>
                <a class="dropdown-item" href="../produtos/produtos_listar.php?comum_id=' . $comum_id . '">
                    <i class="bi bi-list-ul me-2"></i>' . htmlspecialchars(to_uppercase('Listagem de Produtos'), ENT_QUOTES, 'UTF-8') . '
                </a>
            </li>
            <li><hr class="dropdown-divider"></li>
            <li>
                <a class="dropdown-item" href="../planilhas/relatorio141_view.php?id=' . $comum_id . '&comum_id=' . $comum_id . '">
                    <i class="bi bi-file-earmark-pdf me-2"></i>' . htmlspecialchars(to_uppercase('Relatrio 14.1'), ENT_QUOTES, 'UTF-8') . '
                </a>
            </li>
            <li>
                <a class="dropdown-item" href="../planilhas/produto_copiar_etiquetas.php?id=' . $id_planilha . '&comum_id=' . $comum_id . '">
                    <i class="bi bi-tags me-2"></i>' . htmlspecialchars(to_uppercase('Copiar Etiquetas'), ENT_QUOTES, 'UTF-8') . '
                </a>
            </li>
            <li>
                <a class="dropdown-item" href="../planilhas/relatorio_imprimir_alteracao.php?id=' . $comum_id . '&comum_id=' . $comum_id . '">
                    <i class="bi bi-printer me-2"></i>' . htmlspecialchars(to_uppercase('Imprimir Alterao'), ENT_QUOTES, 'UTF-8') . '
                </a>
            </li>';

// Menu nico - removido bloco else {
EOD;

$content = str_replace($oldMenu, $newMenu, $content);

// Remover o bloco else que sobrou
$content = preg_replace(
    '/\/\/ Menu nico - removido bloco else \{\s*\/\/ Doador\/.*?<\/li>\';[\r\n]+\}/s',
    '',
    $content
);

// Remover if (isAdmin()): nas aes de produto
$content = str_replace(
    '<!-- Aes - Apenas para Administrador/Acessor -->
                    <?php if (isAdmin()): ?>',
    '<!-- Aes de produto -->',
    $content
);

// Remover endif correspondente
$content = str_replace(
    '                    <?php endif; // fim do if isAdmin() 
                    ?>',
    '',
    $content
);

file_put_contents($file, $content);
echo "planilha_visualizar.php atualizado\n";

// Agora corrigir encoding em todos os arquivos
$encodingFixes = [
    'o' => 'o',
    '' => '',
    'es' => 'es',
    'rio' => 'rio',
    '' => '',
    '' => '',
    '' => '',
    '' => '',
    '' => '',
    '' => '',
    '' => '',
    '' => '',
    '' => '',
    '§£o' => 'o',
    'ªncia' => 'ncia',
    '£o' => 'o',
    '©' => '',
    '¡' => '',
    '­' => '',
    '³' => '',
    'º' => '',
    '§' => '',
    'No' => 'No',
    'no' => 'no',
    'descrio' => 'descrio',
    'validao' => 'validao',
    'autenticao' => 'autenticao',
    'configurao' => 'configurao',
    'observao' => 'observao',
    'observaes' => 'observaes',
    'dependncia' => 'dependncia',
    'obrigatrio' => 'obrigatrio',
    'obrigatria' => 'obrigatria',
    'relatrio' => 'relatrio',
    'formulrio' => 'formulrio',
    'variveis' => 'variveis',
    'parmetros' => 'parmetros',
    'invlidos' => 'invlidos',
    'usurio' => 'usurio',
    'pgina' => 'pgina',
    'transao' => 'transao',
    'clula' => 'clula',
    'ao' => 'ao',
    'funo' => 'funo',
    'opes' => 'opes',
    'opo' => 'opo',
    'disponveis' => 'disponveis',
    'especfica' => 'especfica',
    'so' => 'so',
    'h' => 'h',
    'autnomo' => 'autnomo',
    'elegvel' => 'elegvel',
    'excluda' => 'excluda',
    'mtodo' => 'mtodo',
    'padro' => 'padro',
    'sesso' => 'sesso',
    'verificao' => 'verificao',
    'inicializao' => 'inicializao',
    'informaes' => 'informaes',
    'exibio' => 'exibio',
    'excluso' => 'excluso',
    'localizao' => 'localizao',
    'bsicas' => 'bsicas',
    'botes' => 'botes',
    'recolhveis' => 'recolhveis',
    'Avanados' => 'Avanados',
    'Descrio' => 'Descrio',
    'condio' => 'condio',
    'Condio' => 'Condio',
    'Vlido' => 'Vlido',
    'vlido' => 'vlido',
    'autenticao' => 'autenticao',
    'Autenticao' => 'Autenticao',
    'AUTENTICAO' => 'AUTENTICAO',
    'Paginao' => 'Paginao',
    'Validao' => 'Validao',
    'ltima' => 'ltima',
    'cdigo' => 'cdigo',
    'Cdigo' => 'Cdigo',
    // Correes de encoding duplo/triplo
    'Cnjuge' => 'Cnjuge',
    'relat"rios' => 'relatrios',
    'Informa"es' => 'Informaes',
    'Paginao' => 'Paginao',
    'conte"do' => 'contedo',
    'boto' => 'boto',
];

// Diretrios para processar
$dirs = [
    $baseDir . '/app/controllers',
    $baseDir . '/app/views',
    $baseDir . '/app/services',
    $baseDir . '/app/helpers',
];

$count = 0;
foreach ($dirs as $dir) {
    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS)
    );

    foreach ($iterator as $file) {
        if ($file->getExtension() !== 'php') continue;

        $content = file_get_contents($file->getPathname());
        $original = $content;

        foreach ($encodingFixes as $bad => $good) {
            $content = str_replace($bad, $good, $content);
        }

        if ($content !== $original) {
            file_put_contents($file->getPathname(), $content);
            echo "Corrigido: " . $file->getPathname() . "\n";
            $count++;
        }
    }
}

echo "\n$count arquivos corrigidos.\n";
