<?php

declare(strict_types=1);

$pageTitle = isset($title) ? $title . ' | SS Nova Technology' : 'SS Nova Technology';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8') ?></title>
    <link rel="stylesheet" href="/SS_NOVA_TECHNOLOGY/public/assets/css/app.css">
</head>
<body>
    <header class="site-header">
        <a class="brand" href="/SS_NOVA_TECHNOLOGY/public/">SS Nova Technology</a>
        <nav aria-label="Navegacion principal">
            <a href="/SS_NOVA_TECHNOLOGY/public/">Inicio</a>
            <a href="#catalogo">Catalogo</a>
            <?php if (\App\Core\Auth::check()): ?>
                <?php if (\App\Core\Auth::isAdmin()): ?>
                    <a href="/SS_NOVA_TECHNOLOGY/public/admin/users">Administracion</a>
                    <a href="/SS_NOVA_TECHNOLOGY/public/admin/clients">Clientes</a>
                    <a href="/SS_NOVA_TECHNOLOGY/public/admin/products">Productos</a>
                    <a href="/SS_NOVA_TECHNOLOGY/public/admin/suppliers">Proveedores</a>
                <?php endif; ?>
                <form method="post" action="/SS_NOVA_TECHNOLOGY/public/logout" class="nav-form">
                    <input type="hidden" name="_csrf" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>">
                    <button type="submit">Cerrar sesion</button>
                </form>
            <?php else: ?>
                <a href="/SS_NOVA_TECHNOLOGY/public/login">Iniciar sesion</a>
            <?php endif; ?>
            <a href="#contacto">Contacto</a>
        </nav>
    </header>
    <main class="page-content">
        <?php if (is_array($flash) && !empty($flash['message'])): ?>
            <div class="flash-message flash-<?= htmlspecialchars($flash['type'] ?? 'success', ENT_QUOTES, 'UTF-8') ?>" role="status">
                <?= htmlspecialchars($flash['message'], ENT_QUOTES, 'UTF-8') ?>
            </div>
        <?php endif; ?>
        <?php if (\App\Core\Auth::isAdmin()): ?>
            <section class="admin-visual-nav" aria-label="Modulos administrativos">
                <a class="visual-link visual-users" href="/SS_NOVA_TECHNOLOGY/public/admin/users"><span>Usuarios tecnológicos</span><small>Personas y acceso a la plataforma</small></a>
                <a class="visual-link visual-clients" href="/SS_NOVA_TECHNOLOGY/public/admin/clients"><span>Clientes</span><small>Personas registradas</small></a>
                <a class="visual-link visual-products" href="/SS_NOVA_TECHNOLOGY/public/admin/products"><span>Productos</span><small>Catalogo y stock</small></a>
                <a class="visual-link visual-suppliers" href="/SS_NOVA_TECHNOLOGY/public/admin/suppliers"><span>Proveedores</span><small>Red de abastecimiento</small></a>
            </section>
        <?php endif; ?>
        <?php require $viewFile; ?>
    </main>
    <footer class="site-footer" id="contacto">
        <small>&copy; <?= date('Y') ?> SS Nova Technology</small>
    </footer>
</body>
</html>
