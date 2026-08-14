<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="bi bi-cart-plus me-2"></i>Registrar Nueva Compra</h5>
                <a href="index.php?action=compras" class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> Cancelar
                </a>
            </div>
            
            <div class="card-body">
                <?php if (!empty($errores)): ?>
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            <?php foreach ($errores as $err): ?>
                                <li><?= htmlspecialchars($err) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <form action="index.php?action=compra-guardar" method="post" id="formCompra">
                    <!-- Datos de la cabecera -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label class="form-label">Proveedor</label>
                            <select name="proveedor_id" class="form-select">
                                <option value="">-- Seleccionar Proveedor (Opcional) --</option>
                                <?php foreach ($proveedores as $prov): ?>
                                    <option value="<?= $prov['id'] ?>" <?= (($old['proveedor_id'] ?? '') == $prov['id']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($prov['nombre']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6 d-flex align-items-end justify-content-end">
                            <h4 class="mb-0">Total: <span id="lblTotal" class="text-success fw-bold">Bs. 0.00</span></h4>
                        </div>
                    </div>

                    <hr>

                    <!-- Buscador de Artículos -->
                    <div class="mb-4">
                        <div class="input-group mb-2">
                            <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                            <input type="text" id="buscadorArticulos" class="form-control form-control-lg border-start-0 ps-0" style="box-shadow: none;" placeholder="Buscar por nombre, categoría, modelo...">
                        </div>
                        <div class="text-muted small mb-3">
                            <i class="bi bi-box"></i> <span id="countArticulos"><?= count($articulos) ?></span> productos registrados
                        </div>
                        
                        <div class="table-responsive border rounded bg-white" style="max-height: 400px; overflow-y: auto;">
                            <table class="table table-hover align-middle mb-0" id="tablaSeleccionArticulos">
                                <thead class="table-light sticky-top shadow-sm">
                                    <tr>
                                        <th>Producto</th>
                                        <th>Categoría</th>
                                        <th>Stock</th>
                                        <th class="text-end pe-4">Acción</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($articulos as $art): ?>
                                        <tr class="articulo-item">
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <?php if (!empty($art['imagen'])): ?>
                                                        <img src="uploads/<?= htmlspecialchars($art['imagen']) ?>" alt="<?= htmlspecialchars($art['nombre']) ?>" style="width: 48px; height: 48px; object-fit: contain;" class="rounded bg-white p-1 border me-3">
                                                    <?php else: ?>
                                                        <div class="bg-light border rounded me-3 d-flex align-items-center justify-content-center text-secondary" style="width: 48px; height: 48px;">
                                                            <i class="bi bi-image fs-4"></i>
                                                        </div>
                                                    <?php endif; ?>
                                                    <div>
                                                        <h6 class="mb-0 fw-bold articulo-nombre text-dark"><?= htmlspecialchars($art['nombre']) ?></h6>
                                                        <small class="text-muted articulo-descripcion"><?= htmlspecialchars($art['descripcion']) ?></small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="articulo-categoria">
                                                <span class="badge bg-light text-dark border fw-normal"><?= htmlspecialchars($art['categoria_nombre'] ?? 'Sin categoría') ?></span>
                                            </td>
                                            <td>
                                                <span class="fw-bold text-danger"><?= $art['stock'] ?></span>
                                            </td>
                                            <td class="text-end pe-3">
                                                <button type="button" class="btn btn-sm text-white btn-agregar-articulo" 
                                                        style="background-color: #d87b5d; border-radius: 6px; padding: 4px 12px;"
                                                        data-id="<?= $art['id'] ?>"
                                                        data-nombre="<?= htmlspecialchars($art['nombre']) ?>"
                                                        data-precio="<?= $art['precio_compra'] ?>">
                                                    <i class="bi bi-plus-lg"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                    <tr id="noResultsRow" style="display: none;">
                                        <td colspan="4" class="text-center py-4 text-muted">No se encontraron productos</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Detalle de la compra -->
                    <div class="table-responsive mb-4">
                        <table class="table table-bordered align-middle" id="tablaDetalles">
                            <thead class="table-light">
                                <tr>
                                    <th>Artículo</th>
                                    <th width="120">Cantidad</th>
                                    <th width="150">Costo Unit. (Bs.)</th>
                                    <th width="150">Subtotal (Bs.)</th>
                                    <th width="80" class="text-center">Quitar</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Filas dinámicas aquí -->
                            </tbody>
                        </table>
                        <div id="empty-state" class="text-center text-muted py-3">
                            No hay artículos agregados aún.
                        </div>
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary" id="btnGuardarCompra" disabled>
                            <i class="bi bi-save me-1"></i> Guardar Compra
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const buscadorArticulos = document.getElementById('buscadorArticulos');
    const articulosItems = document.querySelectorAll('.articulo-item');
    const noResultsRow = document.getElementById('noResultsRow');
    const countArticulos = document.getElementById('countArticulos');
    
    const tablaDetalles = document.getElementById('tablaDetalles').querySelector('tbody');
    const emptyState = document.getElementById('empty-state');
    const lblTotal = document.getElementById('lblTotal');
    const btnGuardarCompra = document.getElementById('btnGuardarCompra');

    // Búsqueda de artículos
    if (buscadorArticulos) {
        buscadorArticulos.addEventListener('input', function() {
            const query = this.value.toLowerCase().trim();
            let count = 0;
            
            articulosItems.forEach(item => {
                const nombre = item.querySelector('.articulo-nombre').textContent.toLowerCase();
                const desc = item.querySelector('.articulo-descripcion').textContent.toLowerCase();
                const cat = item.querySelector('.articulo-categoria').textContent.toLowerCase();
                
                if (nombre.includes(query) || desc.includes(query) || cat.includes(query)) {
                    item.style.display = '';
                    count++;
                } else {
                    item.style.display = 'none';
                }
            });
            
            if (countArticulos) countArticulos.textContent = count;
            if (noResultsRow) {
                noResultsRow.style.display = count === 0 ? '' : 'none';
            }
        });
    }

    // Agregar artículo a la compra
    document.querySelectorAll('.btn-agregar-articulo').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            const nombre = this.getAttribute('data-nombre');
            const precio = parseFloat(this.getAttribute('data-precio')).toFixed(2);

            // Verificar si ya existe en la tabla
            const existingInput = document.querySelector(`input[name="articulos[]"][value="${id}"]`);
            if (existingInput) {
                const tr = existingInput.closest('tr');
                const inputCantidad = tr.querySelector('.input-cantidad');
                inputCantidad.value = parseInt(inputCantidad.value) + 1;
                
                // Disparar evento para actualizar el subtotal y total
                inputCantidad.dispatchEvent(new Event('input'));
                
                // Feedback visual
                const iconoOriginal = this.innerHTML;
                this.innerHTML = '<i class="bi bi-check-lg"></i>';
                setTimeout(() => {
                    this.innerHTML = iconoOriginal;
                }, 1000);
                
                return;
            }

            emptyState.style.display = 'none';
            btnGuardarCompra.disabled = false;

            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td>
                    ${nombre}
                    <input type="hidden" name="articulos[]" value="${id}">
                </td>
                <td>
                    <input type="number" name="cantidades[]" class="form-control form-control-sm input-cantidad" value="1" min="1" required>
                </td>
                <td>
                    <input type="number" name="precios[]" class="form-control form-control-sm input-precio" value="${precio}" min="0" step="0.01" required>
                </td>
                <td class="text-end subtotal-celda fw-semibold">
                    ${precio}
                </td>
                <td class="text-center">
                    <button type="button" class="btn btn-sm btn-outline-danger btn-eliminar">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </td>
            `;

            tablaDetalles.appendChild(tr);

            // Eventos de la nueva fila
            tr.querySelector('.btn-eliminar').addEventListener('click', function() {
                tr.remove();
                calcularTotal();
                if (tablaDetalles.children.length === 0) {
                    emptyState.style.display = 'block';
                    btnGuardarCompra.disabled = true;
                }
            });

            tr.querySelector('.input-cantidad').addEventListener('input', function() {
                actualizarSubtotal(tr);
            });

            tr.querySelector('.input-precio').addEventListener('input', function() {
                actualizarSubtotal(tr);
            });

            calcularTotal();
            
            // Feedback visual
            const iconoOriginal = this.innerHTML;
            this.innerHTML = '<i class="bi bi-check-lg"></i>';
            setTimeout(() => {
                this.innerHTML = iconoOriginal;
            }, 1000);
        });
    });

    function actualizarSubtotal(tr) {
        const cant = parseFloat(tr.querySelector('.input-cantidad').value) || 0;
        const precio = parseFloat(tr.querySelector('.input-precio').value) || 0;
        const subtotal = cant * precio;
        tr.querySelector('.subtotal-celda').innerText = subtotal.toFixed(2);
        calcularTotal();
    }

    function calcularTotal() {
        let total = 0;
        document.querySelectorAll('.subtotal-celda').forEach(celda => {
            total += parseFloat(celda.innerText) || 0;
        });
        lblTotal.innerText = 'Bs. ' + total.toFixed(2);
    }
});
</script>
