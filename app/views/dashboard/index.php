<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold" style="color: var(--brand-primary);">Dashboard</h3>
    <p class="text-muted mb-0">Resumen general del sistema</p>
</div>

<!-- Tarjetas (Targets / Accesos directos) -->
<div class="row g-4 mb-4">
    <div class="col-md-3">
        <a href="index.php?action=productos" class="text-decoration-none">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 12px; transition: transform 0.2s, box-shadow 0.2s;" onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 10px 20px rgba(0,0,0,0.1)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='';">
                <div class="card-body d-flex align-items-center">
                    <div class="rounded-circle d-flex align-items-center justify-content-center text-white me-3" style="width: 50px; height: 50px; background-color: #3b82f6;">
                        <i class="bi bi-box-seam fs-4"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-1">Productos</h6>
                        <h4 class="fw-bold mb-0 text-dark"><?= htmlspecialchars($totalProductos) ?></h4>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <div class="col-md-3">
        <a href="index.php?action=categorias" class="text-decoration-none">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 12px; transition: transform 0.2s, box-shadow 0.2s;" onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 10px 20px rgba(0,0,0,0.1)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='';">
                <div class="card-body d-flex align-items-center">
                    <div class="rounded-circle d-flex align-items-center justify-content-center text-white me-3" style="width: 50px; height: 50px; background-color: #10b981;">
                        <i class="bi bi-tags fs-4"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-1">Categorías</h6>
                        <h4 class="fw-bold mb-0 text-dark"><?= htmlspecialchars($totalCategorias) ?></h4>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <div class="col-md-3">
        <a href="#" class="text-decoration-none">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 12px; transition: transform 0.2s, box-shadow 0.2s;" onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 10px 20px rgba(0,0,0,0.1)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='';">
                <div class="card-body d-flex align-items-center">
                    <div class="rounded-circle d-flex align-items-center justify-content-center text-white me-3" style="width: 50px; height: 50px; background-color: #f59e0b;">
                        <i class="bi bi-cart-check fs-4"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-1">Ventas Hoy</h6>
                        <h4 class="fw-bold mb-0 text-dark">Bs. 3,000</h4>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <div class="col-md-3">
        <a href="#" class="text-decoration-none">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 12px; transition: transform 0.2s, box-shadow 0.2s;" onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 10px 20px rgba(0,0,0,0.1)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='';">
                <div class="card-body d-flex align-items-center">
                    <div class="rounded-circle d-flex align-items-center justify-content-center text-white me-3" style="width: 50px; height: 50px; background-color: #ef4444;">
                        <i class="bi bi-bar-chart fs-4"></i>
                    </div>
                    <div>
                        <h6 class="text-muted mb-1">Reportes</h6>
                        <h4 class="fw-bold mb-0 text-dark">Ver más</h4>
                    </div>
                </div>
            </div>
        </a>
    </div>
</div>

<!-- Gráficos -->
<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm" style="border-radius: 12px;">
            <div class="card-header bg-white border-bottom py-3" style="border-radius: 12px 12px 0 0;">
                <h5 class="mb-0 fw-bold text-dark"><i class="bi bi-graph-up text-primary me-2"></i>Ventas de los últimos días (Mock)</h5>
            </div>
            <div class="card-body">
                <canvas id="ventasChart" height="100"></canvas>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const ctx = document.getElementById('ventasChart').getContext('2d');
        const labels = <?= json_encode(array_keys($ventasDias)) ?>;
        const data = <?= json_encode(array_values($ventasDias)) ?>;

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Ventas (Bs.)',
                    data: data,
                    backgroundColor: 'rgba(59, 130, 246, 0.2)',
                    borderColor: 'rgba(59, 130, 246, 1)',
                    borderWidth: 2,
                    borderRadius: 4,
                    hoverBackgroundColor: 'rgba(59, 130, 246, 0.4)'
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    });
</script>
