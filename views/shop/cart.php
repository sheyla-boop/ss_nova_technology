<section class="catalog-heading"><p class="eyebrow">Tu seleccion</p><h1>Carrito de compras</h1><p>Revisa las cantidades y el precio de cada producto antes de confirmar.</p></section>
<?php if ($products === []): ?>
	<div class="empty-state">Tu carrito esta vacio. <a class="text-link" href="/SS_NOVA_TECHNOLOGY/public/catalog">Ir al catalogo</a></div>
<?php else: ?>
	<div class="cart-layout">
		<div class="cart-items">
			<?php foreach ($products as $product): ?>
				<article class="cart-item">
					<div class="cart-thumb">
						<?php if (!empty($product['image_path'])): ?>
							<img src="/SS_NOVA_TECHNOLOGY/public/<?= htmlspecialchars($product['image_path'], ENT_QUOTES, 'UTF-8') ?>" alt="">
						<?php else: ?>
							<span>SSN</span>
						<?php endif; ?>
					</div>
					<div class="cart-item-copy">
						<h2><?= htmlspecialchars($product['name'], ENT_QUOTES, 'UTF-8') ?></h2>
						<div class="cart-product-details">
							<p class="cart-product-meta">Precio unitario <strong>$ <?= number_format((float) $product['price'], 0, ',', '.') ?> COP</strong></p>
							<p class="cart-product-meta">Cantidad <strong><?= (int) $product['quantity'] ?></strong></p>
						</div>
						<p class="cart-line-total">Total del producto <strong>$ <?= number_format((float) $product['line_total'], 0, ',', '.') ?> COP</strong></p>
					</div>
					<form method="post" action="/SS_NOVA_TECHNOLOGY/public/cart/remove">
						<input type="hidden" name="_csrf" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>">
						<input type="hidden" name="product_id" value="<?= (int) $product['id'] ?>">
						<button class="remove-button" type="submit" title="Eliminar" aria-label="Eliminar <?= htmlspecialchars($product['name'], ENT_QUOTES, 'UTF-8') ?>">
							<svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="M3 6h18M8 6V4h8v2m3 0-.9 14H5.9L5 6m4 4v6m6-6v6" /></svg>
							<span class="remove-label">Eliminar</span>
						</button>
					</form>
				</article>
			<?php endforeach; ?>
		</div>
		<aside class="cart-summary">
			<p class="eyebrow">Resumen</p>
			<h2>Total de productos</h2>
			<p class="summary-total">$ <?= number_format((float) $total, 0, ',', '.') ?> <small>COP</small></p>
			<p class="shipping-note">El costo de envio se confirmara segun tu ubicacion y no esta incluido en este total.</p>
			<div class="payment-choice">
				<span>Forma de pago disponible</span>
				<strong>Pago contraentrega</strong>
				<small>Pagas cuando recibas tu pedido.</small>
			</div>
			<a class="button" href="/SS_NOVA_TECHNOLOGY/public/checkout">Continuar a entrega</a>
			<a class="button button-secondary" href="/SS_NOVA_TECHNOLOGY/public/catalog">Seguir comprando</a>
		</aside>
	</div>
<?php endif; ?>