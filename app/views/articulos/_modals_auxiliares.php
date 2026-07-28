<!-- Modal Marca -->
<div class="modal fade" id="modalMarca" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <form id="formMarca">
                <div class="modal-header">
                    <h6 class="modal-title">Agregar Marca</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="text" name="nombre" class="form-control" placeholder="Ej. Stabilo" required>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary btn-sm">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Categoría -->
<div class="modal fade" id="modalCategoria" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="formCategoria">
                <div class="modal-header">
                    <h6 class="modal-title">Agregar Categoría</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nombre</label>
                        <input type="text" name="nombre" class="form-control" placeholder="Nombre de la categoría" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Depende de (Subcategoría de)</label>
                        <select name="parent_id" class="form-select">
                            <option value="">-- Es Categoría Principal --</option>
                            <?php
                            $principales = $principales ?? [];
                            if (!empty($principales)): ?>
                                <?php foreach ($principales as $principal): ?>
                                    <option value="<?= $principal['id'] ?>"><?= htmlspecialchars($principal['nombre']) ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary btn-sm">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const formMarca = document.getElementById('formMarca');
    if (formMarca) {
        formMarca.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            fetch('index.php?action=marca-guardar-ajax', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    const select = document.getElementById('marca_id');
                    const option = new Option(data.nombre, data.id, true, true);
                    select.add(option);
                    
                    const modal = bootstrap.Modal.getInstance(document.getElementById('modalMarca'));
                    modal.hide();
                    this.reset();
                } else {
                    alert(data.message);
                }
            })
            .catch(err => alert('Error de conexión al guardar la marca'));
        });
    }

    const formCategoria = document.getElementById('formCategoria');
    if (formCategoria) {
        formCategoria.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            fetch('index.php?action=categoria-guardar-ajax', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    const select = document.getElementById('categoria_id');
                    let texto = data.nombre;
                    let isPrincipal = !data.parent_id;
                    
                    if (!isPrincipal) {
                        texto = '\u00A0\u00A0\u00A0\u00A0\u21B3 ' + data.nombre;
                    }
                    
                    const option = new Option(texto, data.id, true, true);
                    if (isPrincipal) option.className = 'fw-bold';
                    
                    select.add(option);
                    
                    if (isPrincipal) {
                        const selectModal = document.querySelector('#modalCategoria select[name="parent_id"]');
                        if (selectModal) {
                            selectModal.add(new Option(data.nombre, data.id));
                        }
                    }
                    
                    const modal = bootstrap.Modal.getInstance(document.getElementById('modalCategoria'));
                    modal.hide();
                    this.reset();
                } else {
                    alert(data.message);
                }
            })
            .catch(err => alert('Error de conexión al guardar la categoría'));
        });
    }
});
</script>
