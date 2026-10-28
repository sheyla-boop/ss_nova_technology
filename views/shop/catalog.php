<section class="catalog-heading"><p class="eyebrow">SS Nova / catalogo</p><h1>Encuentra tu proximo equipo.</h1><p>Explora productos disponibles y filtra por categoria o nombre.</p></section>
<div class="catalog-controls">
    <form class="catalog-search" method="get" action="/SS_NOVA_TECHNOLOGY/public/catalog">
        <label for="catalog-query">Buscar por nombre o descripcion</label>
        <div class="catalog-search-row">
            <input id="catalog-query" type="search" name="q" value="<?= htmlspecialchars($search, ENT_QUOTES, 'UTF-8') ?>" placeholder="Ej. celular, computador">
            <?php if ($categoryId > 0): ?><input type="hidden" name="category" value="<?= $categoryId ?>"><?php endif; ?>
            <button class="button" type="submit">Buscar productos</button>
        </div>
    </form>
    <nav class="category-filter" aria-label="Filtrar por categoria">
        <p class="category-filter-label">Explora por categoria</p>
        <div class="category-chips">
            <a class="category-chip<?= $categoryId === 0 ? ' is-active' : '' ?>" href="/SS_NOVA_TECHNOLOGY/public/catalog?<?= http_build_query(['q' => $search]) ?>"<?= $categoryId === 0 ? ' aria-current="page"' : '' ?>>Todas</a>
            <?php foreach ($categories as $category): ?>
                <a class="category-chip<?= $categoryId === (int) $category['id'] ? ' is-active' : '' ?>" href="/SS_NOVA_TECHNOLOGY/public/catalog?<?= http_build_query(['q' => $search, 'category' => (int) $category['id']]) ?>"<?= $categoryId === (int) $category['id'] ? ' aria-current="page"' : '' ?>><?= htmlspecialchars($category['name'], ENT_QUOTES, 'UTF-8') ?></a>
            <?php endforeach; ?>
        </div>
    </nav>
</div>
<p class="results-caption"><?= count($products) ?> productos encontrados</p>
<section class="product-grid" aria-label="Catalogo de productos" data-stock-poll data-stock-endpoint="/SS_NOVA_TECHNOLOGY/public/catalog/stock">
<?php if ($products === []): ?><p class="empty-state">No encontramos productos con esos filtros. Prueba otra busqueda.</p><?php endif; ?>
<?php foreach ($products as $product): ?>
    <article class="product-card" data-product-id="<?= (int) $product['id'] ?>" data-stock="<?= (int) $product['stock'] ?>">
        <a class="product-image" href="/SS_NOVA_TECHNOLOGY/public/product?id=<?= (int) $product['id'] ?>"><?php if (!empty($product['image_path'])): ?><img src="/SS_NOVA_TECHNOLOGY/public/<?= htmlspecialchars($product['image_path'], ENT_QUOTES, 'UTF-8') ?>" alt="<?= htmlspecialchars($product['name'], ENT_QUOTES, 'UTF-8') ?>" loading="lazy"><?php else: ?><span class="product-image-empty">SSN<span>TECH</span></span><?php endif; ?></a>
        <div class="product-card-copy">
            <p class="product-category"><?= htmlspecialchars($product['category_name'], ENT_QUOTES, 'UTF-8') ?></p>
            <h2><a href="/SS_NOVA_TECHNOLOGY/public/product?id=<?= (int) $product['id'] ?>"><?= htmlspecialchars($product['name'], ENT_QUOTES, 'UTF-8') ?></a></h2>
            <p class="product-price">$ <?= number_format((float) $product['price'], 0, ',', '.') ?> <small>COP</small></p>
            <p class="stock-note<?= (int) $product['stock'] === 0 ? ' is-unavailable' : '' ?>" data-stock-note aria-live="polite"><?= (int) $product['stock'] > 0 ? 'Disponible para envio · ' . (int) $product['stock'] . ' unidades' : 'Sin disponibilidad de envio' ?></p>
            <a class="product-detail-link" href="/SS_NOVA_TECHNOLOGY/public/product?id=<?= (int) $product['id'] ?>">Ver detalles <span aria-hidden="true">&#8594;</span></a>
        </div>
    </article>
<?php endforeach; ?>
</section>
<script>
(() => {
    const grid = document.querySelector('[data-stock-poll]');
    if (!grid) return;

    const cards = Array.from(grid.querySelectorAll('[data-product-id]'));
    let requestPending = false;

    const refreshStock = async () => {
        if (document.hidden || requestPending || cards.length === 0) return;
        requestPending = true;

        try {
            const ids = cards.map((card) => card.dataset.productId).join(',');
            const response = await fetch(`${grid.dataset.stockEndpoint}?ids=${encodeURIComponent(ids)}`, {
                cache: 'no-store',
                headers: { Accept: 'application/json' },
            });
            if (!response.ok) return;

            const { stocks } = await response.json();
            for (const card of cards) {
                const stock = Number(stocks[card.dataset.productId]);
                if (!Number.isInteger(stock) || stock === Number(card.dataset.stock)) continue;

                card.dataset.stock = String(stock);
                const stockNote = card.querySelector('[data-stock-note]');
                const unavailable = stock < 1;
                stockNote.textContent = unavailable ? 'Sin disponibilidad de envio' : `Disponible para envio · ${stock} unidades`;
                stockNote.classList.toggle('is-unavailable', unavailable);
            }
        } catch {
            // A transient network error leaves the last known stock visible.
        } finally {
            requestPending = false;
        }
    };

    refreshStock();
    window.setInterval(refreshStock, 5000);
})();
</script>