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

<?php include __DIR__ . '/../categorias/_modal_categoria.php'; ?>

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

    // Limpiar modal al abrir (en caso de usarlo desde aquí y no con abrirModalCreacion)
    const modalCategoriaEl = document.getElementById('modalCategoria');
    if (modalCategoriaEl) {
        modalCategoriaEl.addEventListener('show.bs.modal', function () {
            document.getElementById('formCategoria').reset();
            if (typeof modalSubcats !== 'undefined') {
                modalSubcats = [];
                if (typeof renderModalSubcats === 'function') {
                    renderModalSubcats();
                }
            }
        });
    }

    const formCategoria = document.getElementById('formCategoria');
    if (formCategoria) {
        formCategoria.addEventListener('submit', function(e) {
            // Si el formCategoria no tiene un "action" definido u otra bandera, usamos AJAX
            // Como usamos el mismo form, revisamos si tiene un action, o simplemente lo interceptamos
            // Aquí, en "_modals_auxiliares", SIEMPRE queremos AJAX.
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
                    
                    // Añadir la categoría principal
                    const mainOption = new Option(data.nombre, data.id, true, true);
                    mainOption.className = 'fw-bold';
                    select.add(mainOption);
                    
                    // Añadir subcategorías si las hay
                    if (data.subcategorias && data.subcategorias.length > 0) {
                        data.subcategorias.forEach(sub => {
                            const subText = '\u00A0\u00A0\u00A0\u00A0\u21B3 ' + sub.nombre;
                            const subOption = new Option(subText, sub.id);
                            select.add(subOption);
                        });
                    }
                    
                    const modal = bootstrap.Modal.getInstance(document.getElementById('modalCategoria'));
                    modal.hide();
                    this.reset();
                    if (typeof modalSubcats !== 'undefined') {
                        modalSubcats = [];
                        if (typeof renderModalSubcats === 'function') {
                            renderModalSubcats();
                        }
                    }
                } else {
                    alert(data.message);
                }
            })
            .catch(err => alert('Error de conexión al guardar la categoría'));
        });
    }
});
</script>
