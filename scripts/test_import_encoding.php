<?php

if (!defined('SKIP_AUTH')) {
    define('SKIP_AUTH', true);
}
require_once __DIR__ . '/../app/bootstrap.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

$arquivoTeste = sys_get_temp_dir() . '/checkplanilha_encoding_test.csv';
$linhas = [
    ['codigo', 'dependencia'],
    ['001', 'ADMINISTRAÇÁO'],
    ['002', 'DORMITÓRIO'],
];

$conteudo = implode("\n", array_map(fn($linha) => implode(';', $linha), $linhas));
file_put_contents($arquivoTeste, mb_convert_encoding($conteudo, 'ISO-8859-1', 'UTF-8'));
ip_normalizar_csv_encoding($arquivoTeste);

$planilha = IOFactory::load($arquivoTeste);
$aba = $planilha->getActiveSheet();
$dependencia1 = (string) $aba->getCell('B2')->getValue();
$dependencia2 = (string) $aba->getCell('B3')->getValue();

@unlink($arquivoTeste);

$erro = '';
if ($dependencia1 !== 'ADMINISTRAÇÁO' || $dependencia2 !== 'DORMITÓRIO') {
    $erro = sprintf('Dependências lidas: "%s", "%s"', $dependencia1, $dependencia2);
    fwrite(STDERR, "Falha no teste de encoding: {$erro}\n");
    exit(1);
}

echo "Teste de importação UTF-8 bem-sucedido.\n";
