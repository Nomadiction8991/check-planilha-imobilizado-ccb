<?php

if (!defined('SKIP_AUTH')) {
    define('SKIP_AUTH', true);
}
require_once __DIR__ . '/../app/bootstrap.php';

/**
 * Corrige os textos armazenados no banco que exibem caracteres corrompidos
 * ao serem renderizados (p.ex. "ADMINISTRAO" ou "DORMITRIO").
 */
$tables = [
    ['name' => 'dependencias', 'pk' => 'id', 'cols' => ['descricao']],
    ['name' => 'comums', 'pk' => 'id', 'cols' => ['descricao', 'administracao', 'cidade']],
    ['name' => 'tipos_bens', 'pk' => 'id', 'cols' => ['descricao']],
    ['name' => 'usuarios', 'pk' => 'id', 'cols' => ['nome', 'assinatura', 'nome_conjuge', 'assinatura_conjuge', 'endereco_logradouro', 'endereco_bairro', 'endereco_cidade']],
    ['name' => 'produtos', 'pk' => 'id_produto', 'cols' => ['descricao_completa', 'bem', 'complemento', 'observacao']],
];

$conexao->beginTransaction();
try {
    foreach ($tables as $entry) {
        $table = $entry['name'];
        $pk = $entry['pk'];
        $cols = $entry['cols'];

        $quotedCols = array_map(static fn($column) => "`$column`", $cols);
        $select = sprintf('SELECT `%s`, %s FROM `%s`', $pk, implode(', ', $quotedCols), $table);
        $stmt = $conexao->prepare($select);
        $stmt->execute();

        $alterados = 0;
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $changes = [];
            foreach ($cols as $col) {
                $original = $row[$col];
                $corrigido = ip_fix_text_encoding($original);
                if ($corrigido !== $original) {
                    $changes[$col] = $corrigido;
                }
            }
            if (empty($changes)) {
                continue;
            }

            $sets = [];
            $params = [':pk' => $row[$pk]];
            foreach ($changes as $col => $value) {
                $param = ':col_' . $col;
                $sets[] = sprintf('`%s` = %s', $col, $param);
                $params[$param] = $value;
            }
            $sql = sprintf('UPDATE `%s` SET %s WHERE `%s` = :pk', $table, implode(', ', $sets), $pk);
            $update = $conexao->prepare($sql);
            foreach ($params as $key => $value) {
                $update->bindValue($key, $value);
            }
            $update->execute();
            $alterados++;
        }

        echo sprintf("Tabela %s: %d linhas corrigidas\n", $table, $alterados);
    }

    $conexao->commit();
    echo "Correo concluda.\n";
} catch (Throwable $e) {
    $conexao->rollBack();
    echo "Erro: " . $e->getMessage() . "\n";
    exit(1);
}
