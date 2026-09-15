<!-- Header del Dashboard -->
<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <div>
        <h3 class="fw-bold mb-1" style="color: var(--brand-primary);">
            <i class="bi bi-speedometer2 me-2"></i>Panel de Control
        </h3>
        <p class="text-muted mb-0 small">Resumen integral y métricas en tiempo real del inventario, ventas y compras.</p>
    </div>
    <div class="d-flex flex-wrap gap-2">
        <a href="index.php?action=venta-nueva" class="btn btn-success quick-action-btn">
            <i class="bi bi-cart-plus"></i> Nueva Venta (POS)
        </a>
        <a href="index.php?action=compra-nueva" class="btn btn-primary quick-action-btn">
            <i class="bi bi-truck"></i> Nueva Compra
        </a>
        <a href="index.php?action=articulo-nuevo" class="btn btn-outline-primary quick-action-btn bg-white">
            <i class="bi bi-plus-circle"></i> Nuevo Artículo
        </a>
        <a href="index.php?action=reportes" class="btn btn-outline-secondary quick-action-btn bg-white">
            <i class="bi bi-bar-chart"></i> Reportes
        </a>
    </div>
</div>

<!-- ============================================== -->
<!-- TARGETS / TARJETAS KPI (ACCESOS DIRECTOS)      -->
<!-- ============================================== -->
<div class="row g-3 mb-4">
    <!-- Target 1: Ventas Realizadas (Ingresos) -->
    <div class="col-sm-6 col-xl-3">
        <a href="index.php?action=ventas" class="target-card">
            <div class="card-body p-3 d-flex align-items-center gap-3">
                <div class="target-icon bg-success-subtle text-success">
                    <i class="bi bi-cash-coin"></i>
                </div>
                <div class="overflow-hidden">
                    <div class="text-muted small text-truncate">Ventas Realizadas</div>
                    <div class="fs-4 fw-bold text-dark">Bs. <?= number_format((float)$resVentas['total_ingresos'], 2) ?></div>
                    <div class="small text-muted text-truncate">
                        <span class="badge bg-success-subtle text-success"><?= (int)$resVentas['total_ventas'] ?> ventas</span>
                        <?php if ((float)$ventasHoy['total_ingresos_hoy'] > 0): ?>
                            <span class="ms-1 text-success fw-semibold">Hoy: Bs. <?= number_format((float)$ventasHoy['total_ingresos_hoy'], 2) ?></span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="target-link-footer">
                <span>Ir al módulo de Ventas</span>
                <i class="bi bi-arrow-right"></i>
            </div>
        </a>
    </div>

    <!-- Target 2: Compras a Proveedores (Egresos) -->
    <div class="col-sm-6 col-xl-3">
        <a href="index.php?action=compras" class="target-card">
            <div class="card-body p-3 d-flex align-items-center gap-3">
                <div class="target-icon bg-primary-subtle text-primary">
                    <i class="bi bi-cart-check"></i>
                </div>
                <div class="overflow-hidden">
                    <div class="text-muted small text-truncate">Compras / Inversión</div>
                    <div class="fs-4 fw-bold text-dark">Bs. <?= number_format((float)$resCompras['total_egresos'], 2) ?></div>
                    <div class="small text-muted text-truncate">
                        <span class="badge bg-primary-subtle text-primary"><?= (int)$resCompras['total_compras'] ?> órdenes</span>
                        <span class="ms-1">Mercadería ingresada</span>
                    </div>
                </div>
            </div>
            <div class="target-link-footer">
                <span>Ir al módulo de Compras</span>
                <i class="bi bi-arrow-right"></i>
            </div>
        </a>
    </div>

    <!-- Target 3: Total Artículos en Catálogo -->
    <div class="col-sm-6 col-xl-3">
        <a href="index.php?action=articulos" class="target-card">
            <div class="card-body p-3 d-flex align-items-center gap-3">
                <div class="target-icon bg-info-subtle text-info">
                    <i class="bi bi-box-seam"></i>
                </div>
                <div class="overflow-hidden">
                    <div class="text-muted small text-truncate">Artículos Registrados</div>
                    <div class="fs-4 fw-bold text-dark"><?= (int)$totalArticulos ?> <small class="fs-6 fw-normal text-muted">items</small></div>
                    <div class="small text-muted text-truncate">
                        <span class="fw-semibold text-dark"><?= (int)$inv['total_stock_unidades'] ?></span> unidades en stock físico
                    </div>
                </div>
            </div>
            <div class="target-link-footer">
                <span>Ver Catálogo de Artículos</span>
                <i class="bi bi-arrow-right"></i>
            </div>
        </a>
    </div>

    <!-- Target 4: Alerta de Stock Bajo / Crítico -->
    <div class="col-sm-6 col-xl-3">
        <a href="index.php?action=articulos" class="target-card <?= $totalStockBajo > 0 ? 'border-warning' : '' ?>">
            <div class="card-body p-3 d-flex align-items-center gap-3">
                <div class="target-icon <?= $totalStockBajo > 0 ? 'bg-warning-subtle text-warning' : 'bg-light text-secondary' ?>">
                    <i class="bi bi-exclamation-triangle"></i>
                </div>
                <div class="overflow-hidden">
                    <div class="text-muted small text-truncate">Alerta de Stock Crítico</div>
                    <div class="fs-4 fw-bold <?= $totalStockBajo > 0 ? 'text-warning-emphasis' : 'text-dark' ?>">
                        <?= (int)$totalStockBajo ?> <small class="fs-6 fw-normal text-muted">artículos</small>
                    </div>
                    <div class="small text-truncate">
                        <?php if ($totalStockBajo > 0): ?>
                            <span class="badge bg-warning-subtle text-warning-emphasis fw-semibold">Requieren reposición (&le; 10)</span>
                        <?php else: ?>
                            <span class="text-success small"><i class="bi bi-check-circle me-1"></i>Stock en nivel seguro</span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="target-link-footer">
                <span>Revisar Artículos con Bajo Stock</span>
                <i class="bi bi-arrow-right"></i>
            </div>
        </a>
    </div>

    <!-- Target 5: Valor del Inventario (Capital) -->
    <div class="col-sm-6 col-xl-3">
        <a href="index.php?action=reportes" class="target-card">
            <div class="card-body p-3 d-flex align-items-center gap-3">
                <div class="target-icon" style="background-color: #F3E8FF; color: #7E22CE;">
                    <i class="bi bi-archive"></i>
                </div>
                <div class="overflow-hidden">
                    <div class="text-muted small text-truncate">Valor del Inventario (Costo)</div>
                    <div class="fs-4 fw-bold text-dark">Bs. <?= number_format((float)$inv['valor_costo'], 2) ?></div>
                    <div class="small text-muted text-truncate">
                        Venta est.: <span class="fw-semibold text-primary">Bs. <?= number_format((float)$inv['valor_venta'], 2) ?></span>
                    </div>
                </div>
            </div>
            <div class="target-link-footer">
                <span>Ver Reporte de Inventario</span>
                <i class="bi bi-arrow-right"></i>
            </div>
        </a>
    </div>

    <!-- Target 6: Balance / Utilidad Bruta -->
    <div class="col-sm-6 col-xl-3">
        <a href="index.php?action=reportes" class="target-card">
            <div class="card-body p-3 d-flex align-items-center gap-3">
                <div class="target-icon" style="background-color: #E0F2FE; color: #0284C7;">
                    <i class="bi bi-graph-up-arrow"></i>
                </div>
                <div class="overflow-hidden">
                    <div class="text-muted small text-truncate">Utilidad / Balance Bruto</div>
                    <div class="fs-4 fw-bold <?= $utilidadBruta >= 0 ? 'text-success' : 'text-danger' ?>">
                        Bs. <?= number_format((float)$utilidadBruta, 2) ?>
                    </div>
                    <div class="small text-muted text-truncate">
                        Margen (Ingresos - Egresos)
                    </div>
                </div>
            </div>
            <div class="target-link-footer">
                <span>Ver Balance Financiero</span>
                <i class="bi bi-arrow-right"></i>
            </div>
        </a>
    </div>

    <!-- Target 7: Categorías y Marcas -->
    <div class="col-sm-6 col-xl-3">
        <a href="index.php?action=categorias" class="target-card">
            <div class="card-body p-3 d-flex align-items-center gap-3">
                <div class="target-icon" style="background-color: #CCFBF1; color: #0F766E;">
                    <i class="bi bi-tags"></i>
                </div>
                <div class="overflow-hidden">
                    <div class="text-muted small text-truncate">Categorías y Marcas</div>
                    <div class="fs-4 fw-bold text-dark"><?= (int)$totalCategorias ?> <small class="fs-6 fw-normal text-muted">categorías</small></div>
                    <div class="small text-muted text-truncate">
                        <span class="badge bg-secondary-subtle text-secondary"><?= (int)$totalMarcas ?> marcas activas</span>
                    </div>
                </div>
            </div>
            <div class="target-link-footer">
                <span>Administrar Categorías</span>
                <i class="bi bi-arrow-right"></i>
            </div>
        </a>
    </div>

    <!-- Target 8: Proveedores Activos -->
    <div class="col-sm-6 col-xl-3">
        <a href="index.php?action=compras" class="target-card">
            <div class="card-body p-3 d-flex align-items-center gap-3">
                <div class="target-icon" style="background-color: #F1F5F9; color: #475569;">
                    <i class="bi bi-building"></i>
                </div>
                <div class="overflow-hidden">
                    <div class="text-muted small text-truncate">Proveedores Registrados</div>
                    <div class="fs-4 fw-bold text-dark"><?= (int)$totalProveedores ?> <small class="fs-6 fw-normal text-muted">proveedores</small></div>
                    <div class="small text-muted text-truncate">
                        Red activa de abastecimiento
                    </div>
                </div>
            </div>
            <div class="target-link-footer">
                <span>Gestionar Compras / Proveedores</span>
                <i class="bi bi-arrow-right"></i>
            </div>
        </a>
    </div>
</div>

<!-- ============================================== -->
<!-- SECCIÓN DE GRÁFICOS DINÁMICOS CON DATOS REALES -->
<!-- ============================================== -->
<div class="row g-4 mb-4">
    <!-- Gráfico Comparativo: Ventas vs Compras -->
    <div class="col-lg-8">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                <h6 class="mb-0 fw-bold text-dark">
                    <i class="bi bi-bar-chart-line text-primary me-2"></i>Movimiento Financiero (Últimos 7 Días)
                </h6>
                <div class="d-flex gap-2">
                    <span class="badge bg-success-subtle text-success px-2 py-1"><i class="bi bi-circle-fill me-1" style="font-size: 0.5rem;"></i>Ventas</span>
                    <span class="badge bg-primary-subtle text-primary px-2 py-1"><i class="bi bi-circle-fill me-1" style="font-size: 0.5rem;"></i>Compras</span>
                </div>
            </div>
            <div class="card-body">
                <canvas id="ventasComprasChart" height="110"></canvas>
            </div>
        </div>
    </div>

    <!-- Gráfico de Categorías -->
    <div class="col-lg-4">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                <h6 class="mb-0 fw-bold text-dark">
                    <i class="bi bi-pie-chart text-info me-2"></i>Distribución de Catálogo
                </h6>
                <a href="index.php?action=categorias" class="small text-decoration-none">Ver todas &rarr;</a>
            </div>
            <div class="card-body d-flex flex-column justify-content-center align-items-center">
                <?php if (!empty($categoriasDistribucion)): ?>
                    <div style="width: 100%; max-height: 220px; position: relative;">
                        <canvas id="categoriasChart"></canvas>
                    </div>
                <?php else: ?>
                    <div class="text-center py-4 text-muted">
                        <i class="bi bi-tags fs-1 text-secondary opacity-50 d-block mb-2"></i>
                        <p class="small mb-0">Sin artículos asignados a categorías aún.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- ============================================== -->
<!-- MONITOREO EN VIVO: ÚLTIMAS VENTAS Y STOCK BAJO -->
<!-- ============================================== -->
<div class="row g-4 mb-4">
    <!-- Últimas Ventas Realizadas -->
    <div class="col-lg-6">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                <h6 class="mb-0 fw-bold text-dark">
                    <i class="bi bi-receipt text-success me-2"></i>Últimas Ventas Realizadas
                </h6>
                <a href="index.php?action=ventas" class="btn btn-sm btn-outline-primary py-1 px-2" style="font-size: 0.78rem;">
                    Ver Todas
                </a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" style="font-size: 0.88rem;">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-3" style="width: 70px;">Folio</th>
                                <th>Cliente</th>
                                <th>Fecha</th>
                                <th class="text-end">Total</th>
                                <th class="text-center pe-3" style="width: 70px;">Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($ultimasVentas)): ?>
                                <?php foreach ($ultimasVentas as $v): ?>
                                    <tr>
                                        <td class="ps-3 fw-bold text-secondary">#<?= str_pad($v['id'], 5, '0', STR_PAD_LEFT) ?></td>
                                        <td>
                                            <span class="fw-semibold"><?= htmlspecialchars($v['cliente_nombre'] ?? 'Consumidor Final') ?></span>
                                            <div class="text-muted" style="font-size: 0.75rem;"><?= (int)$v['total_unidades'] ?> productos</div>
                                        </td>
                                        <td class="text-muted" style="font-size: 0.8rem;">
                                            <?= date('d/m/Y H:i', strtotime($v['fecha_venta'])) ?>
                                        </td>
                                        <td class="text-end fw-bold text-success">
                                            Bs. <?= number_format((float)$v['total'], 2) ?>
                                        </td>
                                        <td class="text-center pe-3">
                                            <a href="index.php?action=venta-ver&id=<?= $v['id'] ?>" class="btn btn-sm btn-outline-secondary py-0 px-2" title="Ver detalle del ticket">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">
                                        <i class="bi bi-inbox fs-2 opacity-50 d-block mb-1"></i>
                                        Aún no hay ventas registradas en el sistema.
                                        <div class="mt-2">
                                            <a href="index.php?action=venta-nueva" class="btn btn-sm btn-success">
                                                <i class="bi bi-cart-plus me-1"></i> Registrar Primera Venta
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer bg-white border-top text-center py-2">
                <a href="index.php?action=ventas" class="text-decoration-none small fw-semibold">
                    Ir al listado completo de ventas &rarr;
                </a>
            </div>
        </div>
    </div>

    <!-- Artículos con Stock Bajo o Crítico -->
    <div class="col-lg-6">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                <h6 class="mb-0 fw-bold text-dark">
                    <i class="bi bi-exclamation-octagon text-danger me-2"></i>Artículos con Stock Crítico (&le; 10)
                </h6>
                <a href="index.php?action=articulos" class="btn btn-sm btn-outline-warning py-1 px-2" style="font-size: 0.78rem;">
                    Ver Inventario
                </a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" style="font-size: 0.88rem;">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-3" style="width: 50px;">Foto</th>
                                <th>Artículo</th>
                                <th>Categoría</th>
                                <th class="text-center">Stock</th>
                                <th class="text-center pe-3" style="width: 120px;">Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($articulosStockBajo)): ?>
                                <?php foreach ($articulosStockBajo as $art): ?>
                                    <tr>
                                        <td class="ps-3">
                                            <?php if (!empty($art['imagen'])): ?>
                                                <img src="uploads/<?= htmlspecialchars($art['imagen']) ?>" alt="Foto" style="width: 34px; height: 34px; object-fit: contain;" class="rounded border p-1 bg-white">
                                            <?php else: ?>
                                                <div class="bg-light border rounded d-flex align-items-center justify-content-center text-secondary" style="width: 34px; height: 34px;">
                                                    <i class="bi bi-image" style="font-size: 0.85rem;"></i>
                                                </div>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div class="fw-semibold text-dark text-truncate" style="max-width: 170px;">
                                                <?= htmlspecialchars($art['nombre']) ?>
                                            </div>
                                            <div class="text-muted" style="font-size: 0.75rem;">
                                                <?= htmlspecialchars($art['marca_nombre'] ?? 'Sin marca') ?>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark border" style="font-size: 0.72rem;">
                                                <?= htmlspecialchars($art['categoria_nombre'] ?? 'Sin categoría') ?>
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge <?= (int)$art['stock'] <= 2 ? 'bg-danger text-white' : 'bg-warning text-dark' ?> fw-bold">
                                                <?= (int)$art['stock'] ?> unids.
                                            </span>
                                        </td>
                                        <td class="text-center pe-3">
                                            <div class="btn-group btn-group-sm">
                                                <a href="index.php?action=compra-nueva" class="btn btn-outline-primary py-0 px-2" title="Comprar stock">
                                                    <i class="bi bi-cart-plus"></i> Reabastecer
                                                </a>
                                                <a href="index.php?action=articulo-editar&id=<?= $art['id'] ?>" class="btn btn-outline-secondary py-0 px-2" title="Editar producto">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">
                                        <i class="bi bi-check-circle-fill fs-2 text-success opacity-75 d-block mb-1"></i>
                                        ¡Excelente! Todos los artículos cuentan con stock óptimo.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer bg-white border-top text-center py-2">
                <a href="index.php?action=articulos" class="text-decoration-none small fw-semibold">
                    Ir al inventario general de artículos &rarr;
                </a>
            </div>
        </div>
    </div>
</div>

<!-- ============================================== -->
<!-- PRODUCTOS MÁS VENDIDOS (SI EXISTEN VENTAS)     -->
<!-- ============================================== -->
<?php if (!empty($topVendidos)): ?>
<div class="card shadow-sm mb-4">
    <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
        <h6 class="mb-0 fw-bold text-dark">
            <i class="bi bi-trophy text-warning me-2"></i>Productos Estrella Más Vendidos
        </h6>
        <a href="index.php?action=reportes" class="btn btn-sm btn-outline-secondary py-1 px-2" style="font-size: 0.78rem;">
            Ver Reporte Completo
        </a>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0" style="font-size: 0.88rem;">
            <thead class="table-light">
                <tr>
                    <th class="text-center ps-3" style="width: 50px;">#</th>
                    <th>Artículo</th>
                    <th>Categoría</th>
                    <th>Marca</th>
                    <th class="text-center">Unidades Vendidas</th>
                    <th class="text-end pe-3">Total Recaudado</th>
                </tr>
            </thead>
            <tbody>
                <?php $pos = 1; foreach ($topVendidos as $top): ?>
                    <tr>
                        <td class="text-center ps-3">
                            <span class="badge rounded-pill <?= $pos === 1 ? 'bg-warning text-dark' : ($pos === 2 ? 'bg-secondary text-white' : 'bg-light text-dark border') ?>">
                                <?= $pos++ ?>
                            </span>
                        </td>
                        <td class="fw-bold text-dark"><?= htmlspecialchars($top['nombre']) ?></td>
                        <td><span class="badge badge-cat"><?= htmlspecialchars($top['categoria_nombre'] ?? '—') ?></span></td>
                        <td class="text-muted"><?= htmlspecialchars($top['marca_nombre'] ?? '—') ?></td>
                        <td class="text-center fw-bold text-primary"><?= (int)$top['total_unidades_vendidas'] ?></td>
                        <td class="text-end pe-3 fw-bold text-success">Bs. <?= number_format((float)$top['total_recaudado'], 2) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php endif; ?>

<!-- Scripts de Gráficos -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    // 1. Gráfico Ventas vs Compras
    const ctxVentas = document.getElementById('ventasComprasChart').getContext('2d');
    const labelsDias = <?= json_encode($labelsDias) ?>;
    const datosVentas = <?= json_encode($datosVentasDias) ?>;
    const datosCompras = <?= json_encode($datosComprasDias) ?>;

    new Chart(ctxVentas, {
        type: 'bar',
        data: {
            labels: labelsDias,
            datasets: [
                {
                    label: 'Ventas (Bs.)',
                    data: datosVentas,
                    backgroundColor: 'rgba(16, 185, 129, 0.75)',
                    borderColor: '#10b981',
                    borderWidth: 1.5,
                    borderRadius: 5
                },
                {
                    label: 'Compras (Bs.)',
                    data: datosCompras,
                    backgroundColor: 'rgba(17, 100, 207, 0.75)',
                    borderColor: '#1164CF',
                    borderWidth: 1.5,
                    borderRadius: 5
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                mode: 'index',
                intersect: false
            },
            plugins: {
                legend: {
                    position: 'top',
                    labels: {
                        boxWidth: 12,
                        font: { size: 11 }
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.dataset.label + ': Bs. ' + Number(context.raw).toFixed(2);
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'Bs. ' + value;
                        }
                    },
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)'
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });

    // 2. Gráfico Categorías (Doughnut)
    <?php if (!empty($categoriasDistribucion)): ?>
    const ctxCat = document.getElementById('categoriasChart').getContext('2d');
    const catLabels = <?= json_encode(array_column($categoriasDistribucion, 'nombre')) ?>;
    const catData = <?= json_encode(array_column($categoriasDistribucion, 'total_articulos')) ?>;

    new Chart(ctxCat, {
        type: 'doughnut',
        data: {
            labels: catLabels,
            datasets: [{
                data: catData,
                backgroundColor: [
                    '#1164CF',
                    '#10B981',
                    '#F59E0B',
                    '#8B5CF6',
                    '#EC4899',
                    '#06B6D4'
                ],
                borderWidth: 2,
                borderColor: '#ffffff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        boxWidth: 10,
                        font: { size: 10 }
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return ' ' + context.label + ': ' + context.raw + ' artículos';
                        }
                    }
                }
            },
            cutout: '65%'
        }
    });
    <?php endif; ?>
});
</script>
