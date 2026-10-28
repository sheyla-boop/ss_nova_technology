<section class="store-hero">
    <div class="store-hero-copy">
        <p class="eyebrow">SS Nova Technology / tienda digital</p>
        <h1>Tecnologia que se mueve contigo.</h1>
        <p>Encuentra celulares, computadores y soluciones para tu hogar. Compra fácil y paga cuando llegue a tu puerta.</p>
        <a class="button" href="/SS_NOVA_TECHNOLOGY/public/catalog">Explorar catalogo <span aria-hidden="true">&#8594;</span></a>
        <div class="hero-proof"><span>01 / Productos seleccionados</span><span>02 / Pago contraentrega</span><span>03 / Entrega a domicilio</span></div>
    </div>
    <div class="store-hero-image" role="img" aria-label="Computadores y dispositivos tecnologicos"></div>
</section>
<section class="section-heading"><div><p class="eyebrow">Lo que buscas, mas cerca</p><h2>Explora la seleccion</h2></div><a href="/SS_NOVA_TECHNOLOGY/public/catalog">Ver catalogo completo <span aria-hidden="true">&#8594;</span></a></section>
<section class="product-grid" aria-label="Productos destacados">
<?php if ($products === []): ?><p class="empty-state">Estamos preparando el catalogo. Vuelve pronto para descubrir productos.</p><?php endif; ?>
<?php foreach ($products as $product): ?>
    <article class="product-card">
        <a class="product-image" href="/SS_NOVA_TECHNOLOGY/public/product?id=<?= (int) $product['id'] ?>">
            <?php if (!empty($product['image_path'])): ?><img src="/SS_NOVA_TECHNOLOGY/public/<?= htmlspecialchars($product['image_path'], ENT_QUOTES, 'UTF-8') ?>" alt="<?= htmlspecialchars($product['name'], ENT_QUOTES, 'UTF-8') ?>" loading="lazy"><?php else: ?><span class="product-image-empty">SSN<span>TECH</span></span><?php endif; ?>
        </a>
        <div class="product-card-copy"><p class="product-category"><?= htmlspecialchars($product['category_name'], ENT_QUOTES, 'UTF-8') ?></p><h3><a href="/SS_NOVA_TECHNOLOGY/public/product?id=<?= (int) $product['id'] ?>"><?= htmlspecialchars($product['name'], ENT_QUOTES, 'UTF-8') ?></a></h3><p class="product-price">$ <?= number_format((float) $product['price'], 0, ',', '.') ?> <small>COP</small></p><a class="text-link" href="/SS_NOVA_TECHNOLOGY/public/product?id=<?= (int) $product['id'] ?>">Ver producto <span aria-hidden="true">&#8594;</span></a></div>
    </article>
<?php endforeach; ?>
</section>
<section class="trust-strip"><span>Compra protegida</span><span>Pago contraentrega</span><span>Atencion cercana</span><span>Tecnologia para todos</span></section>