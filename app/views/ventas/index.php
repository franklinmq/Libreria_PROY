<?php $msg = $_GET['msg'] ?? ''; ?>

<?php if ($msg): ?>
    <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
        <i class="bi bi-check-circle me-1"></i>
        <?php
            $mensajes = [
                'creado' => 'Venta registrada y stock actualizado correctamente.',
            ];
            echo $mensajes[$msg] ?? 'Operación realizada.';
        ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="card shadow-sm">
    <div class="card-header bg-white d-flex flex-wrap justify-content-between align-items-center gap-2">
        <h5 class="mb-0"><i class="bi bi-cash-stack me-2"></i>Historial de Ventas</h5>
        
        <form method="GET" action="index.php" class="d-flex gap-2 align-items-center mb-0">
            <input type="hidden" name="action" value="ventas">
            <label for="fecha" class="small text-muted mb-0 fw-semibold">Fecha:</label>
            <input type="date" name="fecha" id="fecha" value="<?= $fecha === 'all' ? '' : htmlspecialchars($fecha) ?>" class="form-control form-control-sm" style="width: 140px;">
            <button type="submit" class="btn btn-secondary btn-sm"><i class="bi bi-filter"></i> Filtrar</button>
            <a href="index.php?action=ventas&fecha=all" class="btn btn-outline-secondary btn-sm" title="Ver todo el historial">Todas</a>
        </form>

        <div class="d-flex gap-2">
            <a href="index.php?action=venta-nueva" class="btn btn-primary btn-sm text-nowrap">
                <i class="bi bi-plus-circle me-1"></i> Nueva Venta
            </a>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0 table-datatable">
            <thead style="background-color: #E8F5E9;">
                <tr>
                    <th>Cliente</th>
                    <th>Producto</th>
                    <th class="text-center">Cant.</th>
                    <th class="text-end">P. Unit</th>
                    <th class="text-end">Total</th>
                    <th>Hora</th>
                    <th class="text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($ventas as $item): ?>
                        <tr>
                            <td class="text-muted"><?= htmlspecialchars($item['cliente_nombre'] ?? 'Sin Nombre') ?></td>
                            <td>
                                <span class="text-secondary small text-uppercase"><?= htmlspecialchars($item['categoria_nombre'] ?? 'SIN CATEGORÍA') ?></span> <span class="text-muted mx-1">|</span> 
                                <span class="fw-bold text-dark"><?= htmlspecialchars($item['articulo_nombre']) ?></span>
                            </td>
                            <td class="text-center"><?= (int) $item['cantidad'] ?></td>
                            <td class="text-end">Bs. <?= number_format((float) $item['precio_unitario'], 2) ?></td>
                            <td class="text-end fw-bold">Bs. <?= number_format((float) $item['subtotal'], 2) ?></td>
                            <td class="text-muted fw-semibold"><?= $fecha === 'all' ? date('d/m H:i', strtotime($item['fecha_venta'])) : date('H:i', strtotime($item['fecha_venta'])) ?></td>
                            <td class="text-center">
                                <a href="index.php?action=venta-ver&id=<?= $item['venta_id'] ?>" class="btn btn-sm py-0 px-2 shadow-sm" style="background-color: var(--brand-primary); color: white; border-color: var(--brand-primary);" title="Editar / Ver Venta">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <button type="button" class="btn btn-sm py-0 px-2 shadow-sm" style="background-color: var(--brand-dark); color: white; border-color: var(--brand-dark);" title="Eliminar Venta" onclick="if(confirm('¿Eliminar esta venta entera?')) window.location.href='index.php?action=venta-eliminar&id=<?= $item['venta_id'] ?>'">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
