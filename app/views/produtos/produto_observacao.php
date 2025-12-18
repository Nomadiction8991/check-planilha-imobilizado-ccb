<?php
require_once dirname(__DIR__, 2) . '/bootstrap.php';
// AUTENTICACAO
include __DIR__ . '/../../../app/controllers/update/ProdutoObservacaoController.php';

$pageTitle = to_uppercase('observacoes');
$backUrl = getReturnUrl($comum_id, $pagina, $filtro_nome, $filtro_dependencia, $filtro_codigo, $filtro_STATUS);

ob_start();
?>

<?php if (!empty($mensagem)): ?>
    <div class="alert alert-<?php echo $tipo_mensagem === 'success' ? 'success' : 'danger'; ?> alert-dismissible fade show">
        <?php echo htmlspecialchars($mensagem); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="card mb-3">
    <div class="card-header">
        <i class="bi bi-box-seam me-2"></i>
        <?php echo htmlspecialchars(to_uppercase('produto'), ENT_QUOTES, 'UTF-8'); ?>
    </div>
    <div class="card-body">
        <div class="row g-2 small">
            <div class="col-12"><?php echo htmlspecialchars($PRODUTO['codigo'] ?? ''); ?></div>
            <div class="col-12"><?php echo htmlspecialchars($PRODUTO['descricao_completa'] ?? ''); ?></div>
        </div>
        
        <div class="mt-2">
            <?php if ($check['checado'] == 1): ?>
                <span class="badge bg-success"><?php echo htmlspecialchars(to_uppercase('checado'), ENT_QUOTES, 'UTF-8'); ?></span>
            <?php endif; ?>
            <?php if (!empty($check['observacoes'])): ?>
                <span class="badge bg-warning text-dark"><?php echo htmlspecialchars(to_uppercase('com observação'), ENT_QUOTES, 'UTF-8'); ?></span>
            <?php endif; ?>
            <?php if ($check['imprimir'] == 1): ?>
                <span class="badge bg-info text-dark"><?php echo htmlspecialchars(to_uppercase('para imprimir'), ENT_QUOTES, 'UTF-8'); ?></span>
            <?php endif; ?>
        </div>
    </div>
</div>

<form method="POST">
    <input type="hidden" name="pagina" value="<?php echo $pagina; ?>">
    <input type="hidden" name="nome" value="<?php echo htmlspecialchars($filtro_nome); ?>">
    <input type="hidden" name="dependencia" value="<?php echo htmlspecialchars($filtro_dependencia); ?>">
    <input type="hidden" name="filtro_codigo" value="<?php echo htmlspecialchars($filtro_codigo); ?>">
    <input type="hidden" name="STATUS" value="<?php echo htmlspecialchars($filtro_STATUS); ?>">

    <div class="card mb-3">
        <div class="card-body">
            <label for="observacoes" class="form-label">
                <i class="bi bi-chat-square-text me-2"></i>
                <?php echo htmlspecialchars(to_uppercase('observações'), ENT_QUOTES, 'UTF-8'); ?>
            </label>
            <textarea class="form-control text-uppercase" id="observacoes" name="observacoes" rows="6" 
                      placeholder="<?php echo htmlspecialchars(to_uppercase('digite as observações do produto...'), ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars(to_uppercase($check['observacoes'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></textarea>
            <div class="form-text"><?php echo htmlspecialchars(to_uppercase('deixe em branco para remover as observações'), ENT_QUOTES, 'UTF-8'); ?></div>
        </div>
    </div>

    <button type="submit" class="btn btn-primary w-100">
        <i class="bi bi-save me-2"></i>
        <?php echo htmlspecialchars(to_uppercase('salvar observações'), ENT_QUOTES, 'UTF-8'); ?>
    </button>
</form>

<?php
$contentHtml = ob_get_clean();
$tempFile = __DIR__ . '/../../../temp_obs_PRODUTO_' . uniqid() . '.php';
file_put_contents($tempFile, $contentHtml);
$contentFile = $tempFile;
include __DIR__ . '/../layouts/app_wrapper.php';
unlink($tempFile);
?>
