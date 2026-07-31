<!-- Modal Categoría -->
<div class="modal fade" id="modalCategoria" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <form id="formCategoria" <?= isset($modalCategoriaAction) ? 'method="post" action="'.$modalCategoriaAction.'"' : '' ?>>
                <input type="hidden" name="id" id="categoria_id" value="">
                <div class="modal-header border-bottom-0">
                    <h5 class="modal-title fw-bold" id="modalCategoriaTitle"><i class="bi bi-tag text-primary me-2"></i>Nueva categoría</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold">NOMBRE</label>
                        <input type="text" name="nombre" id="categoria_nombre" class="form-control" placeholder="Ej. Bolígrafo" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold">SUBCATEGORIAS</label>
                        <div class="d-flex gap-2">
                            <input type="text" id="inputSubcategoria" class="form-control" placeholder="Ej. Con brillo">
                            <button type="button" class="btn btn-primary d-flex align-items-center justify-content-center" id="btnAddSubcategoria" style="width: 42px;">
                                <i class="bi bi-check-lg" style="font-size: 1.2rem;"></i>
                            </button>
                        </div>
                    </div>
                    
                    <!-- Lista visual de subcategorías -->
                    <div class="subcat-list-container d-flex flex-column" id="listaSubcategoriasVisual">
                        <!-- Items insertados con JS -->
                    </div>
                    
                    <!-- Contenedor para inputs hidden que se enviarán en el POST -->
                    <div id="contenedorSubcategoriasHidden"></div>
                    
                </div>
                <div class="modal-footer border-top-0 bg-light rounded-bottom">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary px-4">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.subcat-list-container { margin-top: 10px; gap: 5px; }
.subcat-item { padding: 8px 12px; font-weight: 500; display: flex; justify-content: space-between; align-items: center; border-radius: 4px; border: 1px solid #dee2e6; background-color: #f8f9fa; color: #212529; }
.btn-remove-subcat { cursor: pointer; opacity: 0.5; transition: opacity 0.2s; }
.btn-remove-subcat:hover { opacity: 1; color: #dc3545; }
</style>

<script>
let modalSubcats = [];

function renderModalSubcats() {
    const listaVisual = document.getElementById('listaSubcategoriasVisual');
    const contenedorHidden = document.getElementById('contenedorSubcategoriasHidden');
    
    listaVisual.innerHTML = '';
    contenedorHidden.innerHTML = '';
    
    modalSubcats.forEach((sub, index) => {
        const item = document.createElement('div');
        item.className = 'subcat-item';
        item.innerHTML = `
            <span class="text-uppercase">${sub}</span>
            <i class="bi bi-x-lg btn-remove-subcat" data-index="${index}"></i>
        `;
        listaVisual.appendChild(item);

        const hidden = document.createElement('input');
        hidden.type = 'hidden';
        hidden.name = 'subcategorias[]';
        hidden.value = sub;
        contenedorHidden.appendChild(hidden);
    });

    document.querySelectorAll('.btn-remove-subcat').forEach(btn => {
        btn.addEventListener('click', function() {
            const idx = parseInt(this.getAttribute('data-index'));
            modalSubcats.splice(idx, 1);
            renderModalSubcats();
        });
    });
}

document.addEventListener('DOMContentLoaded', function() {
    const btnAddSub = document.getElementById('btnAddSubcategoria');
    const inputSub = document.getElementById('inputSubcategoria');

    if(btnAddSub && inputSub) {
        btnAddSub.addEventListener('click', function() {
            const val = inputSub.value.trim();
            if (val) {
                modalSubcats.push(val);
                inputSub.value = '';
                renderModalSubcats();
            }
        });

        inputSub.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                btnAddSub.click();
            }
        });
    }
    
    const inputNombre = document.getElementById('categoria_nombre');
    if (inputNombre && inputSub) {
        inputNombre.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                inputSub.focus();
            }
        });
    }
});
</script>
