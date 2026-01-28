<?php

namespace App\Views\Notificacoes;

use App\Core\Configuracoes;

class VersaoClientes
{
    private static function escapeMarkdown(string $text): string
    {
        $escapeChars = ['\\', '*', '_', '[', ']', '(', ')', '`'];
        foreach ($escapeChars as $ch) {
            $text = str_replace($ch, '\\' . $ch, $text);
        }
        return $text;
    }

    public static function render(array $grupos): string
    {
        $versaoAtual = trim((string) Configuracoes::pegar('versao'));
        $lines = [];

        $lines[] = '*📌 CLIENTES DESATUALIZADOS*';
        $lines[] = '';
        $lines[] = "⬆️ Versão atual: *{$versaoAtual}*";
        $lines[] = '';

        foreach ($grupos as $versao => $nomes) {
            $lines[] = "Versão: {$versao}";
            foreach ($nomes as $nome) {
                $nomeEsc = self::escapeMarkdown($nome);
                $lines[] = "  • {$nomeEsc}";
            }
            $lines[] = '';
        }

        if (end($lines) === '') {
            array_pop($lines);
        }

        return implode("\n", $lines);
    }
}
