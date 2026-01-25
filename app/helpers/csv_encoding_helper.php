<?php

/**
 * Normaliza o contedo de um CSV para UTF-8 e remove BOM caso exista.
 *
 * @param string $filePath Caminho absoluto para o arquivo CSV
 */
function ip_normalizar_csv_encoding(string $filePath): void
{
    if (!is_file($filePath)) {
        return;
    }

    $conteudo = file_get_contents($filePath);
    if ($conteudo === false) {
        return;
    }

    if (strncmp($conteudo, "\xEF\xBB\xBF", 3) === 0) {
        $conteudo = substr($conteudo, 3);
    }

    $encodings = ['UTF-8', 'UTF-16LE', 'UTF-16BE', 'ISO-8859-1', 'Windows-1252'];
    $detectado = mb_detect_encoding($conteudo, $encodings, true) ?: 'UTF-8';

    if (strcasecmp($detectado, 'UTF-8') !== 0) {
        $conteudo = mb_convert_encoding($conteudo, 'UTF-8', $detectado);
    }

    file_put_contents($filePath, $conteudo, LOCK_EX);
}

/**
 * Tenta corrigir textos que estejam marcados com caracteres corrompidos (, ,  etc.).
 *
 * @param string|null $valor Texto original
 * @return string|null Texto depois da tentativa de correo
 */
function ip_fix_text_encoding(?string $valor): ?string
{
    if ($valor === null || $valor === '') {
        return $valor;
    }

    $melhor = $valor;
    $scoreMelhor = substr_count($valor, '');
    $encodings = ['Windows-1252', 'ISO-8859-1'];

    foreach ($encodings as $encoding) {
        $intermediario = @mb_convert_encoding($valor, $encoding, 'UTF-8');
        if ($intermediario === false) {
            continue;
        }
        $corrigido = @mb_convert_encoding($intermediario, 'UTF-8', $encoding);
        if ($corrigido === false) {
            continue;
        }

        $score = substr_count($corrigido, '');
        if ($score < $scoreMelhor || ($score === $scoreMelhor && $corrigido !== $melhor && preg_match('/[-]/u', $corrigido))) {
            $melhor = $corrigido;
            $scoreMelhor = $score;
        }
    }

    return $melhor;
}
