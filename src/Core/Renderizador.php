<?php

namespace App\Core;

class Renderizador
{
    public static function renderizar(string $arquivo, array $dados = []): string
    {
        extract($dados);
        ob_start();
        $caminhoView = __DIR__ . '/../Views/' . ltrim($arquivo, '/');
        include $caminhoView;
        $conteudo = ob_get_clean();

        $caminhoLayout = __DIR__ . '/../Views/layout.php';
        if (file_exists($caminhoLayout)) {
            ob_start();
            include $caminhoLayout;
            return ob_get_clean();
        }

        return $conteudo;
    }
}
