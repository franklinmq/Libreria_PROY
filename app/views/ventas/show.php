<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="bi bi-receipt me-2"></i>Comprobante de Venta #<?= str_pad($venta['id'], 5, '0', STR_PAD_LEFT) ?></h5>
                <a href="index.php?action=ventas" class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> Volver a Ventas
                </a>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6 class="text-muted fw-bold mb-3">INFORMACIÓN DE LA VENTA</h6>
                        <dl class="row mb-0">
                            <dt class="col-sm-4">Fecha:</dt>
                            <dd class="col-sm-8"><?= date('d/m/Y H:i', strtotime($venta['fecha_venta'])) ?></dd>
                            
                            <dt class="col-sm-4">Total Cobrado:</dt>
                            <dd class="col-sm-8 fw-bold text-success">Bs. <?= number_format((float) $venta['total'], 2) ?></dd>
                        </dl>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted fw-bold mb-3">CLIENTE</h6>
                        <dl class="row mb-0">
                            <dt class="col-sm-4">Nombre:</dt>
                            <dd class="col-sm-8 fw-semibold"><?= htmlspecialchars($venta['cliente_nombre'] ?? 'Consumidor Final') ?></dd>
                        </dl>
                    </div>
                </div>

                <h6 class="text-muted fw-bold mb-3">ARTÍCULOS VENDIDOS</h6>
                <div class="table-responsive">
                    <table class="table table-bordered align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Artículo</th>
                                <th class="text-center">Cantidad</th>
                                <th class="text-end">Precio Unitario</th>
                                <th class="text-end">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $subtotal_bruto = 0;
                            if (!empty($venta['detalles'])): 
                            ?>
                                <?php foreach ($venta['detalles'] as $detalle): 
                                    $subtotal_bruto += $detalle['subtotal'];
                                ?>
                                    <tr>
                                        <td><?= htmlspecialchars($detalle['articulo_nombre'] ?? 'Artículo Eliminado') ?></td>
                                        <td class="text-center"><?= (int) $detalle['cantidad'] ?></td>
                                        <td class="text-end">Bs. <?= number_format((float) $detalle['precio_unitario'], 2) ?></td>
                                        <td class="text-end fw-semibold">Bs. <?= number_format((float) $detalle['subtotal'], 2) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4" class="text-center text-muted">No se encontraron detalles para esta venta.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                        <tfoot class="table-light">
                            <tr>
                                <th colspan="3" class="text-end text-muted fw-normal">Subtotal Sin Descuento:</th>
                                <th class="text-end text-muted fw-normal">Bs. <?= number_format((float) $subtotal_bruto, 2) ?></th>
                            </tr>
                            <tr>
                                <th colspan="3" class="text-end text-danger fw-normal">Descuento:</th>
                                <th class="text-end text-danger fw-normal">- Bs. <?= number_format((float) $venta['descuento'], 2) ?></th>
                            </tr>
                            <tr>
                                <th colspan="3" class="text-end">TOTAL VENTA:</th>
                                <th class="text-end text-success fs-5">Bs. <?= number_format((float) $venta['total'], 2) ?></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            <div class="card-footer bg-white text-muted text-center small py-3">
                Esta venta ya descontó el stock de los artículos listados.
            </div>
        </div>
    </div>
</div>
