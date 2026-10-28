<section class="catalog-heading"><p class="eyebrow">Ultimo paso</p><h1>Entrega y pago</h1><p>Completa tu ubicacion para coordinar la entrega. Pagas al recibir el pedido.</p></section>
<div class="checkout-layout">
	<form class="admin-form checkout-form" method="post" action="/SS_NOVA_TECHNOLOGY/public/checkout">
		<input type="hidden" name="_csrf" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>">
		<label>Nombre de quien recibe<input name="recipient_name" required maxlength="160" autocomplete="name"></label>
		<label>Telefono de contacto<input name="recipient_phone" required maxlength="30" autocomplete="tel"></label>
		<label>Direccion de entrega<input name="shipping_address" required maxlength="255" autocomplete="street-address" placeholder="Calle, carrera, numero y barrio"></label>
		<div class="checkout-address-grid">
			<label>Ciudad o municipio<input name="shipping_city" required maxlength="100" autocomplete="address-level2"></label>
			<label>Departamento<input name="shipping_state" maxlength="100" autocomplete="address-level1"></label>
			<label>Codigo postal <span class="form-help">Opcional</span><input name="shipping_postal_code" maxlength="20" autocomplete="postal-code"></label>
			<label>Barrio, conjunto o referencia <span class="form-help">Opcional</span><input name="shipping_address_extra" maxlength="255" placeholder="Torre, apartamento, indicaciones"></label>
		</div>
		<label>Indicaciones para la entrega <span class="form-help">Opcional</span><textarea name="customer_notes" rows="3" maxlength="500" placeholder="Por ejemplo, horario o punto de referencia"></textarea></label>
		<div class="payment-choice">
			<span>Metodo de pago</span>
			<strong>Pago contraentrega</strong>
			<small>Pagas cuando recibas el pedido.</small>
		</div>
		<button class="button" type="submit">Confirmar pedido</button>
	</form>
	<aside class="cart-summary">
		<p class="eyebrow">Tu pedido</p>
		<?php foreach ($products as $product): ?>
			<div class="checkout-line">
				<span><?= (int) $product['quantity'] ?> × <?= htmlspecialchars($product['name'], ENT_QUOTES, 'UTF-8') ?></span>
				<strong>$ <?= number_format((float) $product['line_total'], 0, ',', '.') ?></strong>
			</div>
		<?php endforeach; ?>
		<hr>
		<p class="checkout-subtotal">Total de productos</p>
		<p class="summary-total">$ <?= number_format((float) $total, 0, ',', '.') ?> <small>COP</small></p>
		<p class="shipping-note">El costo de envio se confirmara segun tu ubicacion y no esta incluido en este total.</p>
	</aside>
</div>