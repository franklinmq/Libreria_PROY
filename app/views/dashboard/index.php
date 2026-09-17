<style>
/* New Dashboard CSS */
.dash-section-title {
    font-size: 13px;
    font-weight: 700;
    color: #94a3b8;
    text-transform: uppercase;
    letter-spacing: 1px;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 8px;
}
.dash-section-wrapper {
    background: #ffffff;
    border-radius: 16px;
    padding: 24px;
    margin-bottom: 24px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.03);
}

/* Row 1: Colored cards */
.kpi-color-card {
    border-radius: 16px;
    padding: 20px;
    color: #fff !important;
    position: relative;
    overflow: hidden;
    height: 100%;
    display: flex;
    flex-direction: column;
    text-decoration: none;
    box-shadow: 0 8px 20px rgba(0,0,0,0.08);
    transition: transform 0.2s;
}
.kpi-color-card:hover {
    transform: translateY(-5px);
    color: #fff;
}
.kpi-color-card::before {
    content: '';
    position: absolute;
    top: -30px;
    right: -30px;
    width: 140px;
    height: 140px;
    background: rgba(255,255,255,0.15);
    border-radius: 50%;
}
.kpi-color-card .kpi-icon {
    width: 38px;
    height: 38px;
    background: rgba(255,255,255,0.25);
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    margin-bottom: 20px;
}
.kpi-color-card .kpi-title {
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    opacity: 0.9;
    margin-bottom: 5px;
}
.kpi-color-card .kpi-value {
    font-size: 34px;
    font-weight: 800;
    margin-bottom: 16px;
    line-height: 1.1;
}
.kpi-color-card .kpi-badge {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    background: rgba(255,255,255,0.25);
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 600;
    margin-top: auto;
    align-self: flex-start;
}
/* Gradients mapped to system and semantic functionality */
.bg-blue { background: linear-gradient(135deg, #1164CF, #0F5CC7); } /* Ventas / Ingresos (Brand Primary) */
.bg-green { background: linear-gradient(135deg, #1C4689, #123B78); } /* Compras (Brand Dark) */
.bg-orange { background: linear-gradient(135deg, #0891b2, #0e7490); } /* Catálogo (Teal) */
.bg-purple { background: linear-gradient(135deg, #64748b, #475569); } /* Categorías (Gray-Blue) */
.bg-red { background: linear-gradient(135deg, #ef4444, #dc2626); } /* Stock Crítico (Red Alert) */

/* Row 2/3: White cards with blobs */
.kpi-white-card {
    background: #fff;
    border: 1px solid #f1f5f9;
    border-radius: 16px;
    padding: 24px;
    height: 100%;
    position: relative;
    overflow: hidden;
    box-shadow: 0 4px 15px rgba(0,0,0,0.02);
}
.kpi-white-card .blob-tr {
    position: absolute;
    top: -30px;
    right: -30px;
    width: 100px;
    height: 100px;
    border-radius: 50%;
    opacity: 0.5;
}
.blob-green { background: #dcfce7; } /* Ingresos (Success) */
.blob-amber { background: #fef3c7; } /* Gastos (Warning/Amber) */
.blob-blue { background: #e0f2fe; } /* Balance (Brand) */

.kpi-white-card .kpi-icon {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    margin-bottom: 20px;
}
.icon-green { background: #dcfce7; color: #16a34a; }
.icon-amber { background: #fef3c7; color: #d97706; }
.icon-blue { background: #e0f2fe; color: #0284c7; }

.kpi-white-card .kpi-title {
    font-size: 11px;
    font-weight: 700;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 8px;
}
.kpi-white-card .kpi-value {
    font-size: 32px;
    font-weight: 800;
    color: #0f172a;
    margin-bottom: 16px;
    line-height: 1.1;
}
.kpi-white-card .kpi-badge {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 700;
}
.badge-green { background: #dcfce7; color: #16a34a; }
.badge-amber { background: #fef3c7; color: #d97706; }
.badge-blue { background: #e0f2fe; color: #0284c7; }

/* Donut chart cards */
.donut-card {
    background: #fff;
    border: 1px solid #f1f5f9;
    border-radius: 16px;
    padding: 24px;
    height: 100%;
    box-shadow: 0 4px 15px rgba(0,0,0,0.02);
}
.donut-title {
    font-size: 14px;
    font-weight: 700;
    color: #1e293b;
    text-align: center;
    margin-bottom: 20px;
}
.chart-container {
    position: relative;
    height: 250px;
    width: 100%;
}
</style>

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
        <a href="index.php?action=reportes" class="btn btn-outline-secondary quick-action-btn bg-white">
            <i class="bi bi-bar-chart"></i> Reportes
        </a>
    </div>
</div>

<!-- ============================================== -->
<!-- 1. MÉTRICAS PRINCIPALES (TARJETAS DE COLORES)  -->
<!-- ============================================== -->
<div class="dash-section-wrapper">
    <div class="dash-section-title"><i class="bi bi-box-fill"></i> MÉTRICAS PRINCIPALES</div>
    <div class="row g-3 row-cols-1 row-cols-md-2 row-cols-xl-4">
        <!-- BLUE -->
        <div class="col">
            <a href="index.php?action=ventas" class="kpi-color-card bg-blue">
                <div class="kpi-icon"><i class="bi bi-cart-check"></i></div>
                <div class="kpi-title">TOTAL VENTAS</div>
                <div class="kpi-value"><?= (int)$resVentas['total_ventas'] ?></div>
                <div class="kpi-badge"><i class="bi bi-cash"></i> Bs. <?= number_format((float)$resVentas['total_ingresos'], 0) ?></div>
            </a>
        </div>
        <!-- GREEN -->
        <div class="col">
            <a href="index.php?action=compras" class="kpi-color-card bg-green">
                <div class="kpi-icon"><i class="bi bi-bag-check"></i></div>
                <div class="kpi-title">COMPRAS REALIZADAS</div>
                <div class="kpi-value"><?= (int)$resCompras['total_compras'] ?></div>
                <div class="kpi-badge"><i class="bi bi-truck"></i> Bs. <?= number_format((float)$resCompras['total_egresos'], 0) ?></div>
            </a>
        </div>
        <!-- ORANGE -->
        <div class="col">
            <a href="index.php?action=articulos" class="kpi-color-card bg-orange">
                <div class="kpi-icon"><i class="bi bi-box-seam"></i></div>
                <div class="kpi-title">CATÁLOGO ARTÍCULOS</div>
                <div class="kpi-value"><?= (int)$totalArticulos ?></div>
                <div class="kpi-badge"><i class="bi bi-boxes"></i> <?= (int)$inv['total_stock_unidades'] ?> en stock</div>
            </a>
        </div>
        <!-- RED -->
        <div class="col">
            <a href="index.php?action=articulos" class="kpi-color-card bg-red">
                <div class="kpi-icon"><i class="bi bi-exclamation-triangle"></i></div>
                <div class="kpi-title">STOCK CRÍTICO</div>
                <div class="kpi-value"><?= (int)$totalStockBajo ?></div>
                <div class="kpi-badge"><i class="bi bi-bell"></i> Requieren reposición</div>
            </a>
        </div>
    </div>
</div>

<!-- ============================================== -->
<!-- 2. RESUMEN FINANCIERO Y VALORACIÓN             -->
<!-- ============================================== -->
<div class="dash-section-wrapper">
    <div class="dash-section-title"><i class="bi bi-wallet2"></i> RESUMEN FINANCIERO Y VALORACIÓN</div>
    <div class="row g-4">
        <!-- INGRESOS TOTALES -->
        <div class="col-md-4">
            <div class="kpi-white-card">
                <div class="blob-tr blob-green"></div>
                <div class="kpi-icon icon-green"><i class="bi bi-cash-stack"></i></div>
                <div class="kpi-title">INGRESOS TOTALES</div>
                <div class="kpi-value">Bs <?= number_format((float)$resVentas['total_ingresos'], 2) ?></div>
                <div class="kpi-badge badge-green"><i class="bi bi-graph-up-arrow"></i> Gestión actual</div>
            </div>
        </div>
        <!-- GASTOS EN COMPRAS -->
        <div class="col-md-4">
            <div class="kpi-white-card">
                <div class="blob-tr blob-amber"></div>
                <div class="kpi-icon icon-amber"><i class="bi bi-receipt"></i></div>
                <div class="kpi-title">GASTOS EN COMPRAS</div>
                <div class="kpi-value">Bs <?= number_format((float)$resCompras['total_egresos'], 2) ?></div>
                <div class="kpi-badge badge-amber"><i class="bi bi-arrow-down-right"></i> Inversión</div>
            </div>
        </div>
        <!-- BALANCE (UTILIDAD) -->
        <div class="col-md-4">
            <div class="kpi-white-card">
                <div class="blob-tr blob-blue"></div>
                <div class="kpi-icon icon-blue"><i class="bi bi-scales"></i></div>
                <div class="kpi-title">BALANCE (UTILIDAD)</div>
                <div class="kpi-value">Bs <?= number_format((float)$utilidadBruta, 2) ?></div>
                <div class="kpi-badge badge-blue"><i class="bi bi-hand-thumbs-up"></i> <?= $utilidadBruta >= 0 ? 'Superávit' : 'Déficit' ?></div>
            </div>
        </div>
    </div>
</div>

<!-- ============================================== -->
<!-- 3. ANALÍTICA (GRÁFICOS DONUT)                  -->
<!-- ============================================== -->
<div class="dash-section-wrapper">
    <div class="dash-section-title"><i class="bi bi-pie-chart-fill"></i> ANALÍTICA</div>
    <div class="row g-4">
        <!-- Categorias -->
        <div class="col-md-4">
            <div class="donut-card">
                <div class="donut-title">Distribución por Categorías</div>
                <div class="chart-container">
                    <?php if (!empty($categoriasDistribucion)): ?>
                        <canvas id="chartCategorias"></canvas>
                    <?php else: ?>
                        <div class="d-flex align-items-center justify-content-center h-100 text-muted small">Sin datos</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <!-- Gastos vs Ingresos -->
        <div class="col-md-4">
            <div class="donut-card">
                <div class="donut-title">Ingresos vs Egresos</div>
                <div class="chart-container">
                    <canvas id="chartBalance"></canvas>
                </div>
            </div>
        </div>
        <!-- Top Vendidos -->
        <div class="col-md-4">
            <div class="donut-card">
                <div class="donut-title">Top 5 Productos Más Vendidos</div>
                <div class="chart-container">
                    <?php if (!empty($topVendidos)): ?>
                        <canvas id="chartTop"></canvas>
                    <?php else: ?>
                        <div class="d-flex align-items-center justify-content-center h-100 text-muted small">Sin datos</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ============================================== -->
<!-- 4. MONITOREO EN VIVO: ÚLTIMAS VENTAS Y STOCK BAJO -->
<!-- ============================================== -->
<div class="dash-section-wrapper">
    <div class="dash-section-title"><i class="bi bi-activity"></i> MONITOREO EN VIVO</div>
    <div class="row g-4">
        <!-- Últimas Ventas Realizadas -->
        <div class="col-lg-6">
            <div class="card shadow-none border h-100" style="border-radius: 12px;">
                <div class="card-header bg-white d-flex justify-content-between align-items-center py-3 border-bottom-0">
                    <h6 class="mb-0 fw-bold text-dark">Últimas Ventas Realizadas</h6>
                    <a href="index.php?action=ventas" class="btn btn-sm btn-outline-primary py-1 px-2" style="font-size: 0.78rem;">Ver Todas</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0" style="font-size: 0.88rem;">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-3" style="width: 70px;">Folio</th>
                                    <th>Cliente</th>
                                    <th>Fecha</th>
                                    <th class="text-end pe-3">Total</th>
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
                                            <td class="text-end fw-bold text-success pe-3">
                                                Bs. <?= number_format((float)$v['total'], 2) ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="4" class="text-center py-4 text-muted">Aún no hay ventas registradas.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Artículos con Stock Bajo o Crítico -->
        <div class="col-lg-6">
            <div class="card shadow-none border h-100" style="border-radius: 12px;">
                <div class="card-header bg-white d-flex justify-content-between align-items-center py-3 border-bottom-0">
                    <h6 class="mb-0 fw-bold text-dark text-danger">Artículos con Stock Crítico (&le; 10)</h6>
                    <a href="index.php?action=articulos" class="btn btn-sm btn-outline-warning py-1 px-2" style="font-size: 0.78rem;">Inventario</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0" style="font-size: 0.88rem;">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-3">Artículo</th>
                                    <th>Categoría</th>
                                    <th class="text-center pe-3">Stock</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($articulosStockBajo)): ?>
                                    <?php foreach ($articulosStockBajo as $art): ?>
                                        <tr>
                                            <td class="ps-3">
                                                <div class="fw-semibold text-dark text-truncate" style="max-width: 170px;">
                                                    <?= htmlspecialchars($art['nombre']) ?>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-light text-dark border" style="font-size: 0.72rem;">
                                                    <?= htmlspecialchars($art['categoria_nombre'] ?? 'Sin categoría') ?>
                                                </span>
                                            </td>
                                            <td class="text-center pe-3">
                                                <span class="badge <?= (int)$art['stock'] <= 2 ? 'bg-danger text-white' : 'bg-warning text-dark' ?> fw-bold">
                                                    <?= (int)$art['stock'] ?> unids.
                                                </span>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="3" class="text-center py-4 text-muted">¡Excelente! Todos los artículos cuentan con stock óptimo.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Scripts de Gráficos -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    const commonOptions = {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom',
                labels: { boxWidth: 10, font: { size: 11 }, padding: 20 }
            }
        },
        cutout: '70%',
        borderWidth: 0
    };

    // 1. Gráfico Categorías (Doughnut)
    <?php if (!empty($categoriasDistribucion)): ?>
    new Chart(document.getElementById('chartCategorias').getContext('2d'), {
        type: 'doughnut',
        data: {
            labels: <?= json_encode(array_column($categoriasDistribucion, 'nombre')) ?>,
            datasets: [{
                data: <?= json_encode(array_column($categoriasDistribucion, 'total_articulos')) ?>,
                backgroundColor: ['#1164CF', '#1C4689', '#0891b2', '#0F5CC7', '#64748b', '#0e7490'],
            }]
        },
        options: commonOptions
    });
    <?php endif; ?>

    // 2. Gráfico Balance (Ingresos vs Egresos)
    new Chart(document.getElementById('chartBalance').getContext('2d'), {
        type: 'doughnut',
        data: {
            labels: ['Ingresos', 'Egresos'],
            datasets: [{
                data: [<?= (float)$resVentas['total_ingresos'] ?>, <?= (float)$resCompras['total_egresos'] ?>],
                backgroundColor: ['#10b981', '#f59e0b'],
            }]
        },
        options: commonOptions
    });

    // 3. Gráfico Top Productos
    <?php if (!empty($topVendidos)): ?>
    new Chart(document.getElementById('chartTop').getContext('2d'), {
        type: 'doughnut',
        data: {
            labels: <?= json_encode(array_column($topVendidos, 'nombre')) ?>,
            datasets: [{
                data: <?= json_encode(array_column($topVendidos, 'total_unidades_vendidas')) ?>,
                backgroundColor: ['#1164CF', '#1C4689', '#0891b2', '#64748b', '#94a3b8'],
            }]
        },
        options: {
            ...commonOptions,
            plugins: {
                legend: { display: false } // Hide legend for products as names can be long
            }
        }
    });
    <?php endif; ?>
});
</script>