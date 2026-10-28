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

        $this->view('auth/login', ['title' => 'Iniciar sesion', 'role' => 'customer']);
    }

    public function login(): void
    {
        Csrf::validate($_POST['_csrf'] ?? null);
        $email = strtolower(trim((string) ($_POST['email'] ?? '')));
        $password = (string) ($_POST['password'] ?? '');
        $role = (string) ($_POST['role'] ?? 'customer');
        if (!in_array($role, ['customer', 'admin'], true)) {
            $role = 'customer';
        }
        $user = $this->users->findActiveByEmail($email, $role);

        if (!$user || !(bool) $user['is_active'] || !password_verify($password, $user['password_hash'])) {
            $this->view('auth/login', [
                'title' => 'Iniciar sesion',
                'error' => 'El correo o la contraseña no son correctos.',
                'email' => $email,
                'role' => $role,
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
        $role = (string) ($_POST['role'] ?? 'customer');
        $privacyAccepted = ($_POST['privacy_consent'] ?? '') === '1';
        $errors = [];

        if (!in_array($role, ['customer', 'admin'], true)) {
            $errors[] = 'Selecciona un rol válido.';
            $role = 'customer';
        }
        if ($firstName === '' || mb_strlen($firstName) > 80) {
            $errors[] = 'Escribe un nombre valido.';
        }
        if ($lastName === '' || mb_strlen($lastName) > 80) {
            $errors[] = 'Escribe un apellido valido.';
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL) || mb_strlen($email) > 190) {
            $errors[] = 'Escribe un correo valido.';
        }
        if (!User::passwordMeetsPolicy($password)) {
            $errors[] = 'La contraseña debe tener al menos 8 caracteres y contener al menos un signo o símbolo.';
        }
        if (!$privacyAccepted) {
            $errors[] = 'Debes leer y aceptar la política de tratamiento de datos para crear tu cuenta.';
        }
        if ($this->users->findActiveByEmail($email)) {
            $errors[] = 'Ya existe una cuenta con ese correo.';
        }

        if ($errors !== []) {
            $this->view('auth/register', compact('errors', 'firstName', 'lastName', 'email', 'privacyAccepted', 'role') + ['title' => 'Crear cuenta']);
            return;
        }

        try {
            $userId = $this->users->createAccount(
                $firstName,
                $lastName,
                $email,
                password_hash($password, PASSWORD_DEFAULT),
                User::PRIVACY_POLICY_VERSION,
                $role
            );
        } catch (PDOException $exception) {
            $this->view('auth/register', [
                'title' => 'Crear cuenta',
                'errors' => ['No fue posible crear la cuenta. Verifica que el correo no esté registrado.'],
                'firstName' => $firstName,
                'lastName' => $lastName,
                'email' => $email,
                'privacyAccepted' => $privacyAccepted,
                'role' => $role,
            ]);
            return;
        }

        Auth::login($userId, $role);
        $this->flash('Tu cuenta se ha registrado correctamente. Has iniciado sesión.');
        $this->redirect('/SS_NOVA_TECHNOLOGY/public/');
    }

    public function showPrivacyPolicy(): void
    {
        $config = require dirname(__DIR__) . '/config/config.php';
        $this->view('legal/privacy-policy', [
            'title' => 'Política de tratamiento de datos',
            'support' => $config['support'],
            'policyVersion' => User::PRIVACY_POLICY_VERSION,
        ]);
    }

    public function showPasswordPolicy(): void
    {
        $this->view('legal/password-policy', ['title' => 'Política de contraseñas']);
    }

    public function logout(): void
    {
        Csrf::validate($_POST['_csrf'] ?? null);
        Auth::logout();
        header('Location: /SS_NOVA_TECHNOLOGY/public/', true, 303);
        exit;
    }
}
