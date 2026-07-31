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
        <i class="bi bi-exclamation-circle me-1"></i> No se pudo eliminar la categoría. (Es posible que tenga articulos o subcategorías asignados).
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card shadow-sm border-0 rounded-3">
            <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                <h5 class="mb-0 fw-bold" style="color: var(--brand-primary);"><i class="bi bi-tags me-2"></i>Gestión de Categorías</h5>
                <button type="button" class="btn btn-primary btn-sm rounded-pill px-3 shadow-sm" onclick="abrirModalCreacion()">
                    <i class="bi bi-plus-circle me-1"></i> Nueva Categoría
                </button>
            </div>
            
            <?php
            $principales = array_filter($categorias, fn($c) => empty($c['parent_id']));
            $subcategorias = array_filter($categorias, fn($c) => !empty($c['parent_id']));
            ?>
            <style>
                /* Estilos personalizados para el modal de categoría */
                .input-green-border {
                    border: 1px solid #7cb342 !important;
                    border-radius: 4px;
                }
                .input-green-border:focus {
                    box-shadow: 0 0 0 0.25rem rgba(124, 179, 66, 0.25);
                    border-color: #7cb342 !important;
                }
                .btn-add-subcat {
                    background-color: #4a78c2;
                    color: white;
                    border: none;
                    border-radius: 4px;
                    width: 42px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                }
                .btn-add-subcat:hover {
                    background-color: #3b62a0;
                    color: white;
                }
                .subcat-list-container {
                    margin-top: 10px;
                }
                .subcat-item {
                    padding: 8px 12px;
                    font-weight: 500;
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                }
                .subcat-item.bg-primary-custom {
                    background-color: #5581c9;
                    color: white;
                }
                .subcat-item.bg-light-1 {
                    background-color: #d1d6e8;
                    color: #333;
                }
                .subcat-item.bg-light-2 {
                    background-color: #e6e9f2;
                    color: #333;
                }
                .btn-remove-subcat {
                    cursor: pointer;
                    opacity: 0.7;
                }
                .btn-remove-subcat:hover {
                    opacity: 1;
                }
                .toggle-icon {
                    transition: transform 0.25s ease;
                    display: inline-block;
                    font-size: 0.85rem;
                    vertical-align: middle;
                }
                td[aria-expanded="true"] .toggle-icon {
                    transform: rotate(90deg);
                }
                tr.collapse.show {
                    display: table-row;
                }
            </style>
            
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
                                <?php
                                $tieneSub = false;
                                foreach ($subcategorias as $sub) {
                                    if ($sub['parent_id'] == $principal['id']) {
                                        $tieneSub = true;
                                        break;
                                    }
                                }
                                ?>
                                <!-- Categoría Principal -->
                                <tr>
                                    <!-- Celda de Nombre -->
                                    <td class="align-top py-2">
                                        <div class="fw-bold d-flex align-items-center" <?= $tieneSub ? 'data-bs-toggle="collapse" data-bs-target=".subcat-col-'.$principal['id'].'" aria-expanded="false" style="cursor: pointer; min-height: 32px;" title="Clic para ver subcategorías"' : 'style="min-height: 32px;"' ?>>
                                            <?php if($tieneSub): ?>
                                                <i class="bi bi-chevron-right me-2 toggle-icon text-muted"></i>
                                            <?php else: ?>
                                                <span class="ms-3 d-inline-block" style="width: 14px;"></span>
                                            <?php endif; ?>
                                            <i class="bi bi-folder2-open text-warning me-2 fs-5"></i> 
                                            <span><?= htmlspecialchars($principal['nombre']) ?></span>
                                        </div>
                                        <!-- Lista de Subcategorías (Nombres) -->
                                        <?php if($tieneSub): ?>
                                            <div class="collapse subcat-col-<?= $principal['id'] ?>">
                                                <div class="mt-1"></div>
                                                <?php foreach ($subcategorias as $sub): ?>
                                                    <?php if ($sub['parent_id'] == $principal['id']): ?>
                                                        <div class="d-flex align-items-center ps-5 text-muted border-top border-light" style="min-height: 36px;">
                                                            <i class="bi bi-arrow-return-right me-2"></i>
                                                            <span class="small"><?= htmlspecialchars($sub['nombre']) ?></span>
                                                        </div>
                                                    <?php endif; ?>
                                                <?php endforeach; ?>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    
                                    <!-- Celda de Acciones -->
                                    <?php
                                    $misSubcats = array_filter($subcategorias, fn($s) => $s['parent_id'] == $principal['id']);
                                    $nombresSubcats = array_column($misSubcats, 'nombre');
                                    $jsonSubcats = htmlspecialchars(json_encode(array_values($nombresSubcats)));
                                    ?>
                                    <td class="text-end align-top py-2">
                                        <div class="d-flex align-items-center justify-content-end" style="min-height: 32px;">
                                            <button type="button" class="btn btn-sm btn-outline-secondary me-1 py-1 px-2" onclick="abrirModalEdicion(<?= $principal['id'] ?>, '<?= htmlspecialchars(addslashes($principal['nombre'])) ?>', this)" data-subcats="<?= $jsonSubcats ?>">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-danger py-1 px-2" onclick="confirmarEliminar(<?= $principal['id'] ?>, '<?= htmlspecialchars(addslashes($principal['nombre'])) ?>')">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php $modalCategoriaAction = "index.php?action=categoria-guardar"; ?>
<?php include __DIR__ . '/_modal_categoria.php'; ?>

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
function abrirModalCreacion() {
    document.getElementById('formCategoria').reset();
    document.getElementById('categoria_id').value = '';
    document.getElementById('modalCategoriaTitle').innerHTML = '<i class="bi bi-tag text-primary me-2"></i>Nueva categoría';
    modalSubcats = [];
    renderModalSubcats();
    new bootstrap.Modal(document.getElementById('modalCategoria')).show();
}

function abrirModalEdicion(id, nombre, btn) {
    document.getElementById('formCategoria').reset();
    document.getElementById('categoria_id').value = id;
    document.getElementById('categoria_nombre').value = nombre;
    document.getElementById('modalCategoriaTitle').innerHTML = '<i class="bi bi-pencil-square text-primary me-2"></i>Editar categoría';
    
    const subcatsJson = btn.getAttribute('data-subcats');
    if(subcatsJson) {
        modalSubcats = JSON.parse(subcatsJson);
    } else {
        modalSubcats = [];
    }
    
    renderModalSubcats();
    new bootstrap.Modal(document.getElementById('modalCategoria')).show();
}

function confirmarEliminar(id, nombre) {
    document.getElementById('delete_id').value = id;
    document.getElementById('delete_nombre').innerText = nombre;
    new bootstrap.Modal(document.getElementById('modalEliminarCategoria')).show();
}
</script>
