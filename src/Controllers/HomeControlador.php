<?php

namespace App\Controllers;

use App\Core\Configuracoes;
use App\Core\Renderizador;

class HomeControlador
{
    public function inicio()
    {
        return Renderizador::renderizar('home.php', [
            'titulo' => Configuracoes::pegar('titulo_site'),
            'usuario' => 'Visitante',
        ]);
    }
}
