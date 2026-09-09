<?php
$tab = $tab ?? 'ventas';
?>

<div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
    <div>
        <h4 class="fw-bold mb-1" style="color: var(--brand-dark);">
            <i class="bi bi-bar-chart-line-fill text-primary me-2"></i>Centro de Reportes y Estadísticas
        </h4>
        <p class="text-muted mb-0 small">Genera y exporta reportes detallados del negocio en formatos PDF y Excel.</p>
    </div>

    <!-- Acciones de Exportación Rápidas para la Pestaña Activa -->
    <div class="d-flex gap-2">
        <?php
            // Construir query string con todos los filtros activos
            $queryParams = http_build_query(array_merge($filtros, ['tipo' => $tab]));
        ?>
        <a href="index.php?action=reporte-exportar-excel&<?= $queryParams ?>" class="btn btn-success btn-sm d-flex align-items-center gap-2 shadow-sm" title="Descargar en formato Excel (.xls)">
            <i class="bi bi-file-earmark-excel fs-6"></i>
            <span>Exportar Excel</span>
        </a>
        <a href="index.php?action=reporte-exportar-pdf&<?= $queryParams ?>" target="_blank" class="btn btn-danger btn-sm d-flex align-items-center gap-2 shadow-sm" title="Abrir y descargar vista PDF">
            <i class="bi bi-file-earmark-pdf fs-6"></i>
            <span>Exportar PDF</span>
        </a>
    </div>
</div>

<!-- Barra de Navegación de Pestañas -->
<ul class="nav nav-tabs border-bottom mb-4">
    <li class="nav-item">
        <a class="nav-link px-3 <?= $tab === 'ventas' ? 'active fw-bold' : 'text-secondary' ?>" href="index.php?action=reportes&tab=ventas">
            <i class="bi bi-cash-stack me-1 text-primary"></i> Reporte de Ventas
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link px-3 <?= $tab === 'compras' ? 'active fw-bold' : 'text-secondary' ?>" href="index.php?action=reportes&tab=compras">
            <i class="bi bi-cart-check me-1 text-info"></i> Reporte de Compras
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link px-3 <?= $tab === 'inventario' ? 'active fw-bold' : 'text-secondary' ?>" href="index.php?action=reportes&tab=inventario">
            <i class="bi bi-box-seam me-1 text-warning"></i> Inventario y Valorización
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link px-3 <?= $tab === 'top' ? 'active fw-bold' : 'text-secondary' ?>" href="index.php?action=reportes&tab=top">
            <i class="bi bi-trophy me-1 text-danger"></i> Productos Más Vendidos
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link px-3 <?= $tab === 'balance' ? 'active fw-bold' : 'text-secondary' ?>" href="index.php?action=reportes&tab=balance">
            <i class="bi bi-graph-up-arrow me-1 text-success"></i> Balance Financiero
        </a>
    </li>
</ul>

