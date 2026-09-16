<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Csrf;
use App\Models\Catalog;
use App\Models\User;
use PDOException;

final class CatalogController extends Controller
{
    private User $users;

    public function __construct(private Catalog $catalog, \PDO $database) { $this->users = new User($database); }

    public function products(): void { if ($this->admin()) $this->view('admin/products/index', ['title' => 'Productos', 'products' => $this->catalog->products()]); }
    public function productForm(): void
    {
        if (!$this->admin()) return;
        $id = (int) ($_GET['id'] ?? 0);
        $this->view('admin/products/form', ['title' => $id ? 'Editar producto' : 'Nuevo producto', 'product' => $id ? $this->catalog->product($id) : null, 'categories' => $this->catalog->categories()]);
    }
    public function saveProduct(): void
    {
        if (!$this->admin()) return;
        Csrf::validate($_POST['_csrf'] ?? null);
        $id = (int) ($_POST['id'] ?? 0);
        $data = ['category_id' => (int) ($_POST['category_id'] ?? 0), 'name' => trim((string) ($_POST['name'] ?? '')), 'description' => trim((string) ($_POST['description'] ?? '')), 'price' => (float) ($_POST['price'] ?? 0), 'stock' => (int) ($_POST['stock'] ?? 0), 'image_path' => trim((string) ($_POST['current_image_path'] ?? '')), 'is_active' => isset($_POST['is_active']) ? 1 : 0];
        $errors = [];
        if ($data['category_id'] < 1 || $data['name'] === '') $errors[] = 'Completa los campos obligatorios.';
        if ($data['price'] < 0 || $data['stock'] < 0) $errors[] = 'El precio y el stock no pueden ser negativos.';
        if (!empty($_FILES['product_image']['name'])) {
            try { $data['image_path'] = $this->uploadProductImage($_FILES['product_image']); }
            catch (\RuntimeException $exception) { $errors[] = $exception->getMessage(); }
        }
        if ($errors !== []) { $this->view('admin/products/form', ['title' => $id ? 'Editar producto' : 'Nuevo producto', 'product' => $data + ['id' => $id], 'categories' => $this->catalog->categories(), 'errors' => $errors]); return; }
        try { $id ? $this->catalog->updateProduct($id, $data) : $this->catalog->createProduct($data); }
        catch (PDOException) { $this->view('admin/products/form', ['title' => $id ? 'Editar producto' : 'Nuevo producto', 'product' => $data + ['id' => $id], 'categories' => $this->catalog->categories(), 'errors' => ['No fue posible guardar el producto.']]); return; }
        $this->flash('Producto guardado correctamente.'); $this->redirect('/SS_NOVA_TECHNOLOGY/public/admin/products');
    }

    private function uploadProductImage(array $file): string
    {
        if (($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) throw new \RuntimeException('No fue posible cargar la imagen.');
        if (($file['size'] ?? 0) > 5 * 1024 * 1024) throw new \RuntimeException('La imagen no puede superar 5 MB.');
        $imageInfo = @getimagesize($file['tmp_name']);
        $mimeTypes = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp', 'image/gif' => 'gif'];
        if ($imageInfo === false || !isset($mimeTypes[$imageInfo['mime']])) throw new \RuntimeException('Solo se permiten imágenes JPG, PNG, WEBP o GIF.');
        $fileName = bin2hex(random_bytes(16)) . '.' . $mimeTypes[$imageInfo['mime']];
        $directory = dirname(__DIR__) . '/public/assets/images/products';
        if (!is_dir($directory) && !mkdir($directory, 0755, true)) throw new \RuntimeException('No se pudo preparar la carpeta de imágenes.');
        if (!move_uploaded_file($file['tmp_name'], $directory . '/' . $fileName)) throw new \RuntimeException('No se pudo guardar la imagen.');
        return 'assets/images/products/' . $fileName;
    }
    public function deleteProduct(): void
    {
        if (!$this->admin()) return;
        Csrf::validate($_POST['_csrf'] ?? null);
        $this->catalog->deleteProduct((int) ($_POST['id'] ?? 0));
        $this->flash('Producto eliminado correctamente.');
        $this->redirect('/SS_NOVA_TECHNOLOGY/public/admin/products');
    }
    public function toggleProduct(): void
    {
        if (!$this->admin()) return;
        Csrf::validate($_POST['_csrf'] ?? null);
        $id = (int) ($_POST['id'] ?? 0);
        $product = $this->catalog->product($id);
        if ($product) $this->catalog->toggleProduct($id, !(bool) $product['is_active']);
        $this->flash('Estado del producto actualizado.');
        $this->redirect('/SS_NOVA_TECHNOLOGY/public/admin/products');
    }
    public function suppliers(): void { if ($this->admin()) $this->view('admin/suppliers/index', ['title' => 'Proveedores', 'suppliers' => $this->catalog->suppliers()]); }
    public function supplierForm(): void
    {
        if (!$this->admin()) return;
        $id = (int) ($_GET['id'] ?? 0);
        $this->view('admin/suppliers/form', ['title' => $id ? 'Editar proveedor' : 'Nuevo proveedor', 'supplier' => $id ? $this->catalog->supplier($id) : null]);
    }
    public function saveSupplier(): void
    {
        if (!$this->admin()) return;
        Csrf::validate($_POST['_csrf'] ?? null);
        $id = (int) ($_POST['id'] ?? 0);
        $data = ['name' => trim((string) ($_POST['name'] ?? '')), 'contact_name' => trim((string) ($_POST['contact_name'] ?? '')), 'email' => strtolower(trim((string) ($_POST['email'] ?? ''))) ?: null, 'phone' => trim((string) ($_POST['phone'] ?? '')), 'address' => trim((string) ($_POST['address'] ?? '')), 'is_active' => isset($_POST['is_active']) ? 1 : 0];
        if ($data['name'] === '') { $this->view('admin/suppliers/form', ['title' => $id ? 'Editar proveedor' : 'Nuevo proveedor', 'supplier' => $data + ['id' => $id], 'errors' => ['El nombre es obligatorio.']]); return; }
        if ($data['email'] !== null && !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $this->view('admin/suppliers/form', ['title' => $id ? 'Editar proveedor' : 'Nuevo proveedor', 'supplier' => $data + ['id' => $id], 'errors' => ['Escribe un correo valido.']]);
            return;
        }
        try { $id ? $this->catalog->updateSupplier($id, $data) : $this->catalog->createSupplier($data); }
        catch (PDOException) { $this->view('admin/suppliers/form', ['title' => $id ? 'Editar proveedor' : 'Nuevo proveedor', 'supplier' => $data + ['id' => $id], 'errors' => ['No fue posible guardar el proveedor. Verifica los datos ingresados.']]); return; }
        $this->flash('Proveedor guardado correctamente.'); $this->redirect('/SS_NOVA_TECHNOLOGY/public/admin/suppliers');
    }
    public function toggleSupplier(): void
    {
        if (!$this->admin()) return;
        Csrf::validate($_POST['_csrf'] ?? null); $id = (int) ($_POST['id'] ?? 0); $supplier = $this->catalog->supplier($id);
        if ($supplier) $this->catalog->toggleSupplier($id, !(bool) $supplier['is_active']);
        $this->flash('Estado del proveedor actualizado.'); $this->redirect('/SS_NOVA_TECHNOLOGY/public/admin/suppliers');
    }
    public function deleteSupplier(): void
    {
        if (!$this->admin()) return;
        Csrf::validate($_POST['_csrf'] ?? null);
        $this->catalog->deleteSupplier((int) ($_POST['id'] ?? 0));
        $this->flash('Proveedor eliminado correctamente.');
        $this->redirect('/SS_NOVA_TECHNOLOGY/public/admin/suppliers');
    }
    private function admin(): bool { if (!Auth::check()) { $this->redirect('/SS_NOVA_TECHNOLOGY/public/login'); } if (!Auth::userId() || !$this->users->isAdmin(Auth::userId())) { http_response_code(403); echo 'No tienes permisos para acceder a esta seccion.'; return false; } return true; }
}