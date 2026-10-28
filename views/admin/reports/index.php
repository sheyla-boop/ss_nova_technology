<?php
$maxRevenue = max(1, ...array_map(static fn (array $month): float => (float) $month['revenue'], $monthlySales ?: [['revenue' => 1]]));
?>
<section class="report-heading"><div><p class="eyebrow">SS Nova / inteligencia comercial</p><h1>Panel de empresa</h1><p>Una vista rapida del rendimiento de la tienda.</p></div><div class="report-heading-actions"><a class="button" href="/SS_NOVA_TECHNOLOGY/public/admin/reports/generate">Consultar reportes</a><span class="report-live"><i></i> Datos actualizados al momento</span></div></section>
<section class="metric-grid" aria-label="Indicadores de la empresa">
    <article class="metric-card metric-revenue"><span>Ingresos entregados</span><strong>$ <?= number_format((float) $metrics['revenue'], 0, ',', '.') ?></strong><small>COP · pedidos entregados</small></article>
    <article class="metric-card metric-volume"><span>Unidades vendidas</span><strong><?= number_format((int) $metrics['units_sold'], 0, ',', '.') ?></strong><small>Unidades en pedidos entregados</small></article>
    <article class="metric-card metric-orders"><span>Pedidos recibidos</span><strong><?= number_format((int) $metrics['orders_count'], 0, ',', '.') ?></strong><small><?= (int) $metrics['delivered_orders'] ?> entregados</small></article>
    <article class="metric-card metric-alert"><span>Stock por revisar</span><strong><?= number_format((int) $metrics['low_stock_count'], 0, ',', '.') ?></strong><small>Productos en nivel bajo</small></article>
</section>
<section class="report-columns">
    <article class="report-panel sales-panel"><div class="panel-heading"><div><p class="eyebrow">Ultimos seis meses</p><h2>Ventas entregadas</h2></div><span class="chart-legend"><i></i> Ingresos COP</span></div>
        <?php if ($monthlySales === []): ?><p class="empty-state">Aun no hay pedidos entregados para mostrar.</p><?php else: ?><div class="sales-chart" role="img" aria-label="Ingresos mensuales de los ultimos seis meses"><?php foreach ($monthlySales as $month): $barHeight = max(4, (int) round(((float) $month['revenue'] / $maxRevenue) * 100)); ?><div class="chart-column"><strong>$<?= number_format((float) $month['revenue'], 0, ',', '.') ?></strong><div class="chart-track"><span style="height: <?= $barHeight ?>%"></span></div><small><?= htmlspecialchars($month['month_label'], ENT_QUOTES, 'UTF-8') ?></small></div><?php endforeach; ?></div><?php endif; ?>
    </article>
    <article class="report-panel"><div class="panel-heading"><div><p class="eyebrow">Rendimiento</p><h2>Mas vendidos</h2></div></div>
        <?php if ($topProducts === []): ?><p class="empty-state">Los productos vendidos apareceran cuando se entreguen pedidos.</p><?php else: ?><ol class="top-products"><?php foreach ($topProducts as $index => $product): ?><li><span class="rank-number">0<?= $index + 1 ?></span><div class="top-product-image"><?php if (!empty($product['image_path'])): ?><img src="/SS_NOVA_TECHNOLOGY/public/<?= htmlspecialchars($product['image_path'], ENT_QUOTES, 'UTF-8') ?>" alt="<?= htmlspecialchars($product['product_name'], ENT_QUOTES, 'UTF-8') ?>" loading="lazy"><?php else: ?><span>SSN</span><?php endif; ?></div><div class="top-product-copy"><strong><?= htmlspecialchars($product['product_name'], ENT_QUOTES, 'UTF-8') ?></strong><small><?= (int) $product['units_sold'] ?> unidades</small></div><b>$<?= number_format((float) $product['revenue'], 0, ',', '.') ?></b></li><?php endforeach; ?></ol><?php endif; ?>
    </article>
</section>
<section class="report-panel recent-panel"><div class="panel-heading"><div><p class="eyebrow">Actividad</p><h2>Pedidos recientes</h2></div><span class="panel-meta"><?= count($recentOrders) ?> recientes</span></div>
    <p class="order-report-note">El total de pedidos cuenta todas las solicitudes activas. Ingresos, unidades y productos vendidos se actualizan cuando el pedido pasa a <strong>Entregado</strong>.</p>
    <?php if ($recentOrders === []): ?><p class="empty-state">Todavia no se han recibido pedidos.</p><?php else: ?><div class="table-wrap report-table"><table><thead><tr><th>Pedido</th><th>Cliente</th><th>Fecha</th><th>Estado</th><th>Total</th><th>Actualizar</th></tr></thead><tbody><?php foreach ($recentOrders as $order): ?><tr><td><?= htmlspecialchars($order['order_number'], ENT_QUOTES, 'UTF-8') ?></td><td><?= htmlspecialchars($order['customer_name'], ENT_QUOTES, 'UTF-8') ?></td><td><?= htmlspecialchars(date('d/m/Y', strtotime($order['created_at'])), ENT_QUOTES, 'UTF-8') ?></td><td><span class="status-tag"><?= htmlspecialchars($order['status_name'], ENT_QUOTES, 'UTF-8') ?></span></td><td>$ <?= number_format((float) $order['total_amount'], 0, ',', '.') ?></td><td><?php if (!in_array($order['status_code'], ['delivered', 'cancelled'], true)): ?><form class="order-status-form" method="post" action="/SS_NOVA_TECHNOLOGY/public/admin/reports/orders/status"><input type="hidden" name="_csrf" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>"><input type="hidden" name="order_id" value="<?= (int) $order['id'] ?>"><label class="visually-hidden" for="order-status-<?= (int) $order['id'] ?>">Estado para <?= htmlspecialchars($order['order_number'], ENT_QUOTES, 'UTF-8') ?></label><select id="order-status-<?= (int) $order['id'] ?>" name="status_code"><?php foreach ($orderStatuses as $status): ?><option value="<?= htmlspecialchars($status['code'], ENT_QUOTES, 'UTF-8') ?>"<?= $status['code'] === $order['status_code'] ? ' selected' : '' ?>><?= htmlspecialchars($status['name'], ENT_QUOTES, 'UTF-8') ?></option><?php endforeach; ?></select><button class="button" type="submit">Guardar</button></form><?php else: ?><span class="panel-meta">Estado final</span><?php endif; ?></td></tr><?php endforeach; ?></tbody></table></div><?php endif; ?>
</section>
<section class="report-panel supplier-contact-panel" id="contacto">
    <div class="panel-heading">
        <div><p class="eyebrow">Directorio interno</p><h2>Contactos de proveedores</h2></div>
        <a class="panel-action" href="/SS_NOVA_TECHNOLOGY/public/admin/suppliers">Administrar proveedores <span aria-hidden="true">&#8594;</span></a>
    </div>
    <?php if ($supplierContacts === []): ?>
        <p class="empty-state">Aun no hay proveedores activos registrados.</p>
    <?php else: ?>
        <div class="supplier-contact-grid">
            <?php foreach ($supplierContacts as $supplier): ?>
                <article class="supplier-contact-card">
                    <h3><?= htmlspecialchars($supplier['name'], ENT_QUOTES, 'UTF-8') ?></h3>
                    <?php if (!empty($supplier['contact_name'])): ?><p><?= htmlspecialchars($supplier['contact_name'], ENT_QUOTES, 'UTF-8') ?></p><?php endif; ?>
                    <?php if (!empty($supplier['email'])): ?><a href="mailto:<?= htmlspecialchars($supplier['email'], ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($supplier['email'], ENT_QUOTES, 'UTF-8') ?></a><?php endif; ?>
                    <?php if (!empty($supplier['phone'])): ?><a href="tel:<?= htmlspecialchars($supplier['phone'], ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($supplier['phone'], ENT_QUOTES, 'UTF-8') ?></a><?php endif; ?>
                    <?php if (empty($supplier['email']) && empty($supplier['phone'])): ?><small>Sin telefono o correo registrado</small><?php endif; ?>
                </article>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>
<section class="management-links" aria-label="Gestion de la empresa">
    <a class="management-tile tile-products" href="/SS_NOVA_TECHNOLOGY/public/admin/products"><span>01 / Catalogo</span><strong>Productos y stock</strong></a>
    <a class="management-tile tile-clients" href="/SS_NOVA_TECHNOLOGY/public/admin/clients"><span>02 / Comunidad</span><strong>Clientes</strong></a>
    <a class="management-tile tile-users" href="/SS_NOVA_TECHNOLOGY/public/admin/users"><span>03 / Acceso</span><strong>Usuarios</strong></a>
    <a class="management-tile tile-suppliers" href="/SS_NOVA_TECHNOLOGY/public/admin/suppliers"><span>04 / Abastecimiento</span><strong>Proveedores</strong></a>
</section>