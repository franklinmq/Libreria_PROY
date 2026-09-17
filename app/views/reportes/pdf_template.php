<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de <?= ucfirst($tipo) ?> - Sistema de Inventario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <style>
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background-color: #f8fafc;
            color: #1e293b;
            font-size: 13px;
        }

        .report-sheet {
            max-width: 1000px;
            margin: 20px auto;
            background: #ffffff;
            padding: 35px 40px;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        }

        .report-header {
            border-bottom: 2px solid #1164CF;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }

        .table-custom {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            font-size: 12px;
        }

        .table-custom th {
            background-color: #123B78 !important;
            color: #ffffff !important;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 11px;
            letter-spacing: 0.5px;
            padding: 8px 10px;
            border: 1px solid #123B78;
        }

        .table-custom td {
            padding: 7px 10px;
            border: 1px solid #e2e8f0;
            vertical-align: middle;
        }

        .table-custom tbody tr:nth-child(even) {
            background-color: #f8fafc;
        }

        .table-custom tfoot td {
            background-color: #e2e8f0;
            font-weight: bold;
            border: 1px solid #cbd5e1;
            padding: 8px 10px;
        }

        .kpi-box {
            background: #f1f5f9;
            border-radius: 6px;
            padding: 12px;
            border-left: 4px solid #1164CF;
            margin-bottom: 15px;
        }

        .kpi-title {
            font-size: 11px;
            color: #64748b;
            text-transform: uppercase;
            font-weight: 600;
        }

        .kpi-val {
            font-size: 18px;
            font-weight: bold;
            color: #0f172a;
        }


        @media print {
            body {
                background: #ffffff !important;
                font-size: 11pt;
            }
            .report-sheet {
                max-width: 100% !important;
                margin: 0 !important;
                padding: 0 !important;
                box-shadow: none !important;
                border-radius: 0 !important;
            }
            .no-print {
                display: none !important;
            }
            .table-custom th {
                background-color: #123B78 !important;
                color: #ffffff !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            .kpi-box {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }
    </style>
</head>
<body>

<!-- Barra de Controles Superior (No imprimible) -->
<div class="no-print bg-dark py-2 px-3 text-white sticky-top shadow-sm">
    <div class="container-fluid d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center gap-2">
            <i class="bi bi-file-earmark-pdf fs-5 text-danger"></i>
            <span class="fw-semibold">Vista Previa para PDF / Impresión</span>
        </div>
        <div class="d-flex gap-2">
            <button onclick="window.print()" class="btn btn-sm btn-primary d-flex align-items-center gap-1">
                <i class="bi bi-printer"></i> Imprimir / Guardar como PDF
            </button>
            <button id="btnDescargarPdf" class="btn btn-sm btn-danger d-flex align-items-center gap-1">
                <i class="bi bi-download"></i> Descargar PDF Directo
            </button>
            <a href="javascript:window.close()" class="btn btn-sm btn-outline-light">
                <i class="bi bi-x-lg"></i> Cerrar
            </a>
        </div>
    </div>
</div>

<div class="report-sheet" id="reportContent">
    <!-- Encabezado Corporativo -->
    <div class="d-flex justify-content-between align-items-center mb-4" style="border-bottom: 2px solid #123B78; padding-bottom: 15px;">
        <!-- Izquierda: Logo -->
        <div style="width: 30%; text-align: left;">
            <!-- Ajusta la ruta del logo si es diferente -->
            <img src="assets/img/logo.png" alt="Logo" style="max-height: 80px;">
        </div>
        
        <!-- Centro: Nombre de la Empresa -->
        <div style="width: 40%; text-align: center;">
            <h2 class="fw-bold mb-0" style="color: #123B78 !important; font-size: 26px; letter-spacing: 1px;">LIBRERÍA F & N</h2>
        </div>
        
        <!-- Derecha: Datos de Emisión -->
        <div style="width: 30%; text-align: right; font-size: 13px; color: #333;">
            <div style="margin-bottom: 8px;">
                Fecha de Emision: <span style="display: inline-block; min-width: 80px; text-align: center;"><?= date('d/m/Y') ?></span>
            </div>
            <div>
                Periodo: <span style="display: inline-block; min-width: 120px; text-align: center;">
                    <?= !empty($filtros['fecha_desde']) ? date('d/m/Y', strtotime($filtros['fecha_desde'])) : 'Inicio' ?> - <?= !empty($filtros['fecha_hasta']) ? date('d/m/Y', strtotime($filtros['fecha_hasta'])) : date('d/m/Y') ?>
                </span>
            </div>
        </div>
    </div>

    <!-- Título del Reporte -->
    <div class="text-center" style="margin-bottom: 30px;">
        <h4 style="text-transform: uppercase; color: #333 !important; font-weight: normal; font-size: 16px; letter-spacing: 1px;">
            REPORTE DE <?= strtoupper($tipo) ?>
        </h4>
    </div>

    <!-- ============================================== -->
    <!-- CONTENIDO ESPECÍFICO SEGÚN TIPO DE REPORTE -->
    <!-- ============================================== -->

    <?php if ($tipo === 'ventas'): ?>
        <?php 
        $sumTotalGeneral = 0; $sumArtsGeneral = 0; $sumDescuentoGeneral = 0;
        if (empty($datos)): ?>
            <table class="table-custom">
                <tbody><tr><td class="text-center py-4">No hay ventas en el periodo seleccionado.</td></tr></tbody>
            </table>
        <?php else: ?>
            <?php foreach ($datos as $dia => $items): ?>
                <div class="text-center mt-4 mb-3">
                    <span style="font-size:15px; color:#333;">Fecha: <?= date('d/m/Y', strtotime($dia)) ?></span>
                </div>
                <table class="table-custom">
                    <thead>
                        <tr>
                            <th style="width: 80px;" class="text-center">hora</th>
                            <th>Cliente</th>
                            <th>Articulo</th>
                            <th style="width: 100px;" class="text-end">P/Unit</th>
                            <th style="width: 80px;" class="text-center">Cantidad</th>
                            <th style="width: 100px;" class="text-end">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $totalDia = 0; $artsDia = 0;
                        
                        // Primer paso: Calcular subtotales brutos por venta para distribuir el descuento
                        $totalesPorVenta = [];
                        foreach ($items as $v) {
                            $vid = $v['venta_id'];
                            if (!isset($totalesPorVenta[$vid])) {
                                $totalesPorVenta[$vid] = ['subtotal_bruto' => 0, 'descuento' => (float)$v['descuento']];
                            }
                            $totalesPorVenta[$vid]['subtotal_bruto'] += (float)$v['subtotal'];
                        }

                        // Segundo paso: Mostrar ítems con precios ajustados
                        foreach ($items as $v): 
                            $vid = $v['venta_id'];
                            $bruto = $totalesPorVenta[$vid]['subtotal_bruto'];
                            $descuento = $totalesPorVenta[$vid]['descuento'];
                            
                            // Si la venta tiene un descuento, calculamos qué porcentaje del total representa
                            // para rebajar ese mismo porcentaje al precio de cada producto
                            $ratio = 1;
                            if ($bruto > 0 && $descuento > 0) {
                                $ratio = ($bruto - $descuento) / $bruto;
                            }
                            
                            $adjusted_subtotal = (float)$v['subtotal'] * $ratio;
                            $adjusted_p_unit = $adjusted_subtotal / (int)$v['cantidad'];
                            
                            $totalDia += $adjusted_subtotal;
                            $artsDia += (int)$v['cantidad'];
                            $sumTotalGeneral += $adjusted_subtotal;
                            $sumArtsGeneral += (int)$v['cantidad'];
                        ?>
                            <tr>
                                <td class="text-center"><?= date('H:i', strtotime($v['fecha_hora'])) ?></td>
                                <td><?= htmlspecialchars($v['cliente_nombre'] ?? 'Consumidor Final') ?></td>
                                <td><?= htmlspecialchars($v['articulo_nombre']) ?></td>
                                <td class="text-end">Bs. <?= number_format($adjusted_p_unit, 2) ?></td>
                                <td class="text-center"><?= (int)$v['cantidad'] ?></td>
                                <td class="text-end fw-bold">Bs. <?= number_format($adjusted_subtotal, 2) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="4" class="text-end">TOTAL DÍA:</td>
                            <td class="text-center"><?= $artsDia ?></td>
                            <td class="text-end text-primary fw-bold">Bs. <?= number_format($totalDia, 2) ?></td>
                        </tr>
                    </tfoot>
                </table>
            <?php endforeach; ?>
            
            <div style="margin-top: 20px; padding: 10px; background: #e2e8f0; border-radius: 4px; text-align: right; border: 1px solid #cbd5e1;">
                <strong style="color: #475569;">TOTAL GENERAL DEL PERIODO:</strong> &nbsp;&nbsp;&nbsp; 
                <?php if ($sumDescuentoGeneral > 0): ?>
                <span style="font-size: 13px; color: #dc2626; margin-right: 15px;">(Descuentos: Bs. <?= number_format($sumDescuentoGeneral, 2) ?>)</span>
                <?php endif; ?>
                <span style="font-size: 14px; font-weight: bold; color: #123B78;">Bs. <?= number_format($sumTotalGeneral, 2) ?> (<?= $sumArtsGeneral ?> artículos)</span>
            </div>
        <?php endif; ?>

    <?php elseif ($tipo === 'compras'): ?>
        <?php 
        $sumTotalGeneral = 0; $sumArtsGeneral = 0;
        if (empty($datos)): ?>
            <table class="table-custom">
                <tbody><tr><td class="text-center py-4">No hay compras en el periodo seleccionado.</td></tr></tbody>
            </table>
        <?php else: ?>
            <?php foreach ($datos as $dia => $items): ?>
                <div class="text-center mt-4 mb-3">
                    <span style="font-size:15px; color:#333;">Fecha: <?= date('d/m/Y', strtotime($dia)) ?></span>
                </div>
                <table class="table-custom">
                    <thead>
                        <tr>
                            <th style="width: 80px;" class="text-center">hora</th>
                            <th>Proveedor</th>
                            <th>Articulo</th>
                            <th style="width: 100px;" class="text-end">P/Unit</th>
                            <th style="width: 80px;" class="text-center">Cantidad</th>
                            <th style="width: 100px;" class="text-end">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $totalDia = 0; $artsDia = 0;
                        foreach ($items as $c): 
                            $totalDia += (float)$c['subtotal'];
                            $artsDia += (int)$c['cantidad'];
                            $sumTotalGeneral += (float)$c['subtotal'];
                            $sumArtsGeneral += (int)$c['cantidad'];
                        ?>
                            <tr>
                                <td class="text-center"><?= date('H:i', strtotime($c['fecha_hora'])) ?></td>
                                <td><?= htmlspecialchars($c['proveedor_nombre'] ?? 'Sin Proveedor') ?></td>
                                <td><?= htmlspecialchars($c['articulo_nombre']) ?></td>
                                <td class="text-end">Bs. <?= number_format((float)$c['precio_unitario'], 2) ?></td>
                                <td class="text-center"><?= (int)$c['cantidad'] ?></td>
                                <td class="text-end fw-bold">Bs. <?= number_format((float)$c['subtotal'], 2) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="4" class="text-end">TOTAL DÍA:</td>
                            <td class="text-center"><?= $artsDia ?></td>
                            <td class="text-end text-primary fw-bold">Bs. <?= number_format($totalDia, 2) ?></td>
                        </tr>
                    </tfoot>
                </table>
            <?php endforeach; ?>
            
            <div style="margin-top: 20px; padding: 10px; background: #e2e8f0; border-radius: 4px; text-align: right; border: 1px solid #cbd5e1;">
                <strong style="color: #475569;">TOTAL GENERAL DEL PERIODO:</strong> &nbsp;&nbsp;&nbsp; 
                <span style="font-size: 14px; font-weight: bold; color: #123B78;">Bs. <?= number_format($sumTotalGeneral, 2) ?> (<?= $sumArtsGeneral ?> artículos)</span>
            </div>
        <?php endif; ?>

    <?php elseif ($tipo === 'inventario'): ?>
        <table class="table-custom">
            <thead>
                <tr>
                    <th>Artículo</th>
                    <th>Categoría</th>
                    <th>Marca</th>
                    <th style="width: 60px;" class="text-center">Stock</th>
                    <th style="width: 80px;" class="text-end">P. Compra</th>
                    <th style="width: 80px;" class="text-end">P. Venta</th>
                    <th style="width: 100px;" class="text-end">V. Costo</th>
                    <th style="width: 100px;" class="text-end">V. Venta</th>
                    <th style="width: 100px;" class="text-end">Margen</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($datos as $art): ?>
                    <tr>
                        <td class="fw-semibold"><?= htmlspecialchars($art['nombre']) ?></td>
                        <td><?= htmlspecialchars($art['categoria_nombre'] ?? '—') ?></td>
                        <td><?= htmlspecialchars($art['marca_nombre'] ?? '—') ?></td>
                        <td class="text-center fw-bold"><?= (int)$art['stock'] ?></td>
                        <td class="text-end">Bs. <?= number_format((float)$art['precio_compra'], 2) ?></td>
                        <td class="text-end">Bs. <?= number_format((float)$art['precio_venta'], 2) ?></td>
                        <td class="text-end">Bs. <?= number_format((float)$art['valor_total_costo'], 2) ?></td>
                        <td class="text-end">Bs. <?= number_format((float)$art['valor_total_venta'], 2) ?></td>
                        <td class="text-end text-success fw-bold">Bs. <?= number_format((float)$art['ganancia_potencial'], 2) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3" class="text-end">TOTALES:</td>
                    <td class="text-center"><?= (int)$resumen['total_stock'] ?></td>
                    <td colspan="2"></td>
                    <td class="text-end">Bs. <?= number_format((float)$resumen['valor_costo_total'], 2) ?></td>
                    <td class="text-end">Bs. <?= number_format((float)$resumen['valor_venta_total'], 2) ?></td>
                    <td class="text-end text-success">Bs. <?= number_format((float)$resumen['ganancia_potencial'], 2) ?></td>
                </tr>
            </tfoot>
        </table>

    <?php elseif ($tipo === 'top'): ?>
        <table class="table-custom">
            <thead>
                <tr>
                    <th style="width: 60px;" class="text-center">#</th>
                    <th>Artículo</th>
                    <th>Categoría</th>
                    <th>Marca</th>
                    <th style="width: 90px;" class="text-end">Precio Unit.</th>
                    <th style="width: 90px;" class="text-center">Unid. Vendidas</th>
                    <th style="width: 120px;" class="text-end">Total Recaudado</th>
                    <th style="width: 120px;" class="text-end">Ganancia Estimada</th>
                </tr>
            </thead>
            <tbody>
                <?php $pos = 1; foreach ($datos as $item): ?>
                    <tr>
                        <td class="text-center fw-bold"><?= $pos++ ?></td>
                        <td class="fw-semibold"><?= htmlspecialchars($item['nombre']) ?></td>
                        <td><?= htmlspecialchars($item['categoria_nombre'] ?? '—') ?></td>
                        <td><?= htmlspecialchars($item['marca_nombre'] ?? '—') ?></td>
                        <td class="text-end">Bs. <?= number_format((float)$item['precio_venta'], 2) ?></td>
                        <td class="text-center fw-bold"><?= (int)$item['total_unidades_vendidas'] ?></td>
                        <td class="text-end fw-bold text-success">Bs. <?= number_format((float)$item['total_recaudado'], 2) ?></td>
                        <td class="text-end text-info fw-bold">Bs. <?= number_format((float)$item['ganancia_estimada'], 2) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

    <?php elseif ($tipo === 'balance'): ?>
        <div class="row g-3 my-3">
            <div class="col-4">
                <div class="kpi-box" style="border-left-color: #059669;">
                    <div class="kpi-title">Ingresos Totales (Ventas)</div>
                    <div class="kpi-val text-success">Bs. <?= number_format((float)$resumen['total_ingresos'], 2) ?></div>
                    <div class="small text-muted"><?= (int)$resumen['total_ventas'] ?> ventas registradas</div>
                </div>
            </div>
            <div class="col-4">
                <div class="kpi-box" style="border-left-color: #dc2626;">
                    <div class="kpi-title">Egresos Totales (Compras)</div>
                    <div class="kpi-val text-danger">Bs. <?= number_format((float)$resumen['total_egresos'], 2) ?></div>
                    <div class="small text-muted"><?= (int)$resumen['total_compras'] ?> compras realizadas</div>
                </div>
            </div>
            <div class="col-4">
                <div class="kpi-box" style="border-left-color: #1164CF;">
                    <div class="kpi-title">Utilidad Bruta Estimada</div>
                    <div class="kpi-val text-<?= $resumen['utilidad_bruta'] >= 0 ? 'primary' : 'danger' ?>">
                        Bs. <?= number_format((float)$resumen['utilidad_bruta'], 2) ?>
                    </div>
                    <div class="small text-muted">Balance neto del periodo</div>
                </div>
            </div>
        </div>
    <?php endif; ?>


</div>

<!-- Script para generación directa de PDF vía html2pdf -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
<script>
    document.getElementById('btnDescargarPdf').addEventListener('click', function() {
        const elemento = document.getElementById('reportContent');
        const opt = {
            margin:       [10, 10, 10, 10],
            filename:     'Reporte_<?= ucfirst($tipo) ?>_<?= date("Y-m-d") ?>.pdf',
            image:        { type: 'jpeg', quality: 0.98 },
            html2canvas:  { scale: 2, useCORS: true },
            jsPDF:        { unit: 'mm', format: 'a4', orientation: '<?= in_array($tipo, ['inventario', 'top']) ? 'landscape' : 'portrait' ?>' }
        };

        html2pdf().set(opt).from(elemento).save();
    });
</script>

</body>
</html>
