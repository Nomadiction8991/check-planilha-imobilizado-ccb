<?php

namespace App\Core;

use PDO;
use PDOException;
use App\Core\LerEnv;

class Database
{
    private static ?PDO $conexao = null;

    public static function getConnection(): PDO
    {
        if (self::$conexao instanceof PDO) {
            return self::$conexao;
        }

        $host = LerEnv::obter('DB_HOST', '127.0.0.1');
        $database = LerEnv::obter('DB_DATABASE', 'ellobackup');
        $username = LerEnv::obter('DB_USERNAME', 'root');
        $password = LerEnv::obter('DB_PASSWORD', '');
        $charset = LerEnv::obter('DB_CHARSET', 'utf8mb4');
        $port = LerEnv::obter('DB_PORT', '3306');

        $dsn = sprintf('mysql:host=%s;port=%s;dbname=%s;charset=%s', $host, $port, $database, $charset);

        self::$conexao = new PDO($dsn, $username, $password, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);

        return self::$conexao;
    }
}
