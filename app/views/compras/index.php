<?php $msg = $_GET['msg'] ?? ''; ?>

<?php if ($msg): ?>
    <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
        <i class="bi bi-check-circle me-1"></i>
        <?php
            $mensajes = [
                'creado' => 'Compra registrada y stock actualizado correctamente.',
            ];
            echo $mensajes[$msg] ?? 'Operación realizada.';
        ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="card shadow-sm">
    <div class="card-header bg-white d-flex flex-wrap justify-content-between align-items-center gap-2">
        <h5 class="mb-0"><i class="bi bi-cart-check me-2"></i>Historial de Compras</h5>
        <div class="d-flex gap-2">
            <a href="index.php?action=compra-nueva" class="btn btn-primary btn-sm text-nowrap">
                <i class="bi bi-plus-circle me-1"></i> Nueva Compra
            </a>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0 table-datatable">
            <thead class="table-light">
                <tr>
                    <th>ID Compra</th>
                    <th>Fecha</th>
                    <th>Proveedor</th>
                    <th class="text-center">Artículos comprados</th>
                    <th class="text-end">Total</th>
                    <th class="text-end">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($compras as $compra): ?>
                        <tr>
                            <td class="fw-semibold">#<?= str_pad($compra['id'], 5, '0', STR_PAD_LEFT) ?></td>
                            <td><?= date('d/m/Y H:i', strtotime($compra['fecha_compra'])) ?></td>
                            <td><?= htmlspecialchars($compra['proveedor_nombre'] ?? 'Sin Proveedor') ?></td>
                            <td class="text-center"><?= (int) $compra['total_articulos'] ?></td>
                            <td class="text-end fw-bold text-success">Bs. <?= number_format((float) $compra['total'], 2) ?></td>
                            <td class="text-end">
                                <a href="index.php?action=compra-ver&id=<?= $compra['id'] ?>"
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
