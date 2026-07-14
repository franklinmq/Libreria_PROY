<div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="card shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="bi bi-box-seam me-2"></i>Detalle del producto</h5>
                <a href="index.php?action=productos" class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> Volver
                </a>
            </div>
            <div class="card-body">
                <dl class="row mb-0">
                    <dt class="col-sm-4">Nombre</dt>
                    <dd class="col-sm-8"><?= htmlspecialchars($producto['nombre']) ?></dd>

                    <dt class="col-sm-4">Código de Barras</dt>
                    <dd class="col-sm-8"><?= htmlspecialchars($producto['codigo_barras']) ?></dd>

                    <dt class="col-sm-4">Marca</dt>
                    <dd class="col-sm-8"><?= htmlspecialchars($producto['marca'] ?: '—') ?></dd>

                    <dt class="col-sm-4">Descripción</dt>
                    <dd class="col-sm-8"><?= nl2br(htmlspecialchars($producto['descripcion'] ?: '—')) ?></dd>

                    <dt class="col-sm-4">Categoría</dt>
                    <dd class="col-sm-8"><?= htmlspecialchars($producto['categoria_nombre'] ?? '—') ?></dd>

                    <dt class="col-sm-4">Precio Compra</dt>
                    <dd class="col-sm-8">$<?= number_format((float) $producto['precio_compra'], 2) ?></dd>
                    
                    <dt class="col-sm-4">Precio Venta</dt>
                    <dd class="col-sm-8">$<?= number_format((float) $producto['precio_venta'], 2) ?></dd>

                    <dt class="col-sm-4">Stock disponible</dt>
                    <dd class="col-sm-8">
                        <span class="badge <?= $producto['stock'] <= 10 ? 'text-bg-warning' : 'text-bg-success' ?>">
                            <?= (int) $producto['stock'] ?> unidades
                        </span>
                    </dd>
                </dl>
            </div>
            <div class="card-footer bg-white d-flex justify-content-end gap-2">
                <a href="index.php?action=producto-editar&id=<?= $producto['id'] ?>" class="btn btn-outline-primary">
                    <i class="bi bi-pencil me-1"></i> Editar
                </a>
                <form action="index.php?action=producto-eliminar&id=<?= $producto['id'] ?>" method="post"
                      onsubmit="return confirm('¿Eliminar este producto del inventario?');">
                    <button type="submit" class="btn btn-outline-danger">
                        <i class="bi bi-trash me-1"></i> Eliminar
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
