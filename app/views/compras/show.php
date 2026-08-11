<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="bi bi-receipt me-2"></i>Detalle de Compra #<?= str_pad($compra['id'], 5, '0', STR_PAD_LEFT) ?></h5>
                <a href="index.php?action=compras" class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> Volver a Compras
                </a>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6 class="text-muted fw-bold mb-3">INFORMACIÓN DE LA COMPRA</h6>
                        <dl class="row mb-0">
                            <dt class="col-sm-4">Fecha:</dt>
                            <dd class="col-sm-8"><?= date('d/m/Y H:i', strtotime($compra['fecha_compra'])) ?></dd>
                            
                            <dt class="col-sm-4">Total Pagado:</dt>
                            <dd class="col-sm-8 fw-bold text-success">Bs. <?= number_format((float) $compra['total'], 2) ?></dd>
                        </dl>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted fw-bold mb-3">PROVEEDOR</h6>
                        <dl class="row mb-0">
                            <?php if ($compra['proveedor_nombre']): ?>
                                <dt class="col-sm-4">Nombre:</dt>
                                <dd class="col-sm-8"><?= htmlspecialchars($compra['proveedor_nombre']) ?></dd>
                                
                                <dt class="col-sm-4">Contacto:</dt>
                                <dd class="col-sm-8"><?= htmlspecialchars($compra['contacto'] ?: '—') ?></dd>
                                
                                <dt class="col-sm-4">Teléfono:</dt>
                                <dd class="col-sm-8"><?= htmlspecialchars($compra['telefono'] ?: '—') ?></dd>
                            <?php else: ?>
                                <dd class="col-12 text-muted">No se asignó ningún proveedor a esta compra.</dd>
                            <?php endif; ?>
                        </dl>
                    </div>
                </div>

                <h6 class="text-muted fw-bold mb-3">ARTÍCULOS COMPRADOS</h6>
                <div class="table-responsive">
                    <table class="table table-bordered align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Artículo</th>
                                <th class="text-center">Cantidad</th>
                                <th class="text-end">Costo Unitario</th>
                                <th class="text-end">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($compra['detalles'])): ?>
                                <?php foreach ($compra['detalles'] as $detalle): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($detalle['articulo_nombre'] ?? 'Artículo Eliminado') ?></td>
                                        <td class="text-center"><?= (int) $detalle['cantidad'] ?></td>
                                        <td class="text-end">Bs. <?= number_format((float) $detalle['precio_unitario'], 2) ?></td>
                                        <td class="text-end fw-semibold">Bs. <?= number_format((float) $detalle['subtotal'], 2) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4" class="text-center text-muted">No se encontraron detalles para esta compra.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                        <tfoot class="table-light">
                            <tr>
                                <th colspan="3" class="text-end">TOTAL COMPRA:</th>
                                <th class="text-end text-success fs-5">Bs. <?= number_format((float) $compra['total'], 2) ?></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            <div class="card-footer bg-white text-muted text-center small py-3">
                Esta compra ya ha actualizado el stock de los artículos listados.
            </div>
        </div>
    </div>
</div>
