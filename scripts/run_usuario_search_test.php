<?php
define('SKIP_AUTH', true);
$_GET['busca'] = 'wever';
$_GET['status'] = '1';
require_once __DIR__ . '/../app/controllers/read/UsuarioListController.php';

echo "TEST: busca=wever status=1\n";
echo "total_registros = " . ($total_registros ?? 'UNSET') . "\n";
echo "total_paginas = " . ($total_paginas ?? 'UNSET') . "\n";
echo "usuarios count = " . count($usuarios) . "\n";
foreach (array_slice($usuarios,0,3) as $u) {
    echo " - user: " . ($u['nome'] ?? $u['email'] ?? 'n/a') . "\n";
}
