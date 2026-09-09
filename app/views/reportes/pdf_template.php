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

        .signature-section {
            margin-top: 50px;
            display: flex;
            justify-content: space-around;
            text-align: center;
        }

        .signature-line {
            width: 200px;
            border-top: 1px solid #94a3b8;
            padding-top: 5px;
            font-size: 11px;
            color: #64748b;
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
    <div class="report-header d-flex justify-content-between align-items-start">
        <div>
            <h4 class="fw-bold mb-0 text-primary" style="color: #123B78 !important;">LIBRERÍA Y PAPELERÍA</h4>
            <div class="text-muted small">Sistema de Control de Inventario, Compras y Ventas</div>
            <div class="text-muted small mt-1">
                <strong>Reporte:</strong> INFORME OFICIAL DE <?= strtoupper($tipo) ?>
            </div>
        </div>
        <div class="text-end small">
            <div><strong>Fecha Emisión:</strong> <?= date('d/m/Y H:i') ?></div>
            <div><strong>Generado por:</strong> <?= htmlspecialchars($_SESSION['user_name'] ?? 'Administrador') ?></div>
            <div>
                <strong>Periodo:</strong> 
                <?= !empty($filtros['fecha_desde']) ? date('d/m/Y', strtotime($filtros['fecha_desde'])) : 'Inicio' ?> 
                al 
                <?= !empty($filtros['fecha_hasta']) ? date('d/m/Y', strtotime($filtros['fecha_hasta'])) : date('d/m/Y') ?>
            </div>
        </div>
    </div>

    <!-- ============================================== -->
    <!-- CONTENIDO ESPECÍFICO SEGÚN TIPO DE REPORTE -->
    <!-- ============================================== -->

    <?php if ($tipo === 'ventas'): ?>
        <!-- Resumen de Métricas -->
        <div class="row g-2 mb-3">
            <div class="col-3">
                <div class="kpi-box">
                    <div class="kpi-title">Total Ventas</div>
                    <div class="kpi-val"><?= (int)($resumen['total_operaciones'] ?? 0) ?></div>
                </div>
            </div>
            <div class="col-3">
                <div class="kpi-box">
                    <div class="kpi-title">Total Recaudado</div>
                    <div class="kpi-val text-success">Bs. <?= number_format((float)($resumen['total_ingresos'] ?? 0), 2) ?></div>
                </div>
            </div>
            <div class="col-3">
                <div class="kpi-box">
                    <div class="kpi-title">Unidades Vendidas</div>
                    <div class="kpi-val"><?= (int)($resumen['total_unidades'] ?? 0) ?></div>
                </div>
            </div>
            <div class="col-3">
                <div class="kpi-box">
                    <div class="kpi-title">Ticket Promedio</div>
                    <div class="kpi-val text-primary">Bs. <?= number_format((float)($resumen['ticket_promedio'] ?? 0), 2) ?></div>
                </div>
            </div>
        </div>

        <!-- Tabla Detallada -->
        <table class="table-custom">
            <thead>
                <tr>
                    <th style="width: 80px;" class="text-center">N° Venta</th>
                    <th style="width: 130px;">Fecha y Hora</th>
                    <th>Cliente</th>
                    <th style="width: 100px;" class="text-center">Artículos</th>
                    <th style="width: 120px;" class="text-end">Descuento</th>
                    <th style="width: 120px;" class="text-end">Total (Bs.)</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $sumTotal = 0; $sumDescuento = 0; $sumArts = 0;
                foreach ($datos as $v): 
                    $sumTotal += (float)$v['total'];
                    $sumDescuento += (float)$v['descuento'];
                    $sumArts += (int)$v['total_articulos'];
                ?>
                    <tr>
                        <td class="text-center fw-bold">#<?= str_pad($v['id'], 5, '0', STR_PAD_LEFT) ?></td>
                        <td><?= date('d/m/Y H:i', strtotime($v['fecha_venta'])) ?></td>
                        <td><?= htmlspecialchars($v['cliente_nombre'] ?? 'Consumidor Final') ?></td>
                        <td class="text-center"><?= (int)$v['total_articulos'] ?></td>
                        <td class="text-end text-danger"><?= (float)$v['descuento'] > 0 ? 'Bs. ' . number_format((float)$v['descuento'], 2) : '—' ?></td>
                        <td class="text-end fw-bold">Bs. <?= number_format((float)$v['total'], 2) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3" class="text-end">TOTALES GENERALES:</td>
                    <td class="text-center"><?= $sumArts ?></td>
                    <td class="text-end text-danger">Bs. <?= number_format($sumDescuento, 2) ?></td>
                    <td class="text-end text-success fw-bold">Bs. <?= number_format($sumTotal, 2) ?></td>
                </tr>
            </tfoot>
        </table>

    <?php elseif ($tipo === 'compras'): ?>
        <!-- Resumen de Métricas -->
        <div class="row g-2 mb-3">
            <div class="col-4">
                <div class="kpi-box">
                    <div class="kpi-title">Compras Realizadas</div>
                    <div class="kpi-val"><?= (int)($resumen['total_operaciones'] ?? 0) ?></div>
                </div>
            </div>
            <div class="col-4">
                <div class="kpi-box">
                    <div class="kpi-title">Total Invertido</div>
                    <div class="kpi-val text-primary">Bs. <?= number_format((float)($resumen['total_egresos'] ?? 0), 2) ?></div>
                </div>
            </div>
            <div class="col-4">
                <div class="kpi-box">
                    <div class="kpi-title">Artículos Adquiridos</div>
                    <div class="kpi-val"><?= (int)($resumen['total_unidades'] ?? 0) ?></div>
                </div>
            </div>
        </div>

        <table class="table-custom">
            <thead>
                <tr>
                    <th style="width: 80px;" class="text-center">N° Compra</th>
                    <th style="width: 140px;">Fecha y Hora</th>
                    <th>Proveedor</th>
                    <th style="width: 120px;" class="text-center">Artículos</th>
                    <th style="width: 140px;" class="text-end">Total Invertido (Bs.)</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $sumTotal = 0; $sumArts = 0;
                foreach ($datos as $c): 
                    $sumTotal += (float)$c['total'];
                    $sumArts += (int)$c['total_articulos'];
                ?>
                    <tr>
                        <td class="text-center fw-bold">#<?= str_pad($c['id'], 5, '0', STR_PAD_LEFT) ?></td>
                        <td><?= date('d/m/Y H:i', strtotime($c['fecha_compra'])) ?></td>
                        <td><?= htmlspecialchars($c['proveedor_nombre'] ?? 'Sin Proveedor') ?></td>
                        <td class="text-center"><?= (int)$c['total_articulos'] ?></td>
                        <td class="text-end fw-bold">Bs. <?= number_format((float)$c['total'], 2) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3" class="text-end">TOTALES GENERALES:</td>
                    <td class="text-center"><?= $sumArts ?></td>
                    <td class="text-end text-primary fw-bold">Bs. <?= number_format($sumTotal, 2) ?></td>
                </tr>
            </tfoot>
        </table>

    <?php elseif ($tipo === 'inventario'): ?>
        <!-- Resumen de Métricas -->
        <div class="row g-2 mb-3">
            <div class="col-3">
                <div class="kpi-box">
                    <div class="kpi-title">Artículos / Stock Total</div>
                    <div class="kpi-val"><?= (int)$resumen['total_articulos'] ?> <small class="fs-6">(<?= (int)$resumen['total_stock'] ?>)</small></div>
                </div>
            </div>
            <div class="col-3">
                <div class="kpi-box">
                    <div class="kpi-title">Valor Costo Total</div>
                    <div class="kpi-val text-secondary">Bs. <?= number_format((float)$resumen['valor_costo_total'], 2) ?></div>
                </div>
            </div>
            <div class="col-3">
                <div class="kpi-box">
                    <div class="kpi-title">Valor Venta Estimado</div>
                    <div class="kpi-val text-primary">Bs. <?= number_format((float)$resumen['valor_venta_total'], 2) ?></div>
                </div>
            </div>
            <div class="col-3">
                <div class="kpi-box">
                    <div class="kpi-title">Ganancia Proyectada</div>
                    <div class="kpi-val text-success">Bs. <?= number_format((float)$resumen['ganancia_potencial'], 2) ?></div>
                </div>
            </div>
        </div>

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

    <!-- Firmas de Autorización -->
    <div class="signature-section">
        <div class="signature-line">
            Elaborado por<br>
            <strong><?= htmlspecialchars($_SESSION['user_name'] ?? 'Administrador') ?></strong>
        </div>
        <div class="signature-line">
            Revisado por<br>
            <strong>Gerencia de Operaciones</strong>
        </div>
        <div class="signature-line">
            Aprobado por<br>
            <strong>Dirección General</strong>
        </div>
    </div>
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
