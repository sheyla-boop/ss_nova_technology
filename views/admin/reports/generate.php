<?php
$reportTitles = [
    'sales' => 'Ventas entregadas',
    'orders' => 'Pedidos recibidos',
    'inventory' => 'Inventario actual',
];
$isInventoryReport = $reportType === 'inventory';
?>
<section class="report-heading">
    <div>
        <p class="eyebrow">SS Nova / reportes</p>
        <h1>Reportes</h1>
        <p>Consulta y prepara la informacion de la tienda.</p>
    </div>
    <div class="report-heading-actions">
        <a class="button button-secondary" href="/SS_NOVA_TECHNOLOGY/public/admin/reports">Volver al panel</a>
        <button class="button report-print-button" type="button" onclick="window.print()">Imprimir / guardar PDF</button>
    </div>
</section>

<form class="report-filter-form" method="get" action="/SS_NOVA_TECHNOLOGY/public/admin/reports/generate">
    <label>Tipo de reporte
        <select name="type">
            <?php foreach ($reportTitles as $type => $label): ?>
                <option value="<?= htmlspecialchars($type, ENT_QUOTES, 'UTF-8') ?>"<?= $reportType === $type ? ' selected' : '' ?>><?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8') ?></option>
            <?php endforeach; ?>
        </select>
    </label>
    <?php if (!$isInventoryReport): ?>
        <label>Desde<input type="date" name="from" value="<?= htmlspecialchars($dateFrom, ENT_QUOTES, 'UTF-8') ?>" required></label>
        <label>Hasta<input type="date" name="to" value="<?= htmlspecialchars($dateTo, ENT_QUOTES, 'UTF-8') ?>" required></label>
    <?php else: ?>
        <p class="inventory-report-note">Este reporte muestra el stock actual.</p>
    <?php endif; ?>
    <button class="button" type="submit">Ver reporte</button>
</form>

<?php if ($dateError !== ''): ?>
    <div class="flash-message flash-error" role="alert"><?= htmlspecialchars($dateError, ENT_QUOTES, 'UTF-8') ?></div>
<?php endif; ?>

<?php if ($report !== null): ?>
    <section class="report-panel generated-report" aria-live="polite">
        <div class="panel-heading">
            <div>
                <p class="eyebrow">Reporte generado</p>
                <h2><?= htmlspecialchars($reportTitles[$reportType], ENT_QUOTES, 'UTF-8') ?></h2>
            </div>
            <?php if (!$isInventoryReport): ?><span class="panel-meta"><?= htmlspecialchars(date('d/m/Y', strtotime($dateFrom)), ENT_QUOTES, 'UTF-8') ?> - <?= htmlspecialchars(date('d/m/Y', strtotime($dateTo)), ENT_QUOTES, 'UTF-8') ?></span><?php endif; ?>
        </div>

        <?php if ($isInventoryReport): ?>
            <div class="report-summary-grid">
                <article class="report-summary-card"><span>Productos activos</span><strong><?= number_format((int) $report['summary']['count'], 0, ',', '.') ?></strong></article>
                <article class="report-summary-card"><span>Unidades disponibles</span><strong><?= number_format((int) $report['summary']['stock_units'], 0, ',', '.') ?></strong></article>
                <article class="report-summary-card"><span>Stock por revisar</span><strong><?= number_format((int) $report['summary']['low_stock'], 0, ',', '.') ?></strong></article>
            </div>
            <?php if ($report['rows'] === []): ?>
                <p class="empty-state">No hay productos activos para mostrar.</p>
            <?php else: ?>
                <div class="table-wrap report-table"><table><thead><tr><th>SKU</th><th>Producto</th><th>Categoria</th><th>Existencias</th><th>Precio</th><th>Estado</th></tr></thead><tbody>
                    <?php foreach ($report['rows'] as $row): ?>
                        <tr><td><?= htmlspecialchars($row['sku'], ENT_QUOTES, 'UTF-8') ?></td><td><?= htmlspecialchars($row['product_name'], ENT_QUOTES, 'UTF-8') ?></td><td><?= htmlspecialchars($row['category_name'], ENT_QUOTES, 'UTF-8') ?></td><td><?= number_format((int) $row['stock'], 0, ',', '.') ?></td><td>$ <?= number_format((float) $row['price'], 0, ',', '.') ?> COP</td><td><span class="status-tag<?= (int) $row['stock'] <= (int) $row['low_stock_threshold'] ? ' status-low-stock' : '' ?>"><?= (int) $row['stock'] <= (int) $row['low_stock_threshold'] ? 'Por revisar' : 'Disponible' ?></span></td></tr>
                    <?php endforeach; ?>
                </tbody></table></div>
            <?php endif; ?>
        <?php else: ?>
            <div class="report-summary-grid">
                <article class="report-summary-card"><span><?= $reportType === 'sales' ? 'Pedidos entregados' : 'Pedidos registrados' ?></span><strong><?= number_format((int) $report['summary']['count'], 0, ',', '.') ?></strong></article>
                <article class="report-summary-card"><span><?= $reportType === 'sales' ? 'Ingresos entregados' : 'Valor de los pedidos' ?></span><strong>$ <?= number_format((float) $report['summary']['total'], 0, ',', '.') ?> <small>COP</small></strong></article>
                <article class="report-summary-card"><span>Unidades</span><strong><?= number_format((int) $report['summary']['units'], 0, ',', '.') ?></strong></article>
            </div>
            <?php if ($report['rows'] === []): ?>
                <p class="empty-state">No hay registros para el tipo y periodo seleccionados.</p>
            <?php else: ?>
                <div class="table-wrap report-table"><table><thead><tr><th>Pedido</th><th>Cliente</th><th>Fecha</th><th>Estado</th><th>Pago</th><th>Unidades</th><th>Total</th></tr></thead><tbody>
                    <?php foreach ($report['rows'] as $row): ?>
                        <tr><td><?= htmlspecialchars($row['order_number'], ENT_QUOTES, 'UTF-8') ?></td><td><?= htmlspecialchars($row['customer_name'], ENT_QUOTES, 'UTF-8') ?></td><td><?= htmlspecialchars($row['order_date'], ENT_QUOTES, 'UTF-8') ?></td><td><span class="status-tag"><?= htmlspecialchars($row['status_name'], ENT_QUOTES, 'UTF-8') ?></span></td><td><?= htmlspecialchars($row['payment_method'], ENT_QUOTES, 'UTF-8') ?></td><td><?= number_format((int) $row['units'], 0, ',', '.') ?></td><td>$ <?= number_format((float) $row['total_amount'], 0, ',', '.') ?> COP</td></tr>
                    <?php endforeach; ?>
                </tbody></table></div>
            <?php endif; ?>
        <?php endif; ?>
    </section>
<?php endif; ?>