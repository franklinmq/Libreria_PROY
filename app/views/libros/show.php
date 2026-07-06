<div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="card shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="bi bi-book me-2"></i>Detalle del libro</h5>
                <a href="index.php?action=libros" class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> Volver
                </a>
            </div>
            <div class="card-body">
                <dl class="row mb-0">
                    <dt class="col-sm-4">Título</dt>
                    <dd class="col-sm-8"><?= htmlspecialchars($libro['titulo']) ?></dd>

                    <dt class="col-sm-4">Autor</dt>
                    <dd class="col-sm-8"><?= htmlspecialchars($libro['autor']) ?></dd>

                    <dt class="col-sm-4">ISBN</dt>
                    <dd class="col-sm-8"><?= htmlspecialchars($libro['isbn']) ?></dd>

                    <dt class="col-sm-4">Editorial</dt>
                    <dd class="col-sm-8"><?= htmlspecialchars($libro['editorial'] ?: '—') ?></dd>

                    <dt class="col-sm-4">Categoría</dt>
                    <dd class="col-sm-8"><?= htmlspecialchars($libro['categoria_nombre'] ?? '—') ?></dd>

                    <dt class="col-sm-4">Precio</dt>
                    <dd class="col-sm-8">$<?= number_format((float) $libro['precio'], 2) ?></dd>

                    <dt class="col-sm-4">Stock disponible</dt>
                    <dd class="col-sm-8">
                        <span class="badge <?= $libro['stock'] <= 5 ? 'text-bg-warning' : 'text-bg-success' ?>">
                            <?= (int) $libro['stock'] ?> unidades
                        </span>
                    </dd>
                </dl>
            </div>
            <div class="card-footer bg-white d-flex justify-content-end gap-2">
                <a href="index.php?action=libro-editar&id=<?= $libro['id'] ?>" class="btn btn-outline-primary">
                    <i class="bi bi-pencil me-1"></i> Editar
                </a>
                <form action="index.php?action=libro-eliminar&id=<?= $libro['id'] ?>" method="post"
                      onsubmit="return confirm('¿Eliminar este libro del inventario?');">
                    <button type="submit" class="btn btn-outline-danger">
                        <i class="bi bi-trash me-1"></i> Eliminar
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
