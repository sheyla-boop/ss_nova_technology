<?php

declare(strict_types=1);

namespace App\Core;

abstract class Controller
{
    protected function flash(string $message, string $type = 'success'): void
    {
        $_SESSION['flash'] = [
            'message' => $message,
            'type' => $type,
        ];
    }

    protected function redirect(string $path): never
    {
        header('Location: ' . $path, true, 303);
        exit;
    }

    protected function view(string $view, array $data = []): void
    {
        $viewFile = dirname(__DIR__) . '/views/' . $view . '.php';

        if (!is_readable($viewFile)) {
            http_response_code(500);
            echo 'Vista no disponible.';
            return;
        }

        $data['csrfToken'] = $data['csrfToken'] ?? \App\Core\Csrf::token();
        $data['flash'] = $data['flash'] ?? $_SESSION['flash'] ?? null;
        unset($_SESSION['flash']);
        extract($data, EXTR_SKIP);
        require dirname(__DIR__) . '/views/layouts/main.php';
    }
}
