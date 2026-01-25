<?php
/**
 * Script de conversão AGRESSIVA para UTF-8 + UPPERCASE
 * Processa todos os arquivos PHP em app/views e aplica conversões
 */

$basePath = __DIR__ . '/../app/views';

// Lista de substituições com prioridade de encoding + uppercase
$replacements = [
    // ENCODING FIXES (muito comum)
    'Autenticação' => 'AUTENTICAÇÁO',
    'autenticação' => 'AUTENTICAÇÁO',
    'Autenticação' => 'AUTENTICAÇÁO',
    'autenticação' => 'AUTENTICAÇÁO',
    
    'Código' => 'CÓDIGO',
    'código' => 'CÓDIGO',
    'Código' => 'CÓDIGO',
    'código' => 'CÓDIGO',
    
    'Dependência' => 'DEPENDÊNCIA',
    'dependência' => 'DEPENDÊNCIA',
    'Dependência' => 'DEPENDÊNCIA',
    'dependência' => 'DEPENDÊNCIA',
    
    'Condição' => 'CONDIÇÁO',
    'condição' => 'CONDIÇÁO',
    'Condição' => 'CONDIÇÁO',
    'Condiçao' => 'CONDIÇÁO',
    
    'não' => 'NÁO',
    'Não' => 'NÁO',
    'não' => 'NÁO',
    
    'será' => 'SERÁ',
    'será' => 'SERÁ',
    'Será' => 'SERÁ',
    
    'incluído' => 'INCLUÍDO',
    'incluído' => 'INCLUÍDO',
    
    'descrição' => 'DESCRIÇÁO',
    'descrição' => 'DESCRIÇÁO',
    'Descrição' => 'DESCRIÇÁO',
    
    'função' => 'FUNÇÁO',
    'função' => 'FUNÇÁO',
    'Função' => 'FUNÇÁO',
    
    // UPPERCASE conversions (já tratados em alguns arquivos)
    'Dados Básicos' => 'DADOS BÁSICOS',
    'Dados básicos' => 'DADOS BÁSICOS',
    'Cadastrar Produto' => 'CADASTRAR PRODUTO',
    'cadastrar produto' => 'CADASTRAR PRODUTO',
    'Produtos' => 'PRODUTOS',
    'produtos' => 'PRODUTOS',
    'Produto' => 'PRODUTO',
    'produto' => 'PRODUTO',
    'Imprimir 14.1' => 'IMPRIMIR 14.1',
    'imprimir 14.1' => 'IMPRIMIR 14.1',
    'Selecione um tipo de bem' => 'SELECIONE UM TIPO DE BEM',
    'Primeiro selecione um tipo de bem' => 'PRIMEIRO SELECIONE UM TIPO DE BEM',
    'Selecione um bem' => 'SELECIONE UM BEM',
    'Selecione uma dependência' => 'SELECIONE UMA DEPENDÊNCIA',
    'Status' => 'STATUS',
    'status' => 'STATUS',
];

// Função para processar um arquivo
function processFile($filepath) {
    global $replacements;
    
    if (!is_file($filepath)) return false;
    
    $content = file_get_contents($filepath);
    $original = $content;
    
    // Aplicar replacements
    foreach ($replacements as $from => $to) {
        $content = str_replace($from, $to, $content);
    }
    
    // Se teve mudanças, salvar com UTF-8 explícito
    if ($content !== $original) {
        file_put_contents($filepath, $content, LOCK_EX);
        return true;
    }
    return false;
}

// Função recursiva para processar diretórios
function processDirectory($dir) {
    $count = 0;
    $files = @scandir($dir);
    
    if ($files === false) return 0;
    
    foreach ($files as $file) {
        if ($file === '.' || $file === '..') continue;
        
        $filepath = $dir . '/' . $file;
        
        if (is_dir($filepath)) {
            $count += processDirectory($filepath);
        } elseif (pathinfo($filepath, PATHINFO_EXTENSION) === 'php') {
            // Pular certos padrões
            if (strpos($filepath, 'shared') !== false || strpos($filepath, 'layouts') !== false) {
                continue;
            }
            
            if (processFile($filepath)) {
                $count++;
                echo "✅ " . str_replace(dirname(dirname(__DIR__)), '', $filepath) . "\n";
            }
        }
    }
    
    return $count;
}

// Executar
$totalCount = processDirectory($basePath);
echo "\n🎉 Total de arquivos com mudanças: $totalCount\n";
?>
