<?php

declare(strict_types=1);

use App\Controllers\HomeController;
use App\Controllers\AuthController;
use App\Controllers\AdminController;
use App\Controllers\CatalogController;
use App\Models\Catalog;
use App\Core\Auth;
use App\Core\Database;
use App\Core\Router;

$config = require dirname(__DIR__) . '/config/config.php';

session_set_cookie_params([
    'httponly' => true,
    'secure' => isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
    'samesite' => 'Lax',
]);
session_start();

spl_autoload_register(function (string $class): void {
    $prefix = 'App\\';
    if (!str_starts_with($class, $prefix)) {
        return;
    }

    $relativeClass = substr($class, strlen($prefix));
    $file = dirname(__DIR__) . '/' . str_replace('\\', '/', $relativeClass) . '.php';

    if (is_readable($file)) {
        require $file;
    }
});

$router = new Router();
$homeController = new HomeController();
$database = Database::connection($config['database']);
$authController = new AuthController($database);
$adminController = new AdminController($database);
$catalogController = new CatalogController(new Catalog($database), $database);
$router->get('/', [$homeController, 'index']);
$router->get('/login', [$authController, 'showLogin']);
$router->post('/login', [$authController, 'login']);
$router->get('/register', [$authController, 'showRegister']);
$router->post('/register', [$authController, 'register']);
$router->post('/logout', [$authController, 'logout']);
$router->get('/admin/users', [$adminController, 'index']);
$router->get('/admin/users/create', [$adminController, 'create']);
$router->post('/admin/users/store', [$adminController, 'store']);
$router->get('/admin/users/edit', [$adminController, 'edit']);
$router->post('/admin/users/update', [$adminController, 'update']);
$router->post('/admin/users/toggle', [$adminController, 'toggle']);
$router->post('/admin/users/delete', [$adminController, 'delete']);
$router->get('/admin/clients', [$adminController, 'clients']);
$router->get('/admin/products', [$catalogController, 'products']);
$router->get('/admin/products/form', [$catalogController, 'productForm']);
$router->post('/admin/products/save', [$catalogController, 'saveProduct']);
$router->post('/admin/products/delete', [$catalogController, 'deleteProduct']);
$router->post('/admin/products/toggle', [$catalogController, 'toggleProduct']);
$router->get('/admin/suppliers', [$catalogController, 'suppliers']);
$router->get('/admin/suppliers/form', [$catalogController, 'supplierForm']);
$router->post('/admin/suppliers/save', [$catalogController, 'saveSupplier']);
$router->post('/admin/suppliers/toggle', [$catalogController, 'toggleSupplier']);
$router->post('/admin/suppliers/delete', [$catalogController, 'deleteSupplier']);

try {
    $requestUri = $_SERVER['REQUEST_URI'] ?? '/';
    $basePath = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/')), '/');

    if ($basePath !== '' && str_starts_with($requestUri, $basePath)) {
        $requestUri = substr($requestUri, strlen($basePath)) ?: '/';
    }

    $router->dispatch($_SERVER['REQUEST_METHOD'] ?? 'GET', $requestUri);
} catch (Throwable $exception) {
    http_response_code(http_response_code() >= 400 ? http_response_code() : 500);
    if ($config['app']['debug']) {
        echo '<pre>' . htmlspecialchars($exception->getMessage(), ENT_QUOTES, 'UTF-8') . '</pre>';
    } else {
        echo 'Ha ocurrido un error inesperado.';
    }
}
