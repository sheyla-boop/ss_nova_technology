<?php

declare(strict_types=1);

namespace App\Models;

use PDO;
use RuntimeException;

final class Storefront
{
    public function __construct(private PDO $database) {}

    public function categories(): array
    {
        return $this->database->query('SELECT id, name FROM categories WHERE is_active = 1 ORDER BY name')->fetchAll();
    }

    public function products(string $search = '', int $categoryId = 0, int $limit = 24): array
    {
        $sql = 'SELECT p.id, p.name, p.description, p.price, c.name AS category_name,
                       COALESCE(i.quantity_available, 0) AS stock, pi.file_path AS image_path
                FROM products p
                INNER JOIN categories c ON c.id = p.category_id AND c.is_active = 1
                LEFT JOIN inventory i ON i.product_id = p.id
                LEFT JOIN product_images pi ON pi.product_id = p.id AND pi.is_primary = 1
                WHERE p.is_active = 1';
        $parameters = [];
        if ($search !== '') {
            $sql .= ' AND (p.name LIKE :search_name OR p.description LIKE :search_description)';
            $parameters['search_name'] = '%' . $search . '%';
            $parameters['search_description'] = '%' . $search . '%';
        }
        if ($categoryId > 0) {
            $sql .= ' AND p.category_id = :category_id';
            $parameters['category_id'] = $categoryId;
        }
        $sql .= ' ORDER BY p.created_at DESC LIMIT ' . max(1, min($limit, 100));
        $statement = $this->database->prepare($sql);
        $statement->execute($parameters);
        return $statement->fetchAll();
    }

    public function stockForProducts(array $productIds): array
    {
        $productIds = array_values(array_unique(array_filter(
            array_map('intval', $productIds),
            static fn (int $id): bool => $id > 0
        )));
        if ($productIds === []) return [];

        $placeholders = implode(',', array_fill(0, count($productIds), '?'));
        $statement = $this->database->prepare(
            'SELECT p.id, COALESCE(i.quantity_available, 0) AS stock
             FROM products p
             INNER JOIN categories c ON c.id = p.category_id AND c.is_active = 1
             LEFT JOIN inventory i ON i.product_id = p.id
             WHERE p.is_active = 1 AND p.id IN (' . $placeholders . ')'
        );
        $statement->execute($productIds);

        $stocks = [];
        foreach ($statement->fetchAll() as $product) {
            $stocks[(int) $product['id']] = (int) $product['stock'];
        }
        return $stocks;
    }

    public function product(int $id): ?array
    {
        $statement = $this->database->prepare(
            'SELECT p.id, p.name, p.description, p.price, c.name AS category_name,
                    COALESCE(i.quantity_available, 0) AS stock, pi.file_path AS image_path
             FROM products p
             INNER JOIN categories c ON c.id = p.category_id AND c.is_active = 1
             LEFT JOIN inventory i ON i.product_id = p.id
             LEFT JOIN product_images pi ON pi.product_id = p.id AND pi.is_primary = 1
             WHERE p.id = :id AND p.is_active = 1 LIMIT 1'
        );
        $statement->execute(['id' => $id]);
        $product = $statement->fetch();
        return $product ?: null;
    }

    public function cartProducts(array $cart): array
    {
        if ($cart === []) return [];
        $ids = array_values(array_filter(array_map('intval', array_keys($cart)), static fn (int $id): bool => $id > 0));
        if ($ids === []) return [];
        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $statement = $this->database->prepare(
            'SELECT p.id, p.name, p.price, COALESCE(i.quantity_available, 0) AS stock,
                    pi.file_path AS image_path
             FROM products p LEFT JOIN inventory i ON i.product_id = p.id
             LEFT JOIN product_images pi ON pi.product_id = p.id AND pi.is_primary = 1
             WHERE p.is_active = 1 AND p.id IN (' . $placeholders . ')'
        );
        $statement->execute($ids);
        $products = $statement->fetchAll();
        foreach ($products as &$product) {
            $product['quantity'] = max(1, (int) ($cart[$product['id']] ?? 1));
            $product['line_total'] = (float) $product['price'] * $product['quantity'];
        }
        unset($product);
        return $products;
    }

    public function checkout(int $userId, array $cart, array $shipping): string
    {
        if ($cart === []) throw new RuntimeException('El carrito esta vacio.');
        $this->database->beginTransaction();
        try {
            $items = [];
            $subtotal = 0.0;
            $stockQuery = $this->database->prepare(
                'SELECT p.id, p.name, p.sku, p.price, p.is_active,
                        COALESCE(i.quantity_available, 0) AS stock
                 FROM products p LEFT JOIN inventory i ON i.product_id = p.id
                 WHERE p.id = :id FOR UPDATE'
            );
            foreach ($cart as $productId => $quantity) {
                $quantity = max(1, (int) $quantity);
                $stockQuery->execute(['id' => (int) $productId]);
                $product = $stockQuery->fetch();
                if (!$product || !(bool) $product['is_active']) throw new RuntimeException('Uno de los productos ya no esta disponible.');
                if ((int) $product['stock'] < $quantity) throw new RuntimeException('Stock insuficiente para ' . $product['name'] . '.');
                $lineTotal = (float) $product['price'] * $quantity;
                $subtotal += $lineTotal;
                $items[] = $product + ['quantity' => $quantity, 'line_total' => $lineTotal];
            }

            $statusQuery = $this->database->prepare("SELECT id FROM order_statuses WHERE code = 'pending' LIMIT 1");
            $statusQuery->execute();
            $statusId = $statusQuery->fetchColumn();
            $paymentQuery = $this->database->prepare("SELECT id FROM payment_methods WHERE code = 'cash_on_delivery' AND is_active = 1 LIMIT 1");
            $paymentQuery->execute();
            $paymentMethodId = $paymentQuery->fetchColumn();
            if ($statusId === false || $paymentMethodId === false) throw new RuntimeException('Falta configurar el estado o el metodo de pago contraentrega.');

            $orderNumber = 'SN-' . date('Ymd') . '-' . strtoupper(bin2hex(random_bytes(3)));
            $orderQuery = $this->database->prepare(
                'INSERT INTO orders (order_number, user_id, status_id, payment_method_id,
                 recipient_name, recipient_phone, shipping_address, shipping_address_extra,
                 shipping_city, shipping_state, shipping_postal_code, customer_notes,
                 subtotal, shipping_cost, total_amount)
                 VALUES (:order_number, :user_id, :status_id, :payment_method_id,
                 :recipient_name, :recipient_phone, :shipping_address, :shipping_address_extra,
                 :shipping_city, :shipping_state, :shipping_postal_code, :customer_notes,
                 :subtotal, 0, :total_amount)'
            );
            $orderQuery->execute([
                'order_number' => $orderNumber,
                'user_id' => $userId,
                'status_id' => $statusId,
                'payment_method_id' => $paymentMethodId,
                'recipient_name' => $shipping['recipient_name'],
                'recipient_phone' => $shipping['recipient_phone'],
                'shipping_address' => $shipping['shipping_address'],
                'shipping_address_extra' => $shipping['shipping_address_extra'],
                'shipping_city' => $shipping['shipping_city'],
                'shipping_state' => $shipping['shipping_state'],
                'shipping_postal_code' => $shipping['shipping_postal_code'],
                'customer_notes' => $shipping['customer_notes'],
                'subtotal' => $subtotal,
                'total_amount' => $subtotal,
            ]);
            $orderId = (int) $this->database->lastInsertId();
            $itemQuery = $this->database->prepare(
                'INSERT INTO order_items (order_id, product_id, product_name, sku,
                 unit_price, quantity, line_total)
                 VALUES (:order_id, :product_id, :product_name, :sku,
                 :unit_price, :quantity, :line_total)'
            );
            $inventoryQuery = $this->database->prepare(
                'UPDATE inventory SET quantity_available = quantity_available - :quantity
                 WHERE product_id = :product_id AND quantity_available >= :required_quantity'
            );
            foreach ($items as $item) {
                $itemQuery->execute([
                    'order_id' => $orderId,
                    'product_id' => $item['id'],
                    'product_name' => $item['name'],
                    'sku' => $item['sku'],
                    'unit_price' => $item['price'],
                    'quantity' => $item['quantity'],
                    'line_total' => $item['line_total'],
                ]);
                $inventoryQuery->execute(['quantity' => $item['quantity'], 'product_id' => $item['id'], 'required_quantity' => $item['quantity']]);
                if ($inventoryQuery->rowCount() !== 1) throw new RuntimeException('El inventario cambio. Revisa el stock e intenta de nuevo.');
            }
            $history = $this->database->prepare('INSERT INTO order_status_history (order_id, status_id, changed_by, note) VALUES (:order_id, :status_id, :user_id, :note)');
            $history->execute(['order_id' => $orderId, 'status_id' => $statusId, 'user_id' => $userId, 'note' => 'Pedido creado desde la tienda.']);
            $this->database->commit();
            return $orderNumber;
        } catch (\Throwable $exception) {
            if ($this->database->inTransaction()) $this->database->rollBack();
            throw $exception;
        }
    }
}