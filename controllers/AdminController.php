<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Csrf;
use App\Models\User;
use PDOException;

final class AdminController extends Controller
{
    private User $users;

    public function __construct(\PDO $database) { $this->users = new User($database); }

    public function index(): void
    {
        if (!$this->ensureAdmin()) return;
        $this->view('admin/users/index', ['title' => 'Usuarios', 'users' => $this->users->all()]);
    }

    public function clients(): void
    {
        if (!$this->ensureAdmin()) return;
        $this->view('admin/users/index', ['title' => 'Clientes', 'users' => $this->users->customers(), 'clients' => true]);
    }

    public function create(): void
    {
        if (!$this->ensureAdmin()) return;
        $this->view('admin/users/form', ['title' => 'Nuevo usuario', 'roles' => $this->users->roles(), 'user' => null]);
    }

    public function store(): void
    {
        if (!$this->ensureAdmin()) return;
        Csrf::validate($_POST['_csrf'] ?? null);
        $data = $this->validatedData();
        $password = (string) ($_POST['password'] ?? '');
        $errors = $this->validateData($data, $password, true);
        if ($errors !== []) { $this->formWithErrors($data, $errors, 'Nuevo usuario'); return; }
        try { $this->users->createAdminUser($data, password_hash($password, PASSWORD_DEFAULT)); }
        catch (PDOException) { $this->formWithErrors($data, ['El correo ya esta registrado o los datos no son validos.'], 'Nuevo usuario'); return; }
        $this->flash('Usuario creado correctamente.');
        $this->redirect('/SS_NOVA_TECHNOLOGY/public/admin/users');
    }

    public function edit(): void
    {
        if (!$this->ensureAdmin()) return;
        $user = $this->users->find((int) ($_GET['id'] ?? 0));
        if ($user === null) { http_response_code(404); echo 'Usuario no encontrado.'; return; }
        $this->view('admin/users/form', ['title' => 'Editar usuario', 'roles' => $this->users->roles(), 'user' => $user]);
    }

    public function update(): void
    {
        if (!$this->ensureAdmin()) return;
        Csrf::validate($_POST['_csrf'] ?? null);
        $id = (int) ($_POST['id'] ?? 0);
        $data = $this->validatedData();
        $password = (string) ($_POST['password'] ?? '');
        $errors = $this->validateData($data, $password, false);
        if ($errors !== []) { $this->formWithErrors(array_merge($data, ['id' => $id]), $errors, 'Editar usuario'); return; }
        try { $this->users->updateAdmin($id, $data, $password !== '' ? password_hash($password, PASSWORD_DEFAULT) : null); }
        catch (PDOException) { $this->formWithErrors(array_merge($data, ['id' => $id]), ['El correo ya esta registrado o los datos no son validos.'], 'Editar usuario'); return; }
        $this->flash('Usuario actualizado correctamente.');
        $this->redirect('/SS_NOVA_TECHNOLOGY/public/admin/users');
    }

    public function toggle(): void
    {
        if (!$this->ensureAdmin()) return;
        Csrf::validate($_POST['_csrf'] ?? null);
        $id = (int) ($_POST['id'] ?? 0);
        if ($id === Auth::userId()) { $this->flash('No puedes desactivar tu propia cuenta.', 'error'); $this->redirect('/SS_NOVA_TECHNOLOGY/public/admin/users'); }
        $user = $this->users->find($id);
        if ($user !== null) { $this->users->setActive($id, !(bool) $user['is_active']); $this->flash('Estado del usuario actualizado.'); }
        $this->redirect('/SS_NOVA_TECHNOLOGY/public/admin/users');
    }

    public function delete(): void
    {
        if (!$this->ensureAdmin()) return;
        Csrf::validate($_POST['_csrf'] ?? null);
        $id = (int) ($_POST['id'] ?? 0);
        if ($id === Auth::userId()) {
            $this->flash('No puedes eliminar tu propia cuenta.', 'error');
            $this->redirect('/SS_NOVA_TECHNOLOGY/public/admin/users');
        }
        $this->users->delete($id);
        $this->flash('Usuario eliminado correctamente.');
        $this->redirect('/SS_NOVA_TECHNOLOGY/public/admin/users');
    }

    private function ensureAdmin(): bool
    {
        if (!Auth::check()) $this->redirect('/SS_NOVA_TECHNOLOGY/public/login');
        if (!Auth::userId() || !$this->users->isAdmin(Auth::userId())) { http_response_code(403); echo 'No tienes permisos para acceder a esta seccion.'; return false; }
        return true;
    }

    private function validatedData(): array
    {
        return ['role_id' => (int) ($_POST['role_id'] ?? 0), 'first_name' => trim((string) ($_POST['first_name'] ?? '')), 'last_name' => trim((string) ($_POST['last_name'] ?? '')), 'email' => strtolower(trim((string) ($_POST['email'] ?? ''))), 'phone' => trim((string) ($_POST['phone'] ?? ''))];
    }

    private function validateData(array $data, string $password, bool $required): array
    {
        $errors = [];
        if ($data['role_id'] < 1) $errors[] = 'Selecciona un rol.';
        if ($data['first_name'] === '' || mb_strlen($data['first_name']) > 80) $errors[] = 'Escribe un nombre valido.';
        if ($data['last_name'] === '' || mb_strlen($data['last_name']) > 80) $errors[] = 'Escribe un apellido valido.';
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL) || mb_strlen($data['email']) > 190) $errors[] = 'Escribe un correo valido.';
        if (($required && strlen($password) < 8) || strlen($password) > 255) $errors[] = 'La contrasena debe tener entre 8 y 255 caracteres.';
        if (!$required && $password !== '' && strlen($password) < 8) $errors[] = 'La nueva contrasena debe tener al menos 8 caracteres.';
        return $errors;
    }

    private function formWithErrors(array $data, array $errors, string $title): void
    {
        $this->view('admin/users/form', ['title' => $title, 'roles' => $this->users->roles(), 'user' => $data, 'errors' => $errors]);
    }
}