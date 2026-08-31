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
        <div class="d-flex gap-2">
            <a href="index.php?action=venta-nueva" class="btn btn-primary btn-sm text-nowrap">
                <i class="bi bi-plus-circle me-1"></i> Nueva Venta
            </a>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0 table-datatable">
            <thead class="table-light">
                <tr>
                    <th>ID Venta</th>
                    <th>Fecha</th>
                    <th>Cliente</th>
                    <th class="text-center">Artículos vendidos</th>
                    <th class="text-end">Descuento</th>
                    <th class="text-end">Total</th>
                    <th class="text-end">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($ventas as $venta): ?>
                        <tr>
                            <td class="fw-semibold">#<?= str_pad($venta['id'], 5, '0', STR_PAD_LEFT) ?></td>
                            <td><?= date('d/m/Y H:i', strtotime($venta['fecha_venta'])) ?></td>
                            <td><?= htmlspecialchars($venta['cliente_nombre'] ?? 'Consumidor Final') ?></td>
                            <td class="text-center"><?= (int) $venta['total_articulos'] ?></td>
                            <td class="text-end text-danger">Bs. <?= number_format((float) $venta['descuento'], 2) ?></td>
                            <td class="text-end fw-bold text-success">Bs. <?= number_format((float) $venta['total'], 2) ?></td>
                            <td class="text-end">
                                <a href="index.php?action=venta-ver&id=<?= $venta['id'] ?>"
                                   class="btn btn-sm btn-outline-secondary" title="Ver Detalle">
                                    <i class="bi bi-eye"></i> Detalle
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