<!-- ========================================== -->
<!-- 1. PESTAÑA: REPORTE DE VENTAS -->
<!-- ========================================== -->
<?php if ($tab === 'ventas'): ?>
    <!-- Tarjetas de Resumen KPI -->
    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-lg-3">
            <div class="card stat-card shadow-sm h-100 border-start border-primary border-4">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="stat-icon bg-primary-subtle text-primary"><i class="bi bi-cash-stack"></i></div>
                    <div>
                        <div class="text-muted small">Total Recaudado</div>
                        <div class="fs-4 fw-bold text-dark">Bs. <?= number_format((float)($resumen['total_ingresos'] ?? 0), 2) ?></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="card stat-card shadow-sm h-100 border-start border-success border-4">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="stat-icon bg-success-subtle text-success"><i class="bi bi-receipt"></i></div>
                    <div>
                        <div class="text-muted small">Ventas Registradas</div>
                        <div class="fs-4 fw-bold text-dark"><?= (int)($resumen['total_operaciones'] ?? 0) ?></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="card stat-card shadow-sm h-100 border-start border-info border-4">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="stat-icon bg-info-subtle text-info"><i class="bi bi-boxes"></i></div>
                    <div>
                        <div class="text-muted small">Unidades Vendidas</div>
                        <div class="fs-4 fw-bold text-dark"><?= (int)($resumen['total_unidades'] ?? 0) ?></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="card stat-card shadow-sm h-100 border-start border-warning border-4">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="stat-icon bg-warning-subtle text-warning"><i class="bi bi-calculator"></i></div>
                    <div>
                        <div class="text-muted small">Ticket Promedio</div>
                        <div class="fs-4 fw-bold text-dark">Bs. <?= number_format((float)($resumen['ticket_promedio'] ?? 0), 2) ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtros de Búsqueda -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form method="get" action="index.php" class="row g-3 align-items-end">
                <input type="hidden" name="action" value="reportes">
                <input type="hidden" name="tab" value="ventas">

                <div class="col-md-3">
                    <label class="form-label small fw-semibold text-secondary">Fecha Desde</label>
                    <input type="date" name="fecha_desde" class="form-control form-control-sm" value="<?= htmlspecialchars($filtros['fecha_desde']) ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-semibold text-secondary">Fecha Hasta</label>
                    <input type="date" name="fecha_hasta" class="form-control form-control-sm" value="<?= htmlspecialchars($filtros['fecha_hasta']) ?>">
                </div>
                <div class="col-md-4">
                    <label class="form-label small fw-semibold text-secondary">Cliente</label>
                    <input type="text" name="cliente" class="form-control form-control-sm" placeholder="Buscar por nombre de cliente..." value="<?= htmlspecialchars($filtros['cliente']) ?>">
                </div>
                <div class="col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-primary btn-sm w-100"><i class="bi bi-funnel me-1"></i> Filtrar</button>
                    <a href="index.php?action=reportes&tab=ventas" class="btn btn-outline-secondary btn-sm" title="Limpiar filtros"><i class="bi bi-arrow-counterclockwise"></i></a>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabla de Resultados -->
    <div class="card shadow-sm">
        <div class="card-header bg-white py-3">
            <h6 class="mb-0 fw-bold"><i class="bi bi-table me-2"></i>Detalle de Transacciones de Ventas</h6>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 table-datatable">
                <thead class="table-light">
                    <tr>
                        <th>N° Venta</th>
                        <th>Fecha y Hora</th>
                        <th>Cliente</th>
                        <th class="text-center">Artículos</th>
                        <th class="text-end">Descuento</th>
                        <th class="text-end">Total</th>
                        <th class="text-end">Acción</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($datos as $v): ?>
                        <tr>
                            <td class="fw-bold">#<?= str_pad($v['id'], 5, '0', STR_PAD_LEFT) ?></td>
                            <td><?= date('d/m/Y H:i', strtotime($v['fecha_venta'])) ?></td>
                            <td><?= htmlspecialchars($v['cliente_nombre'] ?? 'Consumidor Final') ?></td>
                            <td class="text-center"><span class="badge bg-light text-dark border"><?= (int)$v['total_articulos'] ?></span></td>
                            <td class="text-end text-danger"><?= (float)$v['descuento'] > 0 ? 'Bs. ' . number_format((float)$v['descuento'], 2) : '—' ?></td>
                            <td class="text-end fw-bold text-success">Bs. <?= number_format((float)$v['total'], 2) ?></td>
                            <td class="text-end">
                                <a href="index.php?action=venta-ver&id=<?= $v['id'] ?>" class="btn btn-xs btn-outline-secondary btn-sm" title="Ver comprobante de venta">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

<!-- ========================================== -->
<!-- 2. PESTAÑA: REPORTE DE COMPRAS -->
<!-- ========================================== -->
<?php elseif ($tab === 'compras'): ?>
    <!-- Tarjetas de Resumen KPI -->
    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-lg-4">
            <div class="card stat-card shadow-sm h-100 border-start border-info border-4">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="stat-icon bg-info-subtle text-info"><i class="bi bi-cart-check"></i></div>
                    <div>
                        <div class="text-muted small">Total Invertido en Compras</div>
                        <div class="fs-4 fw-bold text-dark">Bs. <?= number_format((float)($resumen['total_egresos'] ?? 0), 2) ?></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-4">
            <div class="card stat-card shadow-sm h-100 border-start border-primary border-4">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="stat-icon bg-primary-subtle text-primary"><i class="bi bi-bag-check"></i></div>
                    <div>
                        <div class="text-muted small">Compras Realizadas</div>
                        <div class="fs-4 fw-bold text-dark"><?= (int)($resumen['total_operaciones'] ?? 0) ?></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-4">
            <div class="card stat-card shadow-sm h-100 border-start border-success border-4">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="stat-icon bg-success-subtle text-success"><i class="bi bi-box2"></i></div>
                    <div>
                        <div class="text-muted small">Artículos Adquiridos</div>
                        <div class="fs-4 fw-bold text-dark"><?= (int)($resumen['total_unidades'] ?? 0) ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtros de Búsqueda -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form method="get" action="index.php" class="row g-3 align-items-end">
                <input type="hidden" name="action" value="reportes">
                <input type="hidden" name="tab" value="compras">

                <div class="col-md-3">
                    <label class="form-label small fw-semibold text-secondary">Fecha Desde</label>
                    <input type="date" name="fecha_desde" class="form-control form-control-sm" value="<?= htmlspecialchars($filtros['fecha_desde']) ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-semibold text-secondary">Fecha Hasta</label>
                    <input type="date" name="fecha_hasta" class="form-control form-control-sm" value="<?= htmlspecialchars($filtros['fecha_hasta']) ?>">
                </div>
                <div class="col-md-4">
                    <label class="form-label small fw-semibold text-secondary">Proveedor</label>
                    <select name="proveedor_id" class="form-select form-select-sm">
                        <option value="">Todos los proveedores</option>
                        <?php foreach ($proveedores as $prov): ?>
                            <option value="<?= $prov['id'] ?>" <?= $filtros['proveedor_id'] == $prov['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($prov['nombre']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-info text-white btn-sm w-100"><i class="bi bi-funnel me-1"></i> Filtrar</button>
                    <a href="index.php?action=reportes&tab=compras" class="btn btn-outline-secondary btn-sm" title="Limpiar filtros"><i class="bi bi-arrow-counterclockwise"></i></a>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabla de Compras -->
    <div class="card shadow-sm">
        <div class="card-header bg-white py-3">
            <h6 class="mb-0 fw-bold"><i class="bi bi-table me-2"></i>Historial de Compras</h6>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 table-datatable">
                <thead class="table-light">
                    <tr>
                        <th>N° Compra</th>
                        <th>Fecha y Hora</th>
                        <th>Proveedor</th>
                        <th class="text-center">Artículos Comprados</th>
                        <th class="text-end">Total Invertido</th>
                        <th class="text-end">Acción</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($datos as $c): ?>
                        <tr>
                            <td class="fw-bold">#<?= str_pad($c['id'], 5, '0', STR_PAD_LEFT) ?></td>
                            <td><?= date('d/m/Y H:i', strtotime($c['fecha_compra'])) ?></td>
                            <td><?= htmlspecialchars($c['proveedor_nombre'] ?? 'Sin Proveedor') ?></td>
                            <td class="text-center"><span class="badge bg-light text-dark border"><?= (int)$c['total_articulos'] ?></span></td>
                            <td class="text-end fw-bold text-primary">Bs. <?= number_format((float)$c['total'], 2) ?></td>
                            <td class="text-end">
                                <a href="index.php?action=compra-ver&id=<?= $c['id'] ?>" class="btn btn-xs btn-outline-secondary btn-sm" title="Ver comprobante de compra">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

<!-- ========================================== -->
<!-- 3. PESTAÑA: INVENTARIO Y VALORIZACIÓN -->
<!-- ========================================== -->
<?php elseif ($tab === 'inventario'): ?>
    <!-- Tarjetas de Resumen KPI -->
    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-lg-3">
            <div class="card stat-card shadow-sm h-100 border-start border-primary border-4">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="stat-icon bg-primary-subtle text-primary"><i class="bi bi-box-seam"></i></div>
                    <div>
                        <div class="text-muted small">Total Artículos / Stock</div>
                        <div class="fs-4 fw-bold text-dark"><?= (int)$resumen['total_articulos'] ?> <small class="fs-6 text-muted">(<?= (int)$resumen['total_stock'] ?> unid.)</small></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="card stat-card shadow-sm h-100 border-start border-secondary border-4">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="stat-icon bg-secondary-subtle text-secondary"><i class="bi bi-tag"></i></div>
                    <div>
                        <div class="text-muted small">Valor Costo Total</div>
                        <div class="fs-4 fw-bold text-dark">Bs. <?= number_format((float)$resumen['valor_costo_total'], 2) ?></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="card stat-card shadow-sm h-100 border-start border-success border-4">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="stat-icon bg-success-subtle text-success"><i class="bi bi-currency-dollar"></i></div>
                    <div>
                        <div class="text-muted small">Valor Venta Estimado</div>
                        <div class="fs-4 fw-bold text-dark">Bs. <?= number_format((float)$resumen['valor_venta_total'], 2) ?></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="card stat-card shadow-sm h-100 border-start border-warning border-4">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="stat-icon bg-warning-subtle text-warning"><i class="bi bi-exclamation-triangle"></i></div>
                    <div>
                        <div class="text-muted small">Bajo Stock / Agotados</div>
                        <div class="fs-4 fw-bold text-danger"><?= (int)$resumen['articulos_bajo_stock'] ?> / <?= (int)$resumen['articulos_agotados'] ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtros de Inventario -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form method="get" action="index.php" class="row g-3 align-items-end">
                <input type="hidden" name="action" value="reportes">
                <input type="hidden" name="tab" value="inventario">

                <div class="col-md-3">
                    <label class="form-label small fw-semibold text-secondary">Categoría</label>
                    <select name="categoria_id" class="form-select form-select-sm">
                        <option value="">Todas las categorías</option>
                        <?php foreach ($categorias as $cat): ?>
                            <option value="<?= $cat['id'] ?>" <?= $filtros['categoria_id'] == $cat['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($cat['nombre']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-semibold text-secondary">Marca</label>
                    <select name="marca_id" class="form-select form-select-sm">
                        <option value="">Todas las marcas</option>
                        <?php foreach ($marcas as $m): ?>
                            <option value="<?= $m['id'] ?>" <?= $filtros['marca_id'] == $m['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($m['nombre']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-semibold text-secondary">Estado de Stock</label>
                    <select name="estado_stock" class="form-select form-select-sm">
                        <option value="">Todos los niveles</option>
                        <option value="disponible" <?= $filtros['estado_stock'] === 'disponible' ? 'selected' : '' ?>>Stock Normal (> 10)</option>
                        <option value="bajo" <?= $filtros['estado_stock'] === 'bajo' ? 'selected' : '' ?>>Bajo Stock (&le; 10)</option>
                        <option value="agotado" <?= $filtros['estado_stock'] === 'agotado' ? 'selected' : '' ?>>Agotados (0)</option>
                    </select>
                </div>
                <div class="col-md-3 d-flex gap-2">
                    <button type="submit" class="btn btn-warning text-dark fw-semibold btn-sm w-100"><i class="bi bi-funnel me-1"></i> Filtrar</button>
                    <a href="index.php?action=reportes&tab=inventario" class="btn btn-outline-secondary btn-sm" title="Limpiar filtros"><i class="bi bi-arrow-counterclockwise"></i></a>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabla de Inventario -->
    <div class="card shadow-sm">
        <div class="card-header bg-white py-3">
            <h6 class="mb-0 fw-bold"><i class="bi bi-table me-2"></i>Valorización de Artículos en Inventario</h6>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 table-datatable">
                <thead class="table-light">
                    <tr>
                        <th>Artículo</th>
                        <th>Categoría</th>
                        <th>Marca</th>
                        <th class="text-center">Stock</th>
                        <th class="text-end">P. Compra</th>
                        <th class="text-end">P. Venta</th>
                        <th class="text-end">Valor Costo</th>
                        <th class="text-end">Valor Venta</th>
                        <th class="text-end">Ganancia Estimada</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($datos as $art): ?>
                        <tr>
                            <td class="fw-semibold"><?= htmlspecialchars($art['nombre']) ?></td>
                            <td><span class="badge badge-cat"><?= htmlspecialchars($art['categoria_nombre'] ?? '—') ?></span></td>
                            <td><?= htmlspecialchars($art['marca_nombre'] ?? '—') ?></td>
                            <td class="text-center">
                                <span class="badge <?= $art['stock'] <= 0 ? 'text-bg-danger' : ($art['stock'] <= 10 ? 'text-bg-warning' : 'text-bg-success') ?>">
                                    <?= (int)$art['stock'] ?>
                                </span>
                            </td>
                            <td class="text-end">Bs. <?= number_format((float)$art['precio_compra'], 2) ?></td>
                            <td class="text-end">Bs. <?= number_format((float)$art['precio_venta'], 2) ?></td>
                            <td class="text-end text-muted">Bs. <?= number_format((float)$art['valor_total_costo'], 2) ?></td>
                            <td class="text-end fw-semibold text-primary">Bs. <?= number_format((float)$art['valor_total_venta'], 2) ?></td>
                            <td class="text-end fw-bold text-success">Bs. <?= number_format((float)$art['ganancia_potencial'], 2) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

<!-- ========================================== -->
<!-- 4. PESTAÑA: PRODUCTOS MÁS VENDIDOS -->
<!-- ========================================== -->
<?php elseif ($tab === 'top'): ?>
    <!-- Filtros de Top Vendidos -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form method="get" action="index.php" class="row g-3 align-items-end">
                <input type="hidden" name="action" value="reportes">
                <input type="hidden" name="tab" value="top">

                <div class="col-md-3">
                    <label class="form-label small fw-semibold text-secondary">Fecha Desde</label>
                    <input type="date" name="fecha_desde" class="form-control form-control-sm" value="<?= htmlspecialchars($filtros['fecha_desde']) ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-semibold text-secondary">Fecha Hasta</label>
                    <input type="date" name="fecha_hasta" class="form-control form-control-sm" value="<?= htmlspecialchars($filtros['fecha_hasta']) ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-semibold text-secondary">Cantidad a mostrar</label>
                    <select name="limite" class="form-select form-select-sm">
                        <option value="5" <?= $filtros['limite'] == 5 ? 'selected' : '' ?>>Top 5</option>
                        <option value="10" <?= $filtros['limite'] == 10 ? 'selected' : '' ?>>Top 10</option>
                        <option value="25" <?= $filtros['limite'] == 25 ? 'selected' : '' ?>>Top 25</option>
                        <option value="50" <?= $filtros['limite'] == 50 ? 'selected' : '' ?>>Top 50</option>
                    </select>
                </div>
                <div class="col-md-3 d-flex gap-2">
                    <button type="submit" class="btn btn-danger btn-sm w-100"><i class="bi bi-funnel me-1"></i> Filtrar</button>
                    <a href="index.php?action=reportes&tab=top" class="btn btn-outline-secondary btn-sm" title="Limpiar filtros"><i class="bi bi-arrow-counterclockwise"></i></a>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabla Top Vendidos -->
    <div class="card shadow-sm">
        <div class="card-header bg-white py-3">
            <h6 class="mb-0 fw-bold"><i class="bi bi-trophy-fill text-warning me-2"></i>Ranking de Productos Más Vendidos</h6>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width: 80px;" class="text-center">Posición</th>
                        <th>Artículo</th>
                        <th>Categoría</th>
                        <th>Marca</th>
                        <th class="text-end">Precio Unit.</th>
                        <th class="text-center">Unid. Vendidas</th>
                        <th class="text-end">Total Recaudado</th>
                        <th class="text-end">Ganancia Estimada</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($datos)): ?>
                        <tr><td colspan="8" class="text-center text-muted py-4">No se registran ventas en el periodo seleccionado.</td></tr>
                    <?php else: ?>
                        <?php $pos = 1; foreach ($datos as $item): ?>
                            <tr>
                                <td class="text-center">
                                    <?php if ($pos === 1): ?>
                                        <span class="badge bg-warning text-dark fs-6 rounded-pill px-3">🥇 1°</span>
                                    <?php elseif ($pos === 2): ?>
                                        <span class="badge bg-secondary-subtle text-secondary fs-6 rounded-pill px-3">🥈 2°</span>
                                    <?php elseif ($pos === 3): ?>
                                        <span class="badge bg-warning-subtle text-dark fs-6 rounded-pill px-3">🥉 3°</span>
                                    <?php else: ?>
                                        <span class="badge bg-light text-muted border rounded-pill px-2"><?= $pos ?>°</span>
                                    <?php endif; ?>
                                </td>
                                <td class="fw-bold"><?= htmlspecialchars($item['nombre']) ?></td>
                                <td><span class="badge badge-cat"><?= htmlspecialchars($item['categoria_nombre'] ?? '—') ?></span></td>
                                <td><?= htmlspecialchars($item['marca_nombre'] ?? '—') ?></td>
                                <td class="text-end">Bs. <?= number_format((float)$item['precio_venta'], 2) ?></td>
                                <td class="text-center fw-bold fs-6 text-primary"><?= (int)$item['total_unidades_vendidas'] ?></td>
                                <td class="text-end fw-bold text-success">Bs. <?= number_format((float)$item['total_recaudado'], 2) ?></td>
                                <td class="text-end fw-bold text-info">Bs. <?= number_format((float)$item['ganancia_estimada'], 2) ?></td>
                            </tr>
                        <?php $pos++; endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

<!-- ========================================== -->
<!-- 5. PESTAÑA: BALANCE GENERAL FINANCIERO -->
<!-- ========================================== -->
<?php elseif ($tab === 'balance'): ?>
    <!-- Filtros de Balance -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form method="get" action="index.php" class="row g-3 align-items-end">
                <input type="hidden" name="action" value="reportes">
                <input type="hidden" name="tab" value="balance">

                <div class="col-md-4">
                    <label class="form-label small fw-semibold text-secondary">Fecha Desde</label>
                    <input type="date" name="fecha_desde" class="form-control form-control-sm" value="<?= htmlspecialchars($filtros['fecha_desde']) ?>">
                </div>
                <div class="col-md-4">
                    <label class="form-label small fw-semibold text-secondary">Fecha Hasta</label>
                    <input type="date" name="fecha_hasta" class="form-control form-control-sm" value="<?= htmlspecialchars($filtros['fecha_hasta']) ?>">
                </div>
                <div class="col-md-4 d-flex gap-2">
                    <button type="submit" class="btn btn-success btn-sm w-100"><i class="bi bi-funnel me-1"></i> Filtrar Periodo</button>
                    <a href="index.php?action=reportes&tab=balance" class="btn btn-outline-secondary btn-sm" title="Limpiar filtros"><i class="bi bi-arrow-counterclockwise"></i></a>
                </div>
            </form>
        </div>
    </div>

    <!-- Comparativa Financiera -->
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="card stat-card shadow-sm h-100 border-start border-success border-4">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <span class="text-muted fw-semibold">Ingresos por Ventas</span>
                        <div class="stat-icon bg-success-subtle text-success"><i class="bi bi-arrow-up-circle-fill"></i></div>
                    </div>
                    <div class="fs-3 fw-bold text-success mb-2">Bs. <?= number_format((float)$resumen['total_ingresos'], 2) ?></div>
                    <div class="small text-muted">
                        <i class="bi bi-receipt me-1"></i> <?= (int)$resumen['total_ventas'] ?> ventas registradas
                        <br><i class="bi bi-box-seam me-1"></i> <?= (int)$resumen['unidades_vendidas'] ?> artículos vendidos
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card stat-card shadow-sm h-100 border-start border-danger border-4">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <span class="text-muted fw-semibold">Egresos por Compras</span>
                        <div class="stat-icon bg-danger-subtle text-danger"><i class="bi bi-arrow-down-circle-fill"></i></div>
                    </div>
                    <div class="fs-3 fw-bold text-danger mb-2">Bs. <?= number_format((float)$resumen['total_egresos'], 2) ?></div>
                    <div class="small text-muted">
                        <i class="bi bi-cart-check me-1"></i> <?= (int)$resumen['total_compras'] ?> compras registradas
                        <br><i class="bi bi-box2 me-1"></i> <?= (int)$resumen['unidades_compradas'] ?> artículos ingresados
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card stat-card shadow-sm h-100 border-start border-<?= $resumen['utilidad_bruta'] >= 0 ? 'primary' : 'warning' ?> border-4">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <span class="text-muted fw-semibold">Utilidad Bruta (Balance)</span>
                        <div class="stat-icon bg-primary-subtle text-primary"><i class="bi bi-wallet2"></i></div>
                    </div>
                    <div class="fs-3 fw-bold text-<?= $resumen['utilidad_bruta'] >= 0 ? 'primary' : 'danger' ?> mb-2">
                        Bs. <?= number_format((float)$resumen['utilidad_bruta'], 2) ?>
                    </div>
                    <div class="small text-muted">
                        <i class="bi bi-pie-chart me-1"></i> Ingresos vs Egresos del periodo
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>
