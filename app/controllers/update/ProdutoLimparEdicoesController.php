<?php
// Endpoint AJAX para limpar edições de um produto (marca editado=0, limpar campos editados, imprimir=0)
require_once dirname(__DIR__, 3) . '/bootstrap.php';

// Apenas POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'MÉTODO NÃO PERMITIDO']);
    exit;
}

$id_produto = $_POST['produto_id'] ?? null;
$comum_id = $_POST['comum_id'] ?? null;

if (!$id_produto) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'PARÂMETRO PRODUTO_ID AUSENTE']);
    exit;
}

try {
    $sql = "UPDATE produtos SET editado_tipo_bem_id = 0, editado_bem = '', editado_complemento = '', editado_dependencia_id = 0, editado_descricao_completa = '', editado = 0, imprimir = 0 WHERE id = :produto_id";
    $stmt = $conexao->prepare($sql);
    $stmt->bindValue(':produto_id', (int)$id_produto, PDO::PARAM_INT);
    $stmt->execute();

    echo json_encode(['success' => true, 'message' => 'EDIÇÕES LIMPAS COM SUCESSO']);
    exit;
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'ERRO AO LIMPAR EDIÇÕES: ' . $e->getMessage()]);
    exit;
}
?>