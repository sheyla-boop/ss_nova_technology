<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Csrf;
use App\Models\Storefront;
use RuntimeException;

final class ShopController extends Controller
{
    public function __construct(private Storefront $storefront) {}

    public function home(): void
    {
        $this->view('shop/home', [
            'title' => 'Tecnologia para todos los dias',
            'products' => $this->storefront->products('', 0, 8),
        ]);
    }

    public function catalog(): void
    {
        $search = trim((string) ($_GET['q'] ?? ''));
        $categoryId = max(0, (int) ($_GET['category'] ?? 0));
        $this->view('shop/catalog', [
            'title' => 'Catalogo',
            'products' => $this->storefront->products($search, $categoryId),
            'categories' => $this->storefront->categories(),
            'search' => $search,
            'categoryId' => $categoryId,
        ]);
    }

    public function catalogStock(): void
    {
        $productIds = array_slice(array_values(array_unique(array_filter(
            array_map('intval', explode(',', (string) ($_GET['ids'] ?? ''))),
            static fn (int $id): bool => $id > 0
        ))), 0, 100);

        header('Content-Type: application/json; charset=utf-8');
        header('Cache-Control: no-store, max-age=0');
        echo json_encode(['stocks' => $this->storefront->stockForProducts($productIds)], JSON_THROW_ON_ERROR);
    }

    public function product(): void
    {
        $product = $this->storefront->product((int) ($_GET['id'] ?? 0));
        if ($product === null) { http_response_code(404); echo 'Producto no encontrado.'; return; }
        $this->view('shop/product', ['title' => $product['name'], 'product' => $product]);
    }

    public function addToCart(): void
    {
        Csrf::validate($_POST['_csrf'] ?? null);
        $product = $this->storefront->product((int) ($_POST['product_id'] ?? 0));
        if (!$product) { $this->flash('El producto no esta disponible.', 'error'); $this->redirect('/SS_NOVA_TECHNOLOGY/public/catalog'); }
        if ((int) $product['stock'] < 1) { $this->flash('Este producto esta agotado.', 'error'); $this->redirect('/SS_NOVA_TECHNOLOGY/public/catalog'); }
        $productId = (int) $product['id'];
        $newQuantity = (int) ($_SESSION['cart'][$productId] ?? 0) + max(1, (int) ($_POST['quantity'] ?? 1));
        $_SESSION['cart'][$productId] = min($newQuantity, (int) $product['stock']);
        $this->flash('Producto agregado al carrito.');
        $this->redirect('/SS_NOVA_TECHNOLOGY/public/cart');
    }

    public function cart(): void
    {
        $products = $this->storefront->cartProducts($_SESSION['cart'] ?? []);
        $total = array_sum(array_column($products, 'line_total'));
        $this->view('shop/cart', ['title' => 'Carrito', 'products' => $products, 'total' => $total]);
    }

    public function removeFromCart(): void
    {
        Csrf::validate($_POST['_csrf'] ?? null);
        unset($_SESSION['cart'][(int) ($_POST['product_id'] ?? 0)]);
        $this->flash('Producto quitado del carrito.');
        $this->redirect('/SS_NOVA_TECHNOLOGY/public/cart');
    }

    public function checkoutForm(): void
    {
        if (!Auth::check()) $this->redirect('/SS_NOVA_TECHNOLOGY/public/login');
        $products = $this->storefront->cartProducts($_SESSION['cart'] ?? []);
        if ($products === []) { $this->flash('Agrega productos antes de finalizar la compra.', 'error'); $this->redirect('/SS_NOVA_TECHNOLOGY/public/catalog'); }
        $this->view('shop/checkout', ['title' => 'Finalizar compra', 'products' => $products, 'total' => array_sum(array_column($products, 'line_total'))]);
    }

    public function checkout(): void
    {
        if (!Auth::check()) $this->redirect('/SS_NOVA_TECHNOLOGY/public/login');
        Csrf::validate($_POST['_csrf'] ?? null);
        $shipping = [
            'recipient_name' => trim((string) ($_POST['recipient_name'] ?? '')),
            'recipient_phone' => trim((string) ($_POST['recipient_phone'] ?? '')),
            'shipping_address' => trim((string) ($_POST['shipping_address'] ?? '')),
            'shipping_city' => trim((string) ($_POST['shipping_city'] ?? '')),
            'shipping_address_extra' => trim((string) ($_POST['shipping_address_extra'] ?? '')),
            'shipping_state' => trim((string) ($_POST['shipping_state'] ?? '')),
            'shipping_postal_code' => trim((string) ($_POST['shipping_postal_code'] ?? '')),
            'customer_notes' => trim((string) ($_POST['customer_notes'] ?? '')),
        ];
        foreach (['recipient_name', 'recipient_phone', 'shipping_address', 'shipping_city'] as $requiredField) {
            if ($shipping[$requiredField] === '') {
                $this->flash('Completa todos los datos de entrega.', 'error');
                $this->redirect('/SS_NOVA_TECHNOLOGY/public/checkout');
            }
        }
        try {
            $orderNumber = $this->storefront->checkout((int) Auth::userId(), $_SESSION['cart'] ?? [], $shipping);
        } catch (RuntimeException $exception) {
            $this->flash($exception->getMessage(), 'error');
            $this->redirect('/SS_NOVA_TECHNOLOGY/public/cart');
        }
        unset($_SESSION['cart']);
        $this->view('shop/order-success', ['title' => 'Pedido recibido', 'orderNumber' => $orderNumber]);
    }
}