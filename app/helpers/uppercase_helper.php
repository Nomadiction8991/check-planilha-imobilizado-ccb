<?php
/**
 * Helper para converter strings para UPPERCASE
 * Centraliza toda a lógica de conversão para UPPERCASE no sistema
 */

/**
 * Converte um campo para UPPERCASE
 * 
 * @param string $value O valor a ser convertido
 * @param array $campos_excluir Lista de campos que NÃO devem ser convertidos (ex: email, senha, CPF)
 * @return string O valor em UPPERCASE
 */
function to_uppercase($value, $exclude_fields = []) {
    if (empty($value) || !is_string($value)) {
        return $value;
    }
    return mb_strtoupper($value, 'UTF-8');
}

/**
 * Converte múltiplos campos de um array para UPPERCASE
 * 
 * @param array $data Array com dados
 * @param array $fields_to_convert Lista de campos que devem ser convertidos
 * @return array Array com campos convertidos
 */
function uppercase_fields(&$data, $fields_to_convert = []) {
    foreach ($fields_to_convert as $field) {
        if (isset($data[$field]) && is_string($data[$field])) {
            $data[$field] = to_uppercase($data[$field]);
        }
    }
    return $data;
}

/**
 * Campos que devem ser salvos em UPPERCASE no banco de dados
 * Por modelo/tabela
 */
function get_uppercase_fields($table = null) {
    $fields_by_table = [
        'usuarios' => [
            'nome',
            'tipo',
            'assinatura',
            'nome_conjuge',
            'assinatura_conjuge',
            'endereco_logradouro',
            'endereco_numero',
            'endereco_complemento',
            'endereco_bairro',
            'endereco_cidade',
            'endereco_estado'
        ],
        'comuns' => [
            'descricao',
            'administracao',
            'cidade'
        ],
        'dependencias' => [
            'descricao'
        ],
        'produtos' => [
            'descricao',
            'tipo',
            'marca',
            'modelo',
            'numero_serie',
            'cor',
            'especificacoes'
        ]
    ];
    
    if ($table && isset($fields_by_table[$table])) {
        return $fields_by_table[$table];
    }
    
    // Retornar todos se não especificar tabela
    $all = [];
    foreach ($fields_by_table as $fields) {
        $all = array_merge($all, $fields);
    }
    return $all;
}

?>
