<?php $msg = $_GET['msg'] ?? ''; ?>

<?php if ($msg): ?>
    <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
        <i class="bi bi-check-circle me-1"></i>
        <?php
            $mensajes = [
                'creado'      => 'Articulo agregado correctamente.',
                'actualizado' => 'Articulo actualizado correctamente.',
                'eliminado'   => 'Articulo eliminado del inventario.',
            ];
            echo $mensajes[$msg] ?? 'Operación realizada.';
        ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<!-- Tarjetas resumen -->
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-lg-3">
        <div class="card stat-card shadow-sm h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="stat-icon bg-primary-subtle text-primary"><i class="bi bi-box-seam"></i></div>
                <div>
                    <div class="text-muted small">Articulos registrados</div>
                    <div class="fs-4 fw-bold"><?= (int) $total ?></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card stat-card shadow-sm h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="stat-icon bg-success-subtle text-success"><i class="bi bi-cash-coin"></i></div>
                <div>
                    <div class="text-muted small">Valor del inventario</div>
                    <div class="fs-4 fw-bold">Bs. <?= number_format((float) $valorInv, 2) ?></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card stat-card shadow-sm h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="stat-icon bg-warning-subtle text-warning"><i class="bi bi-exclamation-triangle"></i></div>
                <div>
                    <div class="text-muted small">Stock bajo (&le; 10)</div>
                    <div class="fs-4 fw-bold"><?= count($stockBajo) ?></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card stat-card shadow-sm h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="stat-icon bg-info-subtle text-info"><i class="bi bi-tags"></i></div>
                <div>
                    <div class="text-muted small">Gestión</div>
                    <a href="index.php?action=categorias" class="fw-semibold text-decoration-none">Ver categorías &rarr;</a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-header bg-white d-flex flex-wrap justify-content-between align-items-center gap-2">
        <h5 class="mb-0"><i class="bi bi-list-ul me-2"></i>Inventario de articulos</h5>
        <div class="d-flex gap-2">
            <button type="button" class="btn btn-primary btn-sm text-nowrap" data-bs-toggle="modal" data-bs-target="#modalNuevoArticulo">
                <i class="bi bi-plus-circle me-1"></i> Agregar Articulo
            </button>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0 table-datatable">
            <thead class="table-light">
                <tr>
                    <th>Nombre</th>
                    <th>Marca</th>
                    <th>Categoría</th>
                    <th class="text-end">Precio Venta</th>
                    <th class="text-center">Stock</th>
                    <th class="text-end">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($articulos)): ?>
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">
                            <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                            No se encontraron articulos en el inventario.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($articulos as $articulo): ?>
                        <tr>
                            <td class="fw-semibold"><?= htmlspecialchars($articulo['nombre']) ?></td>
                            <td><?= htmlspecialchars($articulo['marca_nombre'] ?? '') ?></td>
                            <td>
                                <?php if ($articulo['categoria_nombre']): ?>
                                    <span class="badge badge-cat"><?= htmlspecialchars($articulo['categoria_nombre']) ?></span>
                                <?php else: ?>
                                    <span class="text-muted">—</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-end">Bs. <?= number_format((float) $articulo['precio_venta'], 2) ?></td>
                            <td class="text-center">
                                <span class="badge <?= $articulo['stock'] <= 10 ? 'text-bg-warning' : 'text-bg-success' ?>">
                                    <?= (int) $articulo['stock'] ?>
                                </span>
                            </td>
                            <td class="text-end">
                                <a href="index.php?action=articulo-ver&id=<?= $articulo['id'] ?>"
                                   class="btn btn-sm btn-outline-secondary" title="Ver">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="index.php?action=articulo-editar&id=<?= $articulo['id'] ?>"
                                   class="btn btn-sm btn-outline-primary" title="Editar">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="index.php?action=articulo-eliminar&id=<?= $articulo['id'] ?>" method="post"
                                      class="d-inline" onsubmit="return confirm('¿Eliminar este articulo del inventario?');">
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Eliminar">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Nuevo Articulo -->
<div class="modal fade" id="modalNuevoArticulo" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="index.php?action=articulo-guardar" method="post" novalidate>
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-plus-circle me-2"></i>Agregar nuevo articulo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <?php include __DIR__ . '/_form.php'; ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-1"></i> Guardar Articulo
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include __DIR__ . '/_modals_auxiliares.php'; ?>

<?php if (isset($show_modal) && $show_modal): ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var myModal = new bootstrap.Modal(document.getElementById('modalNuevoArticulo'));
        myModal.show();
    });
</script>
<?php endif; ?>
