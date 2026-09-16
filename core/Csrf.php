<?php

declare(strict_types=1);

namespace App\Core;

use RuntimeException;

final class Csrf
{
    public static function token(): string
    {
        if (empty($_SESSION['_csrf'])) {
            $_SESSION['_csrf'] = bin2hex(random_bytes(32));
        }

        return $_SESSION['_csrf'];
    }

    public static function validate(?string $token): void
    {
        if (!is_string($token) || !hash_equals(self::token(), $token)) {
            throw new RuntimeException('La solicitud no es valida. Recarga la pagina e intenta nuevamente.');
        }
    }
}
