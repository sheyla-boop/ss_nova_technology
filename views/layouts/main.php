<?php

declare(strict_types=1);

$pageTitle = isset($title) ? $title . ' | SS Nova Technology' : 'SS Nova Technology';
$siteConfig = require dirname(__DIR__, 2) . '/config/config.php';
$stylesheetVersion = filemtime(dirname(__DIR__, 2) . '/public/assets/css/style.css');
$supportContacts = $siteConfig['support'] ?? [];
$isAdmin = \App\Core\Auth::isAdmin();
$contactHref = $isAdmin
    ? (($title ?? '') === 'Panel de empresa' ? '#contacto' : '/SS_NOVA_TECHNOLOGY/public/admin/suppliers')
    : '#contacto';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8') ?></title>
    <link rel="stylesheet" href="/SS_NOVA_TECHNOLOGY/public/assets/css/style.css?v=<?= rawurlencode((string) $stylesheetVersion) ?>">
</head>
<body>
    <header class="site-header">
        <a class="brand" href="/SS_NOVA_TECHNOLOGY/public/">SS Nova Technology</a>
        <nav aria-label="Navegacion principal">
            <a href="/SS_NOVA_TECHNOLOGY/public/">Inicio</a>
            <a href="/SS_NOVA_TECHNOLOGY/public/catalog">Catalogo</a>
            <a href="/SS_NOVA_TECHNOLOGY/public/cart">Carrito <span class="cart-count"><?= array_sum($_SESSION['cart'] ?? []) ?></span></a>
            <button class="nav-modal-trigger" type="button" aria-haspopup="dialog" aria-controls="about-us-dialog">Quienes somos</button>
            <button class="nav-modal-trigger" type="button" aria-haspopup="dialog" aria-controls="data-treatment-dialog">Tratamiento de datos</button>
            <?php if (\App\Core\Auth::check()): ?>
                <?php if (\App\Core\Auth::isAdmin()): ?>
                    <a class="nav-dashboard" href="/SS_NOVA_TECHNOLOGY/public/admin/reports">Panel empresa</a>
                <?php endif; ?>
                <form method="post" action="/SS_NOVA_TECHNOLOGY/public/logout" class="nav-form">
                    <input type="hidden" name="_csrf" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>">
                    <button type="submit">Cerrar sesion</button>
                </form>
            <?php else: ?>
                <a href="/SS_NOVA_TECHNOLOGY/public/login">Iniciar sesion</a>
            <?php endif; ?>
            <a href="<?= htmlspecialchars($contactHref, ENT_QUOTES, 'UTF-8') ?>">Contacto</a>
        </nav>
    </header>
    <dialog class="data-treatment-dialog" id="data-treatment-dialog" aria-labelledby="data-treatment-title">
        <div class="data-treatment-content">
            <div class="data-treatment-heading">
                <h2 id="data-treatment-title">Tratamiento de datos</h2>
                <button class="data-treatment-close" type="button" aria-label="Cerrar ventana">Cerrar</button>
            </div>
            <p>El presente proyecto tiene fines exclusivamente académicos. Los datos utilizados durante el desarrollo, las pruebas y la demostración del sistema son ficticios y fueron creados únicamente con fines de simulación. El sistema no recolecta, almacena ni procesa datos personales reales de estudiantes, docentes o terceros.</p>
        </div>
    </dialog>
    <dialog class="data-treatment-dialog about-us-dialog" id="about-us-dialog" aria-labelledby="about-us-title">
        <div class="data-treatment-content">
            <div class="data-treatment-heading">
                <h2 id="about-us-title">Quienes somos</h2>
                <button class="data-treatment-close" type="button" aria-label="Cerrar ventana">Cerrar</button>
            </div>
            <p>Los integrantes de SS NOVA TECHNOLOGY somos:</p>
            <div class="team-members">
                <article class="team-member">
                <?php if (is_file(dirname(__DIR__, 2) . '/public/assets/images/team/Foto de Samuel.jpg')): ?>
                    <img class="team-photo" src="/SS_NOVA_TECHNOLOGY/public/assets/images/team/Foto%20de%20Samuel.jpg" alt="Samuel Santofimio">
                <?php else: ?>
                    <div class="team-photo-placeholder" role="img" aria-label="Espacio reservado para la foto de Samuel Santofimio">Agrega samuel.jpg</div>
                <?php endif; ?>
                <h3>Samuel Santofimio</h3>
                </article>
            <article class="team-member">
                <?php if (is_file(dirname(__DIR__, 2) . '/public/assets/images/team/Foto de Sheyla.jpg')): ?>
                    <img class="team-photo" src="/SS_NOVA_TECHNOLOGY/public/assets/images/team/Foto%20de%20Sheyla.jpg" alt="Castaño Sheyla">
                <?php else: ?>
                    <div class="team-photo-placeholder" role="img" aria-label="Espacio reservado para la foto de Castaño Sheyla">Agrega sheyla.jpg</div>
                <?php endif; ?>
                <h3>Castaño Sheyla</h3>
            </article>
            </div>
        </div>
    </dialog>
    <main class="page-content">
        <?php if (is_array($flash) && !empty($flash['message'])): ?>
            <div class="flash-message flash-<?= htmlspecialchars($flash['type'] ?? 'success', ENT_QUOTES, 'UTF-8') ?>" role="status">
                <?= htmlspecialchars($flash['message'], ENT_QUOTES, 'UTF-8') ?>
            </div>
        <?php endif; ?>
        <?php require $viewFile; ?>
    </main>
    <footer class="site-footer" id="<?= $isAdmin && isset($supplierContacts) ? 'site-footer' : 'contacto' ?>">
        <div class="footer-inner">
            <div class="footer-brand">
                <small>SENA CDM</small>
                <a class="brand" href="/SS_NOVA_TECHNOLOGY/public/">SS Nova Technology</a>
                <small>&copy; <?= date('Y') ?> SS Nova Technology</small>
                <small><a href="/SS_NOVA_TECHNOLOGY/public/privacy-policy">Tratamiento de datos</a> · <a href="/SS_NOVA_TECHNOLOGY/public/password-policy">Política de contraseñas</a></small>
            </div>
            <section class="footer-contact" aria-labelledby="footer-contact-title">
                <?php if ($isAdmin): ?>
                    <p class="eyebrow">Uso interno</p>
                    <h2 id="footer-contact-title">Directorio de proveedores</h2>
                    <p>Consulta telefonos y correos desde el panel de proveedores.</p>
                    <a class="footer-contact-link" href="/SS_NOVA_TECHNOLOGY/public/admin/suppliers">Abrir directorio <span aria-hidden="true">&#8594;</span></a>
                <?php else: ?>
                    <p class="eyebrow">Estamos para ayudarte</p>
                    <h2 id="footer-contact-title">Ayuda al cliente</h2>
                    <?php if (!empty($supportContacts['email'])): ?><a href="mailto:<?= htmlspecialchars($supportContacts['email'], ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($supportContacts['email'], ENT_QUOTES, 'UTF-8') ?></a><?php endif; ?>
                    <?php if (!empty($supportContacts['phone'])): ?><a href="tel:<?= htmlspecialchars($supportContacts['phone'], ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($supportContacts['phone'], ENT_QUOTES, 'UTF-8') ?></a><?php endif; ?>
                    <?php if (empty($supportContacts['email']) && empty($supportContacts['phone'])): ?><p class="footer-contact-note">Los canales de soporte estan pendientes de configuracion.</p><?php endif; ?>
                <?php endif; ?>
            </section>
        </div>
    </footer>
    <script>
        document.querySelectorAll('.data-treatment-dialog').forEach((dialog) => {
            document.querySelector(`[aria-controls="${dialog.id}"]`).addEventListener('click', () => dialog.showModal());
            dialog.querySelector('.data-treatment-close').addEventListener('click', () => dialog.close());
            dialog.addEventListener('click', (event) => {
                if (event.target === dialog) dialog.close();
            });
        });
    </script>
</body>
</html>
