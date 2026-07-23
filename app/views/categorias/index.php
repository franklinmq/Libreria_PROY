<?php $msg = $_GET['msg'] ?? ''; ?>

<?php if ($msg === 'creado'): ?>
    <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
        <i class="bi bi-check-circle me-1"></i> Categoría creada correctamente.
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="bi bi-tags me-2"></i>Categorías registradas</h5>
                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalNuevaCategoria">
                    <i class="bi bi-plus-circle me-1"></i> Agregar Categoría
                </button>
            </div>
            <ul class="list-group list-group-flush">
                <?php if (empty($categorias)): ?>
                    <li class="list-group-item text-muted text-center py-4">Aún no hay categorías registradas.</li>
                <?php else: ?>
                    <?php
                    $principales = array_filter($categorias, fn($c) => empty($c['parent_id']));
                    $subcategorias = array_filter($categorias, fn($c) => !empty($c['parent_id']));
                    ?>
                    <?php foreach ($principales as $principal): ?>
                        <li class="list-group-item bg-light fw-bold d-flex justify-content-between align-items-center">
                            <?= htmlspecialchars($principal['nombre']) ?>
                        </li>
                        <?php foreach ($subcategorias as $sub): ?>
                            <?php if ($sub['parent_id'] == $principal['id']): ?>
                                <li class="list-group-item d-flex justify-content-between align-items-center ps-4 text-muted">
                                    &#8627; <?= htmlspecialchars($sub['nombre']) ?>
                                </li>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    <?php endforeach; ?>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</div>

<!-- Modal Nueva Categoría -->
<div class="modal fade" id="modalNuevaCategoria" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post" action="index.php?action=categoria-guardar">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-tag me-2"></i>Nueva categoría</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nombre de la categoría</label>
                        <input type="text" name="nombre" class="form-control" placeholder="Ej. Ciencia ficción" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Depende de (Subcategoría de)</label>
                        <select name="parent_id" class="form-select">
                            <option value="">-- Es Categoría Principal --</option>
                            <?php if (!empty($principales)): ?>
                                <?php foreach ($principales as $principal): ?>
                                    <option value="<?= $principal['id'] ?>"><?= htmlspecialchars($principal['nombre']) ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-1"></i> Guardar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
