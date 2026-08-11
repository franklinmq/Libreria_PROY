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
                    <div class="row mb-3 align-items-end">
                        <div class="col-md-6">
                            <label class="form-label">Agregar Artículo</label>
                            <select id="selectArticulo" class="form-select">
                                <option value="">-- Seleccione un artículo --</option>
                                <?php foreach ($articulos as $art): ?>
                                    <option value="<?= $art['id'] ?>" 
                                            data-nombre="<?= htmlspecialchars($art['nombre']) ?>" 
                                            data-precio="<?= $art['precio_compra'] ?>">
                                        <?= htmlspecialchars($art['nombre']) ?> (Stock: <?= $art['stock'] ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <button type="button" id="btnAgregarArticulo" class="btn btn-success w-100">
                                <i class="bi bi-plus-lg"></i> Agregar a la lista
                            </button>
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
    const selectArticulo = document.getElementById('selectArticulo');
    const btnAgregarArticulo = document.getElementById('btnAgregarArticulo');
    const tablaDetalles = document.getElementById('tablaDetalles').querySelector('tbody');
    const emptyState = document.getElementById('empty-state');
    const lblTotal = document.getElementById('lblTotal');
    const btnGuardarCompra = document.getElementById('btnGuardarCompra');

    btnAgregarArticulo.addEventListener('click', function() {
        const option = selectArticulo.options[selectArticulo.selectedIndex];
        if (!option.value) return;

        const id = option.value;
        const nombre = option.getAttribute('data-nombre');
        const precio = parseFloat(option.getAttribute('data-precio')).toFixed(2);

        // Verificar si ya existe en la tabla
        if (document.querySelector(`input[name="articulos[]"][value="${id}"]`)) {
            alert('El artículo ya está en la lista.');
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

        // Limpiar select
        selectArticulo.value = "";

        // Eventos
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
