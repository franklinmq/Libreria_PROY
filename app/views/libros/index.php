<?php $msg = $_GET['msg'] ?? ''; ?>

<?php if ($msg): ?>
    <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
        <i class="bi bi-check-circle me-1"></i>
        <?php
            $mensajes = [
                'creado'      => 'Libro agregado correctamente.',
                'actualizado' => 'Libro actualizado correctamente.',
                'eliminado'   => 'Libro eliminado del inventario.',
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
                <div class="stat-icon bg-primary-subtle text-primary"><i class="bi bi-book"></i></div>
                <div>
                    <div class="text-muted small">Títulos registrados</div>
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
                    <div class="fs-4 fw-bold">$<?= number_format((float) $valorInv, 2) ?></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card stat-card shadow-sm h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="stat-icon bg-warning-subtle text-warning"><i class="bi bi-exclamation-triangle"></i></div>
                <div>
                    <div class="text-muted small">Stock bajo (&le; 5)</div>
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
        <h5 class="mb-0"><i class="bi bi-list-ul me-2"></i>Inventario de libros</h5>

        <form class="d-flex" role="search" method="get" action="index.php">
            <input type="hidden" name="action" value="libros">
            <input type="search" name="q" value="<?= htmlspecialchars($busqueda) ?>"
                   class="form-control form-control-sm me-2" placeholder="Buscar por título, autor o ISBN...">
            <button class="btn btn-sm btn-outline-primary" type="submit">
                <i class="bi bi-search"></i>
            </button>
        </form>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Título</th>
                    <th>Autor</th>
                    <th>ISBN</th>
                    <th>Categoría</th>
                    <th class="text-end">Precio</th>
                    <th class="text-center">Stock</th>
                    <th class="text-end">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($libros)): ?>
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">
                            <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                            No se encontraron libros en el inventario.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($libros as $libro): ?>
                        <tr>
                            <td class="fw-semibold"><?= htmlspecialchars($libro['titulo']) ?></td>
                            <td><?= htmlspecialchars($libro['autor']) ?></td>
                            <td><span class="text-muted small"><?= htmlspecialchars($libro['isbn']) ?></span></td>
                            <td>
                                <?php if ($libro['categoria_nombre']): ?>
                                    <span class="badge badge-cat"><?= htmlspecialchars($libro['categoria_nombre']) ?></span>
                                <?php else: ?>
                                    <span class="text-muted">—</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-end">$<?= number_format((float) $libro['precio'], 2) ?></td>
                            <td class="text-center">
                                <span class="badge <?= $libro['stock'] <= 5 ? 'text-bg-warning' : 'text-bg-success' ?>">
                                    <?= (int) $libro['stock'] ?>
                                </span>
                            </td>
                            <td class="text-end">
                                <a href="index.php?action=libro-ver&id=<?= $libro['id'] ?>"
                                   class="btn btn-sm btn-outline-secondary" title="Ver">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="index.php?action=libro-editar&id=<?= $libro['id'] ?>"
                                   class="btn btn-sm btn-outline-primary" title="Editar">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="index.php?action=libro-eliminar&id=<?= $libro['id'] ?>" method="post"
                                      class="d-inline" onsubmit="return confirm('¿Eliminar este libro del inventario?');">
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
