<?php $msg = $_GET['msg'] ?? ''; ?>

<?php if ($msg === 'creado'): ?>
    <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
        <i class="bi bi-check-circle me-1"></i> Categoría creada correctamente.
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php elseif ($msg === 'actualizado'): ?>
    <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
        <i class="bi bi-check-circle me-1"></i> Categoría actualizada correctamente.
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php elseif ($msg === 'eliminado'): ?>
    <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
        <i class="bi bi-check-circle me-1"></i> Categoría eliminada correctamente.
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php elseif ($msg === 'error_eliminar'): ?>
    <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
        <i class="bi bi-exclamation-circle me-1"></i> No se pudo eliminar la categoría. (Es posible que tenga productos o subcategorías asignados).
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card shadow-sm border-0 rounded-3">
            <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                <h5 class="mb-0 fw-bold" style="color: var(--brand-primary);"><i class="bi bi-tags me-2"></i>Gestión de Categorías</h5>
                <button type="button" class="btn btn-primary btn-sm rounded-pill px-3 shadow-sm" data-bs-toggle="modal" data-bs-target="#modalNuevaCategoria">
                    <i class="bi bi-plus-circle me-1"></i> Nueva Categoría
                </button>
            </div>
            
            <?php
            $principales = array_filter($categorias, fn($c) => empty($c['parent_id']));
            $subcategorias = array_filter($categorias, fn($c) => !empty($c['parent_id']));
            ?>
            
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 table-datatable">
                    <thead class="table-light">
                        <tr>
                            <th>Nombre</th>
                            <th class="text-end" style="width: 150px;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($categorias)): ?>
                            <tr>
                                <td colspan="2" class="text-center text-muted py-4">Aún no hay categorías registradas.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($principales as $principal): ?>
                                <!-- Categoría Principal -->
                                <tr>
                                    <td class="fw-bold">
                                        <i class="bi bi-folder2-open text-warning me-2 fs-5"></i> 
                                        <?= htmlspecialchars($principal['nombre']) ?>
                                    </td>
                                    <td class="text-end">
                                        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="abrirModalEdicion(<?= $principal['id'] ?>, '<?= htmlspecialchars(addslashes($principal['nombre'])) ?>', '')">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-danger" onclick="confirmarEliminar(<?= $principal['id'] ?>, '<?= htmlspecialchars(addslashes($principal['nombre'])) ?>')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                <!-- Subcategorías -->
                                <?php foreach ($subcategorias as $sub): ?>
                                    <?php if ($sub['parent_id'] == $principal['id']): ?>
                                        <tr>
                                            <td class="ps-5 text-muted">
                                                <i class="bi bi-arrow-return-right me-2"></i>
                                                <?= htmlspecialchars($sub['nombre']) ?>
                                            </td>
                                            <td class="text-end">
                                                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="abrirModalEdicion(<?= $sub['id'] ?>, '<?= htmlspecialchars(addslashes($sub['nombre'])) ?>', <?= $sub['parent_id'] ?>)">
                                                    <i class="bi bi-pencil"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-outline-danger" onclick="confirmarEliminar(<?= $sub['id'] ?>, '<?= htmlspecialchars(addslashes($sub['nombre'])) ?>')">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Nueva Categoría -->
<div class="modal fade" id="modalNuevaCategoria" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <form method="post" action="index.php?action=categoria-guardar">
                <div class="modal-header border-bottom-0">
                    <h5 class="modal-title fw-bold"><i class="bi bi-tag text-primary me-2"></i>Nueva categoría</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold">NOMBRE</label>
                        <input type="text" name="nombre" class="form-control" placeholder="Ej. Ciencia ficción" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold">DEPENDE DE (OPCIONAL)</label>
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
                <div class="modal-footer border-top-0 bg-light rounded-bottom">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary px-4">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Editar Categoría -->
<div class="modal fade" id="modalEditarCategoria" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <form method="post" action="index.php?action=categoria-actualizar">
                <input type="hidden" name="id" id="edit_id">
                <div class="modal-header border-bottom-0">
                    <h5 class="modal-title fw-bold"><i class="bi bi-pencil-square text-primary me-2"></i>Editar categoría</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold">NOMBRE</label>
                        <input type="text" name="nombre" id="edit_nombre" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold">DEPENDE DE</label>
                        <select name="parent_id" id="edit_parent_id" class="form-select">
                            <option value="">-- Es Categoría Principal --</option>
                            <?php if (!empty($principales)): ?>
                                <?php foreach ($principales as $principal): ?>
                                    <option value="<?= $principal['id'] ?>"><?= htmlspecialchars($principal['nombre']) ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer border-top-0 bg-light rounded-bottom">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary px-4">Actualizar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Eliminar Categoría -->
<div class="modal fade" id="modalEliminarCategoria" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content border-0 shadow text-center">
            <form method="post" action="index.php?action=categoria-eliminar">
                <input type="hidden" name="id" id="delete_id">
                <div class="modal-body p-4">
                    <i class="bi bi-exclamation-circle text-danger display-4 d-block mb-3"></i>
                    <h5 class="fw-bold mb-2">¿Eliminar categoría?</h5>
                    <p class="text-muted small mb-4">Estás a punto de eliminar <strong id="delete_nombre"></strong>. Esta acción no se puede deshacer.</p>
                    <div class="d-flex justify-content-center gap-2">
                        <button type="button" class="btn btn-light w-50" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-danger w-50">Eliminar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function abrirModalEdicion(id, nombre, parentId) {
    document.getElementById('edit_id').value = id;
    document.getElementById('edit_nombre').value = nombre;
    
    const selectParent = document.getElementById('edit_parent_id');
    selectParent.value = parentId ? parentId : "";
    
    // Disable selecting itself as parent
    Array.from(selectParent.options).forEach(opt => {
        if(opt.value == id) {
            opt.disabled = true;
        } else {
            opt.disabled = false;
        }
    });

    new bootstrap.Modal(document.getElementById('modalEditarCategoria')).show();
}

function confirmarEliminar(id, nombre) {
    document.getElementById('delete_id').value = id;
    document.getElementById('delete_nombre').innerText = nombre;
    new bootstrap.Modal(document.getElementById('modalEliminarCategoria')).show();
}
</script>
