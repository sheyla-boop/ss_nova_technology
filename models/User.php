<?php

declare(strict_types=1);

namespace App\Models;

use PDO;

final class User
{
    public function __construct(private PDO $database)
    {
    }

    public function findActiveByEmail(string $email): ?array
    {
        $statement = $this->database->prepare(
                'SELECT u.id, u.role_id, r.name AS role_name, u.first_name, u.last_name,
                    u.email, u.password_hash, u.is_active
                 FROM users u INNER JOIN roles r ON r.id = u.role_id
                 WHERE u.email = :email LIMIT 1'
        );
        $statement->execute(['email' => $email]);
        $user = $statement->fetch();

        return $user ?: null;
    }

    public function all(): array
    {
        return $this->database->query(
            'SELECT u.id, u.first_name, u.last_name, u.email, u.is_active,
                    u.created_at, r.name AS role_name, r.description AS role_description
             FROM users u INNER JOIN roles r ON r.id = u.role_id
             ORDER BY u.created_at DESC'
        )->fetchAll();
    }

    public function customers(): array
    {
        $statement = $this->database->prepare(
            'SELECT u.id, u.first_name, u.last_name, u.email, u.phone, u.is_active,
                    u.created_at, r.description AS role_description
             FROM users u INNER JOIN roles r ON r.id = u.role_id
             WHERE r.name = :role ORDER BY u.created_at DESC'
        );
        $statement->execute(['role' => 'customer']);
        return $statement->fetchAll();
    }

    public function isAdmin(int $id): bool
    {
        $statement = $this->database->prepare(
            'SELECT 1 FROM users u INNER JOIN roles r ON r.id = u.role_id
             WHERE u.id = :id AND u.is_active = 1 AND r.name = :role LIMIT 1'
        );
        $statement->execute(['id' => $id, 'role' => 'admin']);
        return $statement->fetchColumn() !== false;
    }

    public function find(int $id): ?array
    {
        $statement = $this->database->prepare(
            'SELECT id, role_id, first_name, last_name, email, phone, is_active
             FROM users WHERE id = :id LIMIT 1'
        );
        $statement->execute(['id' => $id]);
        $user = $statement->fetch();
        return $user ?: null;
    }

    public function roles(): array
    {
        return $this->database->query('SELECT id, name, description FROM roles ORDER BY name')->fetchAll();
    }

    public function createAdminUser(array $data, string $passwordHash): void
    {
        $statement = $this->database->prepare(
            'INSERT INTO users (role_id, first_name, last_name, email, password_hash, phone)
             VALUES (:role_id, :first_name, :last_name, :email, :password_hash, :phone)'
        );
        $statement->execute(['role_id' => $data['role_id'], 'first_name' => $data['first_name'], 'last_name' => $data['last_name'], 'email' => $data['email'], 'password_hash' => $passwordHash, 'phone' => $data['phone'] ?: null]);
    }

    public function updateAdmin(int $id, array $data, ?string $passwordHash): void
    {
        $fields = 'role_id = :role_id, first_name = :first_name, last_name = :last_name, email = :email, phone = :phone';
        if ($passwordHash !== null) $fields .= ', password_hash = :password_hash';
        $parameters = ['id' => $id, 'role_id' => $data['role_id'], 'first_name' => $data['first_name'], 'last_name' => $data['last_name'], 'email' => $data['email'], 'phone' => $data['phone'] ?: null];
        if ($passwordHash !== null) $parameters['password_hash'] = $passwordHash;
        $this->database->prepare("UPDATE users SET {$fields} WHERE id = :id")->execute($parameters);
    }

    public function setActive(int $id, bool $isActive): void
    {
        $this->database->prepare('UPDATE users SET is_active = :is_active WHERE id = :id')->execute(['id' => $id, 'is_active' => $isActive ? 1 : 0]);
    }

    public function delete(int $id): void
    {
        $this->setActive($id, false);
    }

    public function createCustomer(string $firstName, string $lastName, string $email, string $passwordHash): int
    {
        $roleStatement = $this->database->prepare("SELECT id FROM roles WHERE name = 'customer' LIMIT 1");
        $roleStatement->execute();
        $roleId = $roleStatement->fetchColumn();

        if ($roleId === false) {
            throw new \RuntimeException('El rol de cliente no esta configurado.');
        }

        $statement = $this->database->prepare(
            'INSERT INTO users (role_id, first_name, last_name, email, password_hash)
             VALUES (:role_id, :first_name, :last_name, :email, :password_hash)'
        );
        $statement->execute([
            'role_id' => $roleId,
            'first_name' => $firstName,
            'last_name' => $lastName,
            'email' => $email,
            'password_hash' => $passwordHash,
        ]);

        return (int) $this->database->lastInsertId();
    }

    public function updateLastLogin(int $userId): void
    {
        $statement = $this->database->prepare('UPDATE users SET last_login_at = NOW() WHERE id = :id');
        $statement->execute(['id' => $userId]);
    }
}
