<style>
/* Custom POS Styles adaptados al tema del sistema */
.pos-card {
    border: 1px solid rgba(0,0,0,0.125);
    border-radius: 0.375rem;
    box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,0.075);
    margin-bottom: 1.25rem;
    overflow: hidden;
    background: #fff;
}
.pos-card-header {
    padding: 1rem 1.25rem;
    font-weight: 600;
    color: #212529;
    border-bottom: 1px solid rgba(0,0,0,0.125);
    background-color: #fff;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.product-card {
    cursor: pointer;
    transition: all 0.2s ease-in-out;
    border: 1px solid #dee2e6;
    border-radius: 0.375rem;
    height: 100%;
    background-color: #fff;
}
.product-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.15);
    border-color: #0d6efd;
}
.product-card.disabled {
    opacity: 0.6;
    pointer-events: none;
    filter: grayscale(100%);
}
.product-img-wrapper {
    height: 140px;
    background-color: #f8f9fa;
    display: flex;
    align-items: center;
    justify-content: center;
    border-top-left-radius: 0.375rem;
    border-top-right-radius: 0.375rem;
    overflow: hidden;
    border-bottom: 1px solid #dee2e6;
}
.product-img-wrapper img {
    max-height: 100%;
    max-width: 100%;
    object-fit: contain;
}
.cart-item {
    border-bottom: 1px solid #dee2e6;
    padding: 1rem 0;
}
.cart-item:last-child {
    border-bottom: none;
}
.empty-cart-icon {
    font-size: 4rem;
    color: #ced4da;
}
/* Scrollable areas */
.products-scroll {
    max-height: calc(100vh - 350px);
    overflow-y: auto;
    padding-right: 5px;
}
.cart-scroll {
    max-height: calc(100vh - 400px);
    overflow-y: auto;
    padding-right: 5px;
}
/* Scrollbar styling */
::-webkit-scrollbar { width: 6px; }
::-webkit-scrollbar-track { background: #f8f9fa; }
::-webkit-scrollbar-thumb { background: #dee2e6; border-radius: 3px; }
::-webkit-scrollbar-thumb:hover { background: #adb5bd; }
</style>

<div class="row justify-content-center">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="mb-0 fw-bold text-dark"><i class="bi bi-cart4 me-2"></i>Nueva Venta POS</h5>
            <a href="index.php?action=ventas" class="btn btn-sm btn-outline-secondary shadow-sm">
                <i class="bi bi-arrow-left"></i> Volver a Ventas
            </a>
        </div>

        <?php if (!empty($errores)): ?>
            <div class="alert alert-danger shadow-sm">
                <ul class="mb-0">
                    <?php foreach ($errores as $err): ?>
                        <li><?= htmlspecialchars($err) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form action="index.php?action=venta-guardar" method="post" id="formVenta">
            <div class="row">
                <!-- PANEL IZQUIERDO: CLIENTE, BÚSQUEDA Y PRODUCTOS -->
                <div class="col-lg-7">
                    
                    <!-- Controles de Venta -->
                    <div class="pos-card mb-3">
                        <div class="card-body p-3">
                            <!-- Cliente (Línea 1) -->
                            <div class="d-flex align-items-center mb-3">
                                <label class="form-label mb-0 fw-bold me-2 text-nowrap" style="min-width: 85px;"><i class="bi bi-person-fill text-secondary"></i> Cliente:</label>
                                <input type="text" name="cliente_nombre" class="form-control form-control-sm" placeholder="Público General" value="<?= htmlspecialchars($old['cliente_nombre'] ?? '') ?>">
                            </div>
                            
                            <!-- Búsqueda (Línea 2) -->
                            <div class="d-flex align-items-center">
                                <label class="form-label mb-0 fw-bold me-2 text-nowrap" style="min-width: 85px;"><i class="bi bi-search text-secondary"></i> Buscar:</label>
                                <div class="input-group input-group-sm w-100">
                                    <span class="input-group-text bg-white border-end-0"><i class="bi bi-upc-scan text-muted"></i></span>
                                    <input type="text" id="buscadorArticulos" class="form-control border-start-0 ps-0" placeholder="Nombre o categoría del producto..." autofocus autocomplete="off" style="box-shadow: none;">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tarjeta Productos -->
                    <div class="pos-card">
                        <div class="pos-card-header d-flex justify-content-between align-items-center py-2">
                            <span><i class="bi bi-box-seam text-secondary"></i> Catálogo de Productos</span>
                            <small class="text-muted fw-normal">Mostrando <span id="countArticulos" class="fw-bold"><?= count($articulos) ?></span> artículos</small>
                        </div>
                        <div class="card-body p-2">
                            <div class="products-scroll pe-1">
                                <div class="row row-cols-2 row-cols-md-3 g-3" id="contenedorProductos">
                                    <?php foreach ($articulos as $art): ?>
                                        <div class="col articulo-item">
                                            <div class="card product-card h-100 <?= $art['stock'] <= 0 ? 'disabled' : '' ?>" 
                                                 data-id="<?= $art['id'] ?>"
                                                 data-nombre="<?= htmlspecialchars($art['nombre']) ?>"
                                                 data-precio="<?= $art['precio_venta'] ?>"
                                                 data-stock="<?= $art['stock'] ?>">
                                                
                                                <!-- Imagen -->
                                                <div class="product-img-wrapper position-relative">
                                                    <?php if (!empty($art['imagen'])): ?>
                                                        <img src="uploads/<?= htmlspecialchars($art['imagen']) ?>" alt="<?= htmlspecialchars($art['nombre']) ?>">
                                                    <?php else: ?>
                                                        <i class="bi bi-image text-secondary opacity-25" style="font-size: 3rem;"></i>
                                                    <?php endif; ?>
                                                    
                                                    <!-- Badge Stock -->
                                                    <div class="position-absolute top-0 end-0 p-2">
                                                        <?php if ($art['stock'] > 0): ?>
                                                            <span class="badge bg-light text-success border border-success">Stock: <?= $art['stock'] ?></span>
                                                        <?php else: ?>
                                                            <span class="badge bg-danger">Agotado</span>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>

                                                <!-- Detalles -->
                                                <div class="card-body text-center p-3 d-flex flex-column justify-content-between">
                                                    <div>
                                                        <small class="text-muted" style="font-size: 0.75rem;"><?= htmlspecialchars($art['categoria_nombre'] ?? 'Sin categoría') ?></small>
                                                        <h6 class="card-title mt-1 mb-2 text-dark articulo-nombre" style="font-size: 0.9rem;">
                                                            <?= htmlspecialchars($art['nombre']) ?>
                                                        </h6>
                                                    </div>
                                                    <div class="fw-bold text-primary fs-6">
                                                        Bs. <?= number_format((float) $art['precio_venta'], 2) ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                                
                                <!-- Empty State Búsqueda -->
                                <div id="noResults" class="text-center py-5" style="display: none;">
                                    <i class="bi bi-search text-muted mb-3" style="font-size: 2.5rem;"></i>
                                    <h6 class="text-muted">No se encontraron coincidencias</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- PANEL DERECHO: CARRITO -->
                <div class="col-lg-5">
                    <div class="pos-card sticky-top" style="top: 1.5rem; z-index: 1020;">
                        <div class="pos-card-header bg-light d-flex justify-content-between align-items-center">
                            <div><i class="bi bi-basket3 text-secondary me-2"></i> Detalle de Venta</div>
                            <span class="badge bg-primary rounded-pill" id="badgeItemCount">0</span>
                        </div>
                        
                        <div class="card-body p-0">
                            <!-- Empty State Carrito -->
                            <div id="emptyCart" class="text-center py-5">
                                <i class="bi bi-cart-x empty-cart-icon mb-3"></i>
                                <p class="text-muted mb-0">Selecciona productos para comenzar</p>
                            </div>

                            <!-- Lista de Ítems -->
                            <div id="cartItemsContainer" style="display: none;">
                                <div class="cart-scroll px-3" id="listaDetalles">
                                    <!-- Filas dinámicas -->
                                </div>
                            </div>
                        </div>

                        <!-- Totales -->
                        <div class="card-footer bg-white border-top p-4">
                            <div class="d-flex justify-content-between align-items-center mb-2 text-muted small">
                                <span>Subtotal Original:</span>
                                <span id="lblSubtotalGral">Bs. 0.00</span>
                            </div>
                            
                            <!-- Descuento Global -->
                            <div class="d-flex justify-content-between align-items-center mb-2 text-danger small">
                                <label for="inputDescuentoGlobal" class="mb-0">Descuento Global (Bs.):</label>
                                <input type="number" id="inputDescuentoGlobal" name="descuento_global" class="form-control form-control-sm text-end text-danger border-danger shadow-none" style="width: 100px;" value="0" min="0" step="0.01">
                            </div>
                            
                            <div class="d-flex justify-content-between align-items-center mb-3 text-danger small fw-bold">
                                <span>Total Descuentos:</span>
                                <span id="lblDescuento">- Bs. 0.00</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-end mb-4">
                                <h5 class="fw-bold mb-0 text-dark">TOTAL A PAGAR</h5>
                                <h3 class="fw-bolder mb-0 text-success" id="lblTotal">Bs. 0.00</h3>
                            </div>

                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-outline-danger shadow-sm flex-shrink-1" id="btnLimpiarCarrito" disabled title="Limpiar Carrito">
                                    <i class="bi bi-trash"></i>
                                </button>
                                <button type="submit" class="btn btn-primary shadow-sm flex-grow-1 fw-bold" id="btnFinalizarVenta" disabled>
                                    <i class="bi bi-check2-circle me-1"></i> Finalizar Venta
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const buscador = document.getElementById('buscadorArticulos');
    const articuloItems = document.querySelectorAll('.articulo-item');
    const noResults = document.getElementById('noResults');
    const countArticulos = document.getElementById('countArticulos');
    
    const listaDetalles = document.getElementById('listaDetalles');
    const emptyCart = document.getElementById('emptyCart');
    const cartItemsContainer = document.getElementById('cartItemsContainer');
    const badgeItemCount = document.getElementById('badgeItemCount');
    
    const lblSubtotalGral = document.getElementById('lblSubtotalGral');
    const lblDescuento = document.getElementById('lblDescuento');
    const lblTotal = document.getElementById('lblTotal');
    
    const btnFinalizarVenta = document.getElementById('btnFinalizarVenta');
    const btnLimpiarCarrito = document.getElementById('btnLimpiarCarrito');

    // 1. Buscador en tiempo real
    if (buscador) {
        buscador.addEventListener('input', function() {
            const query = this.value.toLowerCase().trim();
            let count = 0;
            
            articuloItems.forEach(item => {
                const text = item.textContent.toLowerCase();
                if (text.includes(query)) {
                    item.style.display = '';
                    count++;
                } else {
                    item.style.display = 'none';
                }
            });
            
            countArticulos.textContent = count;
            noResults.style.display = count === 0 ? 'block' : 'none';
        });
    }

    // 2. Click en Tarjeta de Producto
    document.querySelectorAll('.product-card:not(.disabled)').forEach(card => {
        card.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            const nombre = this.getAttribute('data-nombre');
            const precio = parseFloat(this.getAttribute('data-precio')).toFixed(2);
            const stockMax = parseInt(this.getAttribute('data-stock'));

            // Animar la tarjeta
            this.style.transform = 'scale(0.95)';
            setTimeout(() => this.style.transform = '', 150);

            // Verificar si ya está en el carrito
            const existingRow = document.querySelector(`.cart-item[data-cart-id="${id}"]`);
            if (existingRow) {
                const inputCantidad = existingRow.querySelector('.input-cantidad');
                if (parseInt(inputCantidad.value) < stockMax) {
                    inputCantidad.value = parseInt(inputCantidad.value) + 1;
                    inputCantidad.dispatchEvent(new Event('input')); // Disparar recálculo
                } else {
                    alert('Límite de stock alcanzado para este producto.');
                }
                return;
            }

            // Ocultar Empty State
            emptyCart.style.display = 'none';
            cartItemsContainer.style.display = 'block';
            btnFinalizarVenta.disabled = false;
            btnLimpiarCarrito.disabled = false;

            // Crear ítem de carrito
            const itemDiv = document.createElement('div');
            itemDiv.className = 'cart-item';
            itemDiv.setAttribute('data-cart-id', id);
            itemDiv.dataset.precioOriginal = precio;
            
            itemDiv.innerHTML = `
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div class="fw-bold text-dark lh-sm flex-grow-1 pe-2" style="font-size: 0.9rem;">
                        ${nombre}
                        <input type="hidden" name="articulos[]" value="${id}">
                    </div>
                    <button type="button" class="btn btn-sm btn-link text-danger p-0 btn-eliminar-item" title="Quitar">
                        <i class="bi bi-x-circle-fill fs-5"></i>
                    </button>
                </div>
                <div class="d-flex justify-content-between align-items-center gap-2">
                    <div class="input-group input-group-sm w-50">
                        <span class="input-group-text bg-light text-muted border-end-0">Cant.</span>
                        <input type="number" name="cantidades[]" class="form-control input-cantidad fw-bold text-center" value="1" min="1" max="${stockMax}" required>
                    </div>
                    <div class="input-group input-group-sm w-50">
                        <span class="input-group-text bg-light text-muted border-end-0">Bs.</span>
                        <input type="number" name="precios[]" class="form-control input-precio fw-bold text-end" value="${precio}" min="0" step="0.01" required>
                    </div>
                </div>
            `;

            listaDetalles.appendChild(itemDiv);

            // Eventos del ítem
            itemDiv.querySelector('.btn-eliminar-item').addEventListener('click', function() {
                itemDiv.style.opacity = '0';
                setTimeout(() => {
                    itemDiv.remove();
                    calcularTotal();
                    checkEmptyCart();
                }, 200);
            });

            itemDiv.querySelector('.input-cantidad').addEventListener('input', function() {
                if (parseInt(this.value) > stockMax) {
                    this.value = stockMax;
                }
                calcularTotal();
            });

            itemDiv.querySelector('.input-precio').addEventListener('input', function() {
                calcularTotal();
            });

            calcularTotal();
            checkEmptyCart();
        });
    });

    // 3. Limpiar Carrito
    btnLimpiarCarrito.addEventListener('click', function() {
        if(confirm('¿Estás seguro de limpiar todo el carrito?')) {
            listaDetalles.innerHTML = '';
            calcularTotal();
            checkEmptyCart();
        }
    });

    // Funciones Auxiliares
    function checkEmptyCart() {
        const count = document.querySelectorAll('.cart-item').length;
        badgeItemCount.textContent = count;
        
        if (count === 0) {
            emptyCart.style.display = 'block';
            cartItemsContainer.style.display = 'none';
            btnFinalizarVenta.disabled = true;
            btnLimpiarCarrito.disabled = true;
        } else {
            emptyCart.style.display = 'none';
            cartItemsContainer.style.display = 'block';
            btnFinalizarVenta.disabled = false;
            btnLimpiarCarrito.disabled = false;
        }
    }

    const inputDescuentoGlobal = document.getElementById('inputDescuentoGlobal');
    
    if (inputDescuentoGlobal) {
        inputDescuentoGlobal.addEventListener('input', calcularTotal);
    }

    function calcularTotal() {
        let subtotalOriginal = 0;
        let totalCarts = 0; // sum of (cant * precioVenta) before global discount
        let descuentoManual = 0; // difference between catalog and manual input

        document.querySelectorAll('.cart-item').forEach(item => {
            const cant = parseFloat(item.querySelector('.input-cantidad').value) || 0;
            const precioVenta = parseFloat(item.querySelector('.input-precio').value) || 0;
            const precioOriginal = parseFloat(item.dataset.precioOriginal) || 0;
            
            totalCarts += cant * precioVenta;
            subtotalOriginal += cant * precioOriginal;
            
            if (precioOriginal > precioVenta) {
                descuentoManual += (precioOriginal - precioVenta) * cant;
            }
        });
        
        let descuentoGlobal = parseFloat(inputDescuentoGlobal.value) || 0;
        
        // No permitir que el descuento global sea mayor al total
        if (descuentoGlobal > totalCarts) {
            descuentoGlobal = totalCarts;
            inputDescuentoGlobal.value = descuentoGlobal.toFixed(2);
        }
        
        let descuentoTotal = descuentoManual + descuentoGlobal;
        let totalFinal = totalCarts - descuentoGlobal;
        
        lblSubtotalGral.innerText = 'Bs. ' + subtotalOriginal.toFixed(2);
        lblDescuento.innerText = '- Bs. ' + descuentoTotal.toFixed(2);
        lblTotal.innerText = 'Bs. ' + totalFinal.toFixed(2);
    }
});
</script>
