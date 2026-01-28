<?php

namespace App\Routes;

use App\Controllers\HomeControlador;

class MapaRotas
{
    public static function obter(): array
    {
        return [
            'GET /' => [HomeControlador::class, 'inicio'],
        ];
    }
}
