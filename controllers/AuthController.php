<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Csrf;
use App\Models\User;
use PDOException;

final class AuthController extends Controller
{
    private User $users;

    public function __construct(\PDO $database)
    {
        $this->users = new User($database);
    }

    public function showLogin(): void
    {
        if (Auth::check()) {
            $this->redirect('/SS_NOVA_TECHNOLOGY/public/');
        }

        $this->view('auth/login', ['title' => 'Iniciar sesion']);
    }

    public function login(): void
    {
        Csrf::validate($_POST['_csrf'] ?? null);
        $email = strtolower(trim((string) ($_POST['email'] ?? '')));
        $password = (string) ($_POST['password'] ?? '');
        $user = $this->users->findActiveByEmail($email);

        if (!$user || !(bool) $user['is_active'] || !password_verify($password, $user['password_hash'])) {
            $this->view('auth/login', [
                'title' => 'Iniciar sesion',
                'error' => 'El correo o la contrasena no son correctos.',
                'email' => $email,
            ]);
            return;
        }

        Auth::login((int) $user['id'], (string) $user['role_name']);
        $this->users->updateLastLogin((int) $user['id']);
        $this->flash('Has iniciado sesion correctamente.');
        $this->redirect('/SS_NOVA_TECHNOLOGY/public/');
    }

    public function showRegister(): void
    {
        if (Auth::check()) {
            $this->redirect('/SS_NOVA_TECHNOLOGY/public/');
        }

        $this->view('auth/register', ['title' => 'Crear cuenta']);
    }

    public function register(): void
    {
        Csrf::validate($_POST['_csrf'] ?? null);
        $firstName = trim((string) ($_POST['first_name'] ?? ''));
        $lastName = trim((string) ($_POST['last_name'] ?? ''));
        $email = strtolower(trim((string) ($_POST['email'] ?? '')));
        $password = (string) ($_POST['password'] ?? '');
        $errors = [];

        if ($firstName === '' || mb_strlen($firstName) > 80) {
            $errors[] = 'Escribe un nombre valido.';
        }
        if ($lastName === '' || mb_strlen($lastName) > 80) {
            $errors[] = 'Escribe un apellido valido.';
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL) || mb_strlen($email) > 190) {
            $errors[] = 'Escribe un correo valido.';
        }
        if (strlen($password) < 8 || strlen($password) > 255) {
            $errors[] = 'La contrasena debe tener entre 8 y 255 caracteres.';
        }
        if ($this->users->findActiveByEmail($email)) {
            $errors[] = 'Ya existe una cuenta con ese correo.';
        }

        if ($errors !== []) {
            $this->view('auth/register', compact('errors', 'firstName', 'lastName', 'email') + ['title' => 'Crear cuenta']);
            return;
        }

        try {
            $userId = $this->users->createCustomer($firstName, $lastName, $email, password_hash($password, PASSWORD_DEFAULT));
        } catch (PDOException $exception) {
            $this->view('auth/register', [
                'title' => 'Crear cuenta',
                'errors' => ['No fue posible crear la cuenta. Verifica que el correo no este registrado.'],
                'firstName' => $firstName,
                'lastName' => $lastName,
                'email' => $email,
            ]);
            return;
        }

        Auth::login($userId);
        $this->flash('Tu cuenta se ha registrado correctamente. Has iniciado sesion.');
        $this->redirect('/SS_NOVA_TECHNOLOGY/public/');
    }

    public function logout(): void
    {
        Csrf::validate($_POST['_csrf'] ?? null);
        Auth::logout();
        header('Location: /SS_NOVA_TECHNOLOGY/public/', true, 303);
        exit;
    }
}
