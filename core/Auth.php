<?php

declare(strict_types=1);

namespace App\Core;

final class Auth
{
    public static function userId(): ?int
    {
        return isset($_SESSION['user_id']) ? (int) $_SESSION['user_id'] : null;
    }

    public static function login(int $userId, string $roleName = 'customer'): void
    {
        session_regenerate_id(true);
        $_SESSION['user_id'] = $userId;
        $_SESSION['role_name'] = $roleName;
    }

    public static function logout(): void
    {
        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
        }
        session_destroy();
    }

    public static function check(): bool
    {
        return self::userId() !== null;
    }

    public static function isAdmin(): bool
    {
        return self::check() && ($_SESSION['role_name'] ?? null) === 'admin';
    }
}
