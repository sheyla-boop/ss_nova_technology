<?php

declare(strict_types=1);

namespace App\Models;

use PDO;
use RuntimeException;

final class Reports
{
    public function __construct(private PDO $database) {}

    public function dashboard(): array
    {
        $metrics = $this->database->query(
            "SELECT
                (SELECT COUNT(*) FROM orders o INNER JOIN order_statuses s ON s.id = o.status_id WHERE s.code <> 'cancelled') AS orders_count,
                (SELECT COALESCE(SUM(o.total_amount), 0) FROM orders o INNER JOIN order_statuses s ON s.id = o.status_id WHERE s.code = 'delivered') AS revenue,
                (SELECT COALESCE(SUM(oi.quantity), 0) FROM order_items oi INNER JOIN orders o ON o.id = oi.order_id INNER JOIN order_statuses s ON s.id = o.status_id WHERE s.code = 'delivered') AS units_sold,
                (SELECT COUNT(*) FROM products p LEFT JOIN inventory i ON i.product_id = p.id WHERE p.is_active = 1 AND COALESCE(i.quantity_available, 0) <= COALESCE(i.low_stock_threshold, 5)) AS low_stock_count,
                (SELECT COUNT(*) FROM orders o INNER JOIN order_statuses s ON s.id = o.status_id WHERE s.code = 'delivered') AS delivered_orders"
        )->fetch();

        $monthlySales = $this->database->query(
            "SELECT DATE_FORMAT(o.created_at, '%Y-%m') AS month_key,
                    DATE_FORMAT(o.created_at, '%b %Y') AS month_label,
                    COALESCE(SUM(o.total_amount), 0) AS revenue,
                    COUNT(*) AS orders_count
             FROM orders o INNER JOIN order_statuses s ON s.id = o.status_id
             WHERE s.code = 'delivered' AND o.created_at >= DATE_FORMAT(CURRENT_DATE - INTERVAL 5 MONTH, '%Y-%m-01')
             GROUP BY month_key, month_label ORDER BY month_key"
        )->fetchAll();

        $topProducts = $this->database->query(
                "SELECT oi.product_id, oi.product_name,
                    (SELECT pi.file_path FROM product_images pi
                     WHERE pi.product_id = oi.product_id AND pi.is_primary = 1 LIMIT 1) AS image_path,
                    SUM(oi.quantity) AS units_sold,
                    SUM(oi.line_total) AS revenue
             FROM order_items oi INNER JOIN orders o ON o.id = oi.order_id
             INNER JOIN order_statuses s ON s.id = o.status_id
             WHERE s.code = 'delivered'
             GROUP BY oi.product_id, oi.product_name
             ORDER BY units_sold DESC, revenue DESC LIMIT 5"
        )->fetchAll();

        $recentOrders = $this->database->query(
                'SELECT o.id, o.order_number, o.total_amount, o.created_at,
                    s.code AS status_code, s.name AS status_name,
                    CONCAT(u.first_name, " ", u.last_name) AS customer_name
             FROM orders o INNER JOIN order_statuses s ON s.id = o.status_id
             INNER JOIN users u ON u.id = o.user_id
               ORDER BY o.created_at DESC LIMIT 5'
        )->fetchAll();

        $orderStatuses = $this->database->query(
            'SELECT code, name, sort_order FROM order_statuses ORDER BY sort_order'
        )->fetchAll();

           $supplierContacts = $this->database->query(
              'SELECT name, contact_name, email, phone
               FROM suppliers
               WHERE is_active = 1
               ORDER BY name
               LIMIT 8'
           )->fetchAll();

        return [
            'metrics' => $metrics,
            'monthlySales' => $monthlySales,
            'topProducts' => $topProducts,
            'recentOrders' => $recentOrders,
            'orderStatuses' => $orderStatuses,
            'supplierContacts' => $supplierContacts,
        ];
    }

    public function generateReport(string $type, string $dateFrom, string $dateTo): array
    {
        if (!in_array($type, ['sales', 'orders', 'inventory'], true)) {
            throw new RuntimeException('Selecciona un tipo de reporte valido.');
        }

        if ($type === 'inventory') {
            $rows = $this->database->query(
                'SELECT p.sku, p.name AS product_name, c.name AS category_name,
                        COALESCE(i.quantity_available, 0) AS stock,
                        COALESCE(i.low_stock_threshold, 5) AS low_stock_threshold,
                        p.price
                 FROM products p
                 INNER JOIN categories c ON c.id = p.category_id
                 LEFT JOIN inventory i ON i.product_id = p.id
                 WHERE p.is_active = 1
                 ORDER BY c.name, p.name'
            )->fetchAll();

            return [
                'rows' => $rows,
                'summary' => [
                    'count' => count($rows),
                    'stock_units' => array_sum(array_column($rows, 'stock')),
                    'low_stock' => count(array_filter($rows, static fn (array $row): bool => (int) $row['stock'] <= (int) $row['low_stock_threshold'])),
                ],
            ];
        }

        $deliveredOnly = $type === 'sales' ? " AND s.code = 'delivered'" : '';
        $statement = $this->database->prepare(
            'SELECT o.order_number, CONCAT(u.first_name, " ", u.last_name) AS customer_name,
                    DATE_FORMAT(o.created_at, "%d/%m/%Y %H:%i") AS order_date,
                    s.name AS status_name, pm.name AS payment_method,
                    (SELECT COALESCE(SUM(oi.quantity), 0) FROM order_items oi WHERE oi.order_id = o.id) AS units,
                    o.total_amount
             FROM orders o
             INNER JOIN users u ON u.id = o.user_id
             INNER JOIN order_statuses s ON s.id = o.status_id
             INNER JOIN payment_methods pm ON pm.id = o.payment_method_id
             WHERE o.created_at >= :date_from
               AND o.created_at < DATE_ADD(:date_to, INTERVAL 1 DAY)' . $deliveredOnly . '
             ORDER BY o.created_at DESC'
        );
        $statement->execute(['date_from' => $dateFrom, 'date_to' => $dateTo]);
        $rows = $statement->fetchAll();

        return [
            'rows' => $rows,
            'summary' => [
                'count' => count($rows),
                'total' => array_sum(array_column($rows, 'total_amount')),
                'units' => array_sum(array_column($rows, 'units')),
            ],
        ];
    }

    public function updateOrderStatus(int $orderId, string $statusCode, int $adminId): void
    {
        $this->database->beginTransaction();
        try {
            $orderQuery = $this->database->prepare(
                'SELECT o.id, current_status.code AS current_code,
                        current_status.sort_order AS current_sort_order
                 FROM orders o
                 INNER JOIN order_statuses current_status ON current_status.id = o.status_id
                 WHERE o.id = :order_id FOR UPDATE'
            );
            $orderQuery->execute(['order_id' => $orderId]);
            $order = $orderQuery->fetch();
            if (!$order) throw new RuntimeException('No se encontro el pedido.');
            if (in_array($order['current_code'], ['delivered', 'cancelled'], true)) {
                throw new RuntimeException('El pedido ya tiene un estado final y no se puede modificar.');
            }

            $statusQuery = $this->database->prepare(
                'SELECT id, code, sort_order FROM order_statuses WHERE code = :code LIMIT 1'
            );
            $statusQuery->execute(['code' => $statusCode]);
            $status = $statusQuery->fetch();
            if (!$status) throw new RuntimeException('Selecciona un estado valido.');
            if ($status['code'] !== 'cancelled' && (int) $status['sort_order'] <= (int) $order['current_sort_order']) {
                throw new RuntimeException('El estado del pedido solo puede avanzar.');
            }
            if ($status['code'] === 'cancelled' && (int) $order['current_sort_order'] >= 4) {
                throw new RuntimeException('Un pedido enviado ya no se puede cancelar desde el panel.');
            }

            $this->database->prepare('UPDATE orders SET status_id = :status_id WHERE id = :order_id')->execute([
                'status_id' => $status['id'],
                'order_id' => $orderId,
            ]);
            $this->database->prepare(
                'INSERT INTO order_status_history (order_id, status_id, changed_by, note)
                 VALUES (:order_id, :status_id, :admin_id, :note)'
            )->execute([
                'order_id' => $orderId,
                'status_id' => $status['id'],
                'admin_id' => $adminId,
                'note' => 'Estado actualizado desde el panel de empresa.',
            ]);
            $this->database->commit();
        } catch (\Throwable $exception) {
            if ($this->database->inTransaction()) $this->database->rollBack();
            throw $exception;
        }
    }
}