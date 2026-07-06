<?php $msg = $_GET['msg'] ?? ''; ?>

<?php if ($msg === 'creado'): ?>
    <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
        <i class="bi bi-check-circle me-1"></i> Categoría creada correctamente.
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="row g-4">
    <div class="col-lg-4">
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="bi bi-tag me-2"></i>Nueva categoría</h5>
            </div>
            <div class="card-body">
                <form method="post" action="index.php?action=categoria-guardar">
                    <label class="form-label">Nombre de la categoría</label>
                    <input type="text" name="nombre" class="form-control mb-3" placeholder="Ej. Ciencia ficción" required>
                    <button type="submit" class="btn btn-add w-100">
                        <i class="bi bi-plus-circle me-1"></i> Agregar categoría
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="bi bi-tags me-2"></i>Categorías registradas</h5>
            </div>
            <ul class="list-group list-group-flush">
                <?php if (empty($categorias)): ?>
                    <li class="list-group-item text-muted text-center py-4">Aún no hay categorías registradas.</li>
                <?php else: ?>
                    <?php foreach ($categorias as $cat): ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <?= htmlspecialchars($cat['nombre']) ?>
                            <span class="badge badge-cat">ID #<?= $cat['id'] ?></span>
                        </li>
                    <?php endforeach; ?>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</div>
