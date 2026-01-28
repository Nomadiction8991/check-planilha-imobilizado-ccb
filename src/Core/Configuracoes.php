<?php

namespace App\Core;

class Configuracoes
{
    private static ?array $dados = null;

    public static function pegar(string $chave)
    {
        if (self::$dados === null) {
            self::$dados = require __DIR__ . '/../../config/app.php';
        }
        return self::$dados[$chave] ?? null;
    }

    public static function atualizar(string $chave, $valor): bool
    {
        $file = __DIR__ . '/../../config/app.php';

        $config = @include $file;
        if (!is_array($config)) {
            return false;
        }

        $config[$chave] = $valor;

        $linhas = ["<?php", "", "return ["];
        foreach ($config as $k => $v) {
            if ($v === null) {
                $linhas[] = "    '" . $k . "' => null,";
            } else {
                $linhas[] = "    '" . $k . "' => '" . addslashes((string) $v) . "',";
            }
        }
        $linhas[] = "];";
        $linhas[] = "";

        $export = implode("\n", $linhas);

        $tmp = $file . '.tmp';

        $written = @file_put_contents($tmp, $export, LOCK_EX);
        if ($written === false) {
            @unlink($tmp);
            return false;
        }

        if (!@rename($tmp, $file)) {
            @unlink($tmp);
            return false;
        }

        self::$dados = null;

        return true;
    }
}
