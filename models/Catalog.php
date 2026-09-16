<?php

declare(strict_types=1);

namespace App\Models;

use PDO;

final class Catalog
{
    public function __construct(private PDO $database) {}

    public function categories(): array { return $this->database->query('SELECT id, name FROM categories WHERE is_active = 1 ORDER BY name')->fetchAll(); }
    public function products(): array
    {
        return $this->database->query(
                'SELECT p.*, c.name AS category_name, COALESCE(i.quantity_available, 0) AS stock,
                    pi.file_path AS image_path
             FROM products p INNER JOIN categories c ON c.id = p.category_id
             LEFT JOIN inventory i ON i.product_id = p.id
               LEFT JOIN product_images pi ON pi.product_id = p.id AND pi.is_primary = 1
             ORDER BY p.created_at DESC'
        )->fetchAll();
    }
    public function product(int $id): ?array
    {
        $statement = $this->database->prepare('SELECT p.*, COALESCE(i.quantity_available, 0) AS stock, pi.file_path AS image_path FROM products p LEFT JOIN inventory i ON i.product_id = p.id LEFT JOIN product_images pi ON pi.product_id = p.id AND pi.is_primary = 1 WHERE p.id = :id');
        $statement->execute(['id' => $id]);
        $product = $statement->fetch();
        return $product ?: null;
    }
    public function createProduct(array $data): void
    {
        $this->database->beginTransaction();
        try {
            $statement = $this->database->prepare('INSERT INTO products (category_id, name, slug, sku, description, price, is_active) VALUES (:category_id, :name, :slug, :sku, :description, :price, :is_active)');
            $statement->execute([
                'category_id' => $data['category_id'],
                'name' => $data['name'],
                'slug' => $this->internalSlug($data['name']),
                'sku' => 'PROD-' . strtoupper(bin2hex(random_bytes(4))),
                'description' => $data['description'] ?: null,
                'price' => $data['price'],
                'is_active' => $data['is_active'],
            ]);
            $productId = (int) $this->database->lastInsertId();
            $inventory = $this->database->prepare('INSERT INTO inventory (product_id, quantity_available) VALUES (:id, :stock)');
            $inventory->execute(['id' => $productId, 'stock' => $data['stock']]);
            $this->saveProductImage($productId, $data['image_path'] ?? null, $data['name']);
            $this->database->commit();
        } catch (\Throwable $exception) {
            if ($this->database->inTransaction()) $this->database->rollBack();
            throw $exception;
        }
    }
    public function updateProduct(int $id, array $data): void
    {
        $this->database->beginTransaction();
        try {
            $statement = $this->database->prepare('UPDATE products SET category_id = :category_id, name = :name, description = :description, price = :price, is_active = :is_active WHERE id = :id');
            $statement->execute(['id' => $id, 'category_id' => $data['category_id'], 'name' => $data['name'], 'description' => $data['description'] ?: null, 'price' => $data['price'], 'is_active' => $data['is_active']]);
            $inventory = $this->database->prepare('INSERT INTO inventory (product_id, quantity_available) VALUES (:id, :stock) ON DUPLICATE KEY UPDATE quantity_available = :stock_update');
            $inventory->execute(['id' => $id, 'stock' => $data['stock'], 'stock_update' => $data['stock']]);
            $this->saveProductImage($id, $data['image_path'] ?? null, $data['name']);
            $this->database->commit();
        } catch (\Throwable $exception) {
            if ($this->database->inTransaction()) $this->database->rollBack();
            throw $exception;
        }
    }
    public function deleteProduct(int $id): void { $this->database->prepare('UPDATE products SET is_active = 0 WHERE id = :id')->execute(['id' => $id]); }
    public function toggleProduct(int $id, bool $active): void { $this->database->prepare('UPDATE products SET is_active = :active WHERE id = :id')->execute(['id' => $id, 'active' => $active ? 1 : 0]); }
    public function suppliers(): array { return $this->database->query('SELECT * FROM suppliers ORDER BY created_at DESC')->fetchAll(); }
    public function supplier(int $id): ?array { $statement = $this->database->prepare('SELECT * FROM suppliers WHERE id = :id'); $statement->execute(['id' => $id]); $supplier = $statement->fetch(); return $supplier ?: null; }
    public function createSupplier(array $data): void
    {
        $this->database->prepare(
            'INSERT INTO suppliers (name, contact_name, email, phone, address)
             VALUES (:name, :contact_name, :email, :phone, :address)'
        )->execute([
            'name' => $data['name'],
            'contact_name' => $data['contact_name'] ?: null,
            'email' => $data['email'] ?: null,
            'phone' => $data['phone'] ?: null,
            'address' => $data['address'] ?: null,
        ]);
    }
    public function updateSupplier(int $id, array $data): void
    {
        $this->database->prepare(
            'UPDATE suppliers SET name = :name, contact_name = :contact_name,
             email = :email, phone = :phone, address = :address,
             is_active = :is_active WHERE id = :id'
        )->execute([
            'id' => $id,
            'name' => $data['name'],
            'contact_name' => $data['contact_name'] ?: null,
            'email' => $data['email'] ?: null,
            'phone' => $data['phone'] ?: null,
            'address' => $data['address'] ?: null,
            'is_active' => $data['is_active'],
        ]);
    }
    public function toggleSupplier(int $id, bool $active): void { $this->database->prepare('UPDATE suppliers SET is_active = :active WHERE id = :id')->execute(['id' => $id, 'active' => $active ? 1 : 0]); }
    public function deleteSupplier(int $id): void { $this->toggleSupplier($id, false); }

    private function internalSlug(string $name): string
    {
        $slug = strtolower(trim((string) preg_replace('/[^a-zA-Z0-9]+/', '-', $name), '-'));
        return ($slug !== '' ? $slug : 'producto') . '-' . bin2hex(random_bytes(4));
    }

    private function saveProductImage(int $productId, ?string $imagePath, string $productName): void
    {
        $imagePath = trim((string) $imagePath);
        $this->database->prepare('DELETE FROM product_images WHERE product_id = :product_id AND is_primary = 1')->execute(['product_id' => $productId]);
        if ($imagePath === '') return;
        $this->database->prepare(
            'INSERT INTO product_images (product_id, file_path, alt_text, is_primary)
             VALUES (:product_id, :file_path, :alt_text, 1)'
        )->execute(['product_id' => $productId, 'file_path' => $imagePath, 'alt_text' => $productName]);
    }
}