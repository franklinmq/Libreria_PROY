<?php

require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/Reporte.php';
require_once __DIR__ . '/../models/Categoria.php';
require_once __DIR__ . '/../models/Marca.php';
require_once __DIR__ . '/../models/Proveedor.php';

class ReporteController extends Controller
{
    private Reporte $reporteModel;
    private Categoria $categoriaModel;
    private Marca $marcaModel;
    private Proveedor $proveedorModel;

    public function __construct()
    {
        $this->reporteModel = new Reporte();
        $this->categoriaModel = new Categoria();
        $this->marcaModel = new Marca();
        $this->proveedorModel = new Proveedor();
    }

    /**
     * Vista principal del módulo de reportes con pestañas interactivas
     */
    public function index(): void
    {
        $tab = $_GET['tab'] ?? 'ventas';
        $filtros = [
            'fecha_desde'  => $_GET['fecha_desde'] ?? '',
            'fecha_hasta'  => $_GET['fecha_hasta'] ?? '',
            'cliente'      => $_GET['cliente'] ?? '',
            'proveedor_id' => $_GET['proveedor_id'] ?? '',
            'categoria_id' => $_GET['categoria_id'] ?? '',
            'marca_id'     => $_GET['marca_id'] ?? '',
            'estado_stock' => $_GET['estado_stock'] ?? '',
            'limite'       => $_GET['limite'] ?? 10,
            'busqueda'     => $_GET['busqueda'] ?? ''
        ];

        // Listas auxiliares para los selectores de filtros
        $categorias = $this->categoriaModel->obtenerTodas();
        $marcas = $this->marcaModel->obtenerTodas();
        $proveedores = $this->proveedorModel->obtenerTodos();

        // Datos según la pestaña activa
        $datos = [];
        $resumen = [];

        switch ($tab) {
            case 'compras':
                $datos = $this->reporteModel->obtenerReporteCompras($filtros);
                $resumen = $this->reporteModel->obtenerResumenCompras($filtros);
                break;

            case 'inventario':
                $datos = $this->reporteModel->obtenerReporteInventario($filtros);
                $resumen = $this->reporteModel->obtenerResumenInventario($filtros);
                break;

            case 'top':
                $datos = $this->reporteModel->obtenerTopVendidos($filtros);
                $resumen = $this->reporteModel->obtenerResumenVentas($filtros);
                break;

            case 'balance':
                $resumen = $this->reporteModel->obtenerBalance($filtros);
                $datos = [
                    'ventas' => $this->reporteModel->obtenerReporteVentas($filtros),
                    'compras' => $this->reporteModel->obtenerReporteCompras($filtros)
                ];
                break;

            case 'ventas':
            default:
                $tab = 'ventas';
                $datos = $this->reporteModel->obtenerReporteVentas($filtros);
                $resumen = $this->reporteModel->obtenerResumenVentas($filtros);
                break;
        }

        $this->render('reportes/index', [
            'tab'         => $tab,
            'filtros'     => $filtros,
            'datos'       => $datos,
            'resumen'     => $resumen,
            'categorias'  => $categorias,
            'marcas'      => $marcas,
            'proveedores' => $proveedores
        ]);
    }

    /**
     * Exportación de reportes a formato Excel (.xls compatible con estilos y fórmulas)
     */
    public function exportarExcel(): void
    {
        $tipo = $_GET['tipo'] ?? 'ventas';
        $filtros = [
            'fecha_desde'  => $_GET['fecha_desde'] ?? '',
            'fecha_hasta'  => $_GET['fecha_hasta'] ?? '',
            'cliente'      => $_GET['cliente'] ?? '',
            'proveedor_id' => $_GET['proveedor_id'] ?? '',
            'categoria_id' => $_GET['categoria_id'] ?? '',
            'marca_id'     => $_GET['marca_id'] ?? '',
            'estado_stock' => $_GET['estado_stock'] ?? '',
            'limite'       => $_GET['limite'] ?? 50,
            'busqueda'     => $_GET['busqueda'] ?? ''
        ];

        $nombreArchivo = "Reporte_" . ucfirst($tipo) . "_" . date('Y-m-d_Hi') . ".xls";

        // Headers para descarga directa en Excel
        header('Content-Type: application/vnd.ms-excel; charset=UTF-8');
        header('Content-Disposition: attachment; filename="' . $nombreArchivo . '"');
        header('Pragma: no-cache');
        header('Expires: 0');

        // UTF-8 BOM para evitar problemas con acentos y caracteres especiales
        echo "\xEF\xBB\xBF";

        // Estilos CSS integrados para Excel
        $styleHeader = 'style="background-color: #1164CF; color: #FFFFFF; font-weight: bold; text-align: center; border: 1px solid #999; padding: 8px;"';
        $styleTitle = 'style="font-size: 16pt; font-weight: bold; color: #123B78; text-align: center;"';
        $styleSubTitle = 'style="font-size: 10pt; color: #555; text-align: center;"';
        $styleKpiTh = 'style="background-color: #F1F5F9; color: #334155; font-weight: bold; border: 1px solid #CBD5E1; padding: 6px;"';
        $styleKpiTd = 'style="border: 1px solid #CBD5E1; padding: 6px; text-align: right; font-weight: bold;"';
        $styleTd = 'style="border: 1px solid #E2E8F0; padding: 6px;"';
        $styleTdCenter = 'style="border: 1px solid #E2E8F0; padding: 6px; text-align: center;"';
        $styleTdRight = 'style="border: 1px solid #E2E8F0; padding: 6px; text-align: right;"';
        $styleTotal = 'style="background-color: #E2E8F0; font-weight: bold; border: 1px solid #94A3B8; padding: 8px;"';

        echo '<table border="0" cellpadding="0" cellspacing="0" width="100%">';
        echo '<tr><td colspan="6" ' . $styleTitle . '>SISTEMA DE INVENTARIO Y LIBRERÍA</td></tr>';
        echo '<tr><td colspan="6" ' . $styleSubTitle . '>REPORTE DE ' . strtoupper($tipo) . ' — Generado el: ' . date('d/m/Y H:i:s') . '</td></tr>';
        if (!empty($filtros['fecha_desde']) || !empty($filtros['fecha_hasta'])) {
            $rango = 'Periodo: ' . ($filtros['fecha_desde'] ?: 'Inicio') . ' al ' . ($filtros['fecha_hasta'] ?: 'Hoy');
            echo '<tr><td colspan="6" ' . $styleSubTitle . '>' . htmlspecialchars($rango) . '</td></tr>';
        }
        echo '<tr><td colspan="6">&nbsp;</td></tr>';
        echo '</table>';

        switch ($tipo) {
            case 'ventas':
                $ventas = $this->reporteModel->obtenerReporteVentas($filtros);
                $resumen = $this->reporteModel->obtenerResumenVentas($filtros);

                // Tabla Resumen
                echo '<table border="1" cellpadding="5" cellspacing="0">';
                echo '<tr><th colspan="4" ' . $styleKpiTh . '>RESUMEN GENERAL DE VENTAS</th></tr>';
                echo '<tr>';
                echo '<td ' . $styleKpiTh . '>Total Ventas:</td><td ' . $styleKpiTd . '>' . (int)$resumen['total_operaciones'] . '</td>';
                echo '<td ' . $styleKpiTh . '>Total Recaudado:</td><td ' . $styleKpiTd . '>Bs. ' . number_format((float)$resumen['total_ingresos'], 2) . '</td>';
                echo '</tr>';
                echo '<tr>';
                echo '<td ' . $styleKpiTh . '>Total Unidades:</td><td ' . $styleKpiTd . '>' . (int)$resumen['total_unidades'] . '</td>';
                echo '<td ' . $styleKpiTh . '>Descuentos Otorgados:</td><td ' . $styleKpiTd . '>Bs. ' . number_format((float)$resumen['total_descuentos'], 2) . '</td>';
                echo '</tr>';
                echo '</table>';
                echo '<br>';

                // Tabla Detalle
                echo '<table border="1" cellpadding="5" cellspacing="0">';
                echo '<thead>';
                echo '<tr>';
                echo '<th ' . $styleHeader . '>N° Venta</th>';
                echo '<th ' . $styleHeader . '>Fecha y Hora</th>';
                echo '<th ' . $styleHeader . '>Cliente</th>';
                echo '<th ' . $styleHeader . '>Artículos</th>';
                echo '<th ' . $styleHeader . '>Descuento (Bs.)</th>';
                echo '<th ' . $styleHeader . '>Total (Bs.)</th>';
                echo '</tr>';
                echo '</thead><tbody>';

                $sumTotal = 0;
                $sumDescuento = 0;
                $sumArts = 0;

                foreach ($ventas as $v) {
                    $sumTotal += (float)$v['total'];
                    $sumDescuento += (float)$v['descuento'];
                    $sumArts += (int)$v['total_articulos'];

                    echo '<tr>';
                    echo '<td ' . $styleTdCenter . '>#' . str_pad($v['id'], 5, '0', STR_PAD_LEFT) . '</td>';
                    echo '<td ' . $styleTdCenter . '>' . date('d/m/Y H:i', strtotime($v['fecha_venta'])) . '</td>';
                    echo '<td ' . $styleTd . '>' . htmlspecialchars($v['cliente_nombre'] ?? 'Consumidor Final') . '</td>';
                    echo '<td ' . $styleTdCenter . '>' . (int)$v['total_articulos'] . '</td>';
                    echo '<td ' . $styleTdRight . '>' . number_format((float)$v['descuento'], 2) . '</td>';
                    echo '<td ' . $styleTdRight . '>' . number_format((float)$v['total'], 2) . '</td>';
                    echo '</tr>';
                }

                echo '</tbody><tfoot>';
                echo '<tr>';
                echo '<td colspan="3" ' . $styleTotal . ' style="text-align:right;">TOTALES:</td>';
                echo '<td ' . $styleTotal . ' style="text-align:center;">' . $sumArts . '</td>';
                echo '<td ' . $styleTotal . ' style="text-align:right;">Bs. ' . number_format($sumDescuento, 2) . '</td>';
                echo '<td ' . $styleTotal . ' style="text-align:right;">Bs. ' . number_format($sumTotal, 2) . '</td>';
                echo '</tr></tfoot></table>';
                break;

            case 'compras':
                $compras = $this->reporteModel->obtenerReporteCompras($filtros);
                $resumen = $this->reporteModel->obtenerResumenCompras($filtros);

                echo '<table border="1" cellpadding="5" cellspacing="0">';
                echo '<tr><th colspan="4" ' . $styleKpiTh . '>RESUMEN GENERAL DE COMPRAS</th></tr>';
                echo '<tr>';
                echo '<td ' . $styleKpiTh . '>Total Compras:</td><td ' . $styleKpiTd . '>' . (int)$resumen['total_operaciones'] . '</td>';
                echo '<td ' . $styleKpiTh . '>Total Invertido:</td><td ' . $styleKpiTd . '>Bs. ' . number_format((float)$resumen['total_egresos'], 2) . '</td>';
                echo '</tr>';
                echo '<tr>';
                echo '<td ' . $styleKpiTh . '>Total Unidades Compradas:</td><td ' . $styleKpiTd . '>' . (int)$resumen['total_unidades'] . '</td>';
                echo '<td ' . $styleKpiTh . '>Promedio por Compra:</td><td ' . $styleKpiTd . '>Bs. ' . ($resumen['total_operaciones'] > 0 ? number_format($resumen['total_egresos'] / $resumen['total_operaciones'], 2) : '0.00') . '</td>';
                echo '</tr>';
                echo '</table><br>';

                echo '<table border="1" cellpadding="5" cellspacing="0">';
                echo '<thead><tr>';
                echo '<th ' . $styleHeader . '>N° Compra</th>';
                echo '<th ' . $styleHeader . '>Fecha y Hora</th>';
                echo '<th ' . $styleHeader . '>Proveedor</th>';
                echo '<th ' . $styleHeader . '>Artículos Comprados</th>';
                echo '<th ' . $styleHeader . '>Total (Bs.)</th>';
                echo '</tr></thead><tbody>';

                $sumTotal = 0;
                $sumArts = 0;

                foreach ($compras as $c) {
                    $sumTotal += (float)$c['total'];
                    $sumArts += (int)$c['total_articulos'];

                    echo '<tr>';
                    echo '<td ' . $styleTdCenter . '>#' . str_pad($c['id'], 5, '0', STR_PAD_LEFT) . '</td>';
                    echo '<td ' . $styleTdCenter . '>' . date('d/m/Y H:i', strtotime($c['fecha_compra'])) . '</td>';
                    echo '<td ' . $styleTd . '>' . htmlspecialchars($c['proveedor_nombre'] ?? 'Sin Proveedor') . '</td>';
                    echo '<td ' . $styleTdCenter . '>' . (int)$c['total_articulos'] . '</td>';
                    echo '<td ' . $styleTdRight . '>' . number_format((float)$c['total'], 2) . '</td>';
                    echo '</tr>';
                }

                echo '</tbody><tfoot><tr>';
                echo '<td colspan="3" ' . $styleTotal . ' style="text-align:right;">TOTALES:</td>';
                echo '<td ' . $styleTotal . ' style="text-align:center;">' . $sumArts . '</td>';
                echo '<td ' . $styleTotal . ' style="text-align:right;">Bs. ' . number_format($sumTotal, 2) . '</td>';
                echo '</tr></tfoot></table>';
                break;

            case 'inventario':
                $articulos = $this->reporteModel->obtenerReporteInventario($filtros);
                $resumen = $this->reporteModel->obtenerResumenInventario($filtros);

                echo '<table border="1" cellpadding="5" cellspacing="0">';
                echo '<tr><th colspan="4" ' . $styleKpiTh . '>VALORIZACIÓN DEL INVENTARIO</th></tr>';
                echo '<tr>';
                echo '<td ' . $styleKpiTh . '>Total Artículos:</td><td ' . $styleKpiTd . '>' . (int)$resumen['total_articulos'] . '</td>';
                echo '<td ' . $styleKpiTh . '>Unidades en Stock:</td><td ' . $styleKpiTd . '>' . (int)$resumen['total_stock'] . '</td>';
                echo '</tr>';
                echo '<tr>';
                echo '<td ' . $styleKpiTh . '>Valor Costo Total:</td><td ' . $styleKpiTd . '>Bs. ' . number_format((float)$resumen['valor_costo_total'], 2) . '</td>';
                echo '<td ' . $styleKpiTh . '>Valor Venta Potencial:</td><td ' . $styleKpiTd . '>Bs. ' . number_format((float)$resumen['valor_venta_total'], 2) . '</td>';
                echo '</tr>';
                echo '<tr>';
                echo '<td ' . $styleKpiTh . '>Ganancia Estimada:</td><td ' . $styleKpiTd . ' style="color:#059669;">Bs. ' . number_format((float)$resumen['ganancia_potencial'], 2) . '</td>';
                echo '<td ' . $styleKpiTh . '>Bajo Stock / Agotados:</td><td ' . $styleKpiTd . ' style="color:#DC2626;">' . (int)$resumen['articulos_bajo_stock'] . ' / ' . (int)$resumen['articulos_agotados'] . '</td>';
                echo '</tr>';
                echo '</table><br>';

                echo '<table border="1" cellpadding="5" cellspacing="0">';
                echo '<thead><tr>';
                echo '<th ' . $styleHeader . '>ID</th>';
                echo '<th ' . $styleHeader . '>Artículo</th>';
                echo '<th ' . $styleHeader . '>Categoría</th>';
                echo '<th ' . $styleHeader . '>Marca</th>';
                echo '<th ' . $styleHeader . '>Stock</th>';
                echo '<th ' . $styleHeader . '>P. Compra</th>';
                echo '<th ' . $styleHeader . '>P. Venta</th>';
                echo '<th ' . $styleHeader . '>Valor Costo (Bs.)</th>';
                echo '<th ' . $styleHeader . '>Valor Venta (Bs.)</th>';
                echo '<th ' . $styleHeader . '>Margen Ganancia (Bs.)</th>';
                echo '</tr></thead><tbody>';

                foreach ($articulos as $art) {
                    echo '<tr>';
                    echo '<td ' . $styleTdCenter . '>#' . $art['id'] . '</td>';
                    echo '<td ' . $styleTd . '>' . htmlspecialchars($art['nombre']) . '</td>';
                    echo '<td ' . $styleTd . '>' . htmlspecialchars($art['categoria_nombre'] ?? '—') . '</td>';
                    echo '<td ' . $styleTd . '>' . htmlspecialchars($art['marca_nombre'] ?? '—') . '</td>';
                    echo '<td ' . $styleTdCenter . '>' . (int)$art['stock'] . '</td>';
                    echo '<td ' . $styleTdRight . '>' . number_format((float)$art['precio_compra'], 2) . '</td>';
                    echo '<td ' . $styleTdRight . '>' . number_format((float)$art['precio_venta'], 2) . '</td>';
                    echo '<td ' . $styleTdRight . '>' . number_format((float)$art['valor_total_costo'], 2) . '</td>';
                    echo '<td ' . $styleTdRight . '>' . number_format((float)$art['valor_total_venta'], 2) . '</td>';
                    echo '<td ' . $styleTdRight . '>' . number_format((float)$art['ganancia_potencial'], 2) . '</td>';
                    echo '</tr>';
                }

                echo '</tbody><tfoot><tr>';
                echo '<td colspan="4" ' . $styleTotal . ' style="text-align:right;">TOTALES:</td>';
                echo '<td ' . $styleTotal . ' style="text-align:center;">' . (int)$resumen['total_stock'] . '</td>';
                echo '<td colspan="2" ' . $styleTotal . '>&nbsp;</td>';
                echo '<td ' . $styleTotal . ' style="text-align:right;">Bs. ' . number_format((float)$resumen['valor_costo_total'], 2) . '</td>';
                echo '<td ' . $styleTotal . ' style="text-align:right;">Bs. ' . number_format((float)$resumen['valor_venta_total'], 2) . '</td>';
                echo '<td ' . $styleTotal . ' style="text-align:right;">Bs. ' . number_format((float)$resumen['ganancia_potencial'], 2) . '</td>';
                echo '</tr></tfoot></table>';
                break;

            case 'top':
                $top = $this->reporteModel->obtenerTopVendidos($filtros);

                echo '<table border="1" cellpadding="5" cellspacing="0">';
                echo '<thead><tr>';
                echo '<th ' . $styleHeader . '>Ranking</th>';
                echo '<th ' . $styleHeader . '>Artículo</th>';
                echo '<th ' . $styleHeader . '>Categoría</th>';
                echo '<th ' . $styleHeader . '>Marca</th>';
                echo '<th ' . $styleHeader . '>P. Venta</th>';
                echo '<th ' . $styleHeader . '>Unidades Vendidas</th>';
                echo '<th ' . $styleHeader . '>Total Recaudado (Bs.)</th>';
                echo '<th ' . $styleHeader . '>Ganancia Estimada (Bs.)</th>';
                echo '</tr></thead><tbody>';

                $i = 1;
                $totUnidades = 0;
                $totRecaudado = 0;
                $totGanancia = 0;

                foreach ($top as $t) {
                    $totUnidades += (int)$t['total_unidades_vendidas'];
                    $totRecaudado += (float)$t['total_recaudado'];
                    $totGanancia += (float)$t['ganancia_estimada'];

                    echo '<tr>';
                    echo '<td ' . $styleTdCenter . '>#' . $i++ . '</td>';
                    echo '<td ' . $styleTd . '>' . htmlspecialchars($t['nombre']) . '</td>';
                    echo '<td ' . $styleTd . '>' . htmlspecialchars($t['categoria_nombre'] ?? '—') . '</td>';
                    echo '<td ' . $styleTd . '>' . htmlspecialchars($t['marca_nombre'] ?? '—') . '</td>';
                    echo '<td ' . $styleTdRight . '>' . number_format((float)$t['precio_venta'], 2) . '</td>';
                    echo '<td ' . $styleTdCenter . ' style="font-weight:bold;">' . (int)$t['total_unidades_vendidas'] . '</td>';
                    echo '<td ' . $styleTdRight . '>' . number_format((float)$t['total_recaudado'], 2) . '</td>';
                    echo '<td ' . $styleTdRight . '>' . number_format((float)$t['ganancia_estimada'], 2) . '</td>';
                    echo '</tr>';
                }

                echo '</tbody><tfoot><tr>';
                echo '<td colspan="5" ' . $styleTotal . ' style="text-align:right;">TOTALES:</td>';
                echo '<td ' . $styleTotal . ' style="text-align:center;">' . $totUnidades . '</td>';
                echo '<td ' . $styleTotal . ' style="text-align:right;">Bs. ' . number_format($totRecaudado, 2) . '</td>';
                echo '<td ' . $styleTotal . ' style="text-align:right;">Bs. ' . number_format($totGanancia, 2) . '</td>';
                echo '</tr></tfoot></table>';
                break;

            case 'balance':
                $balance = $this->reporteModel->obtenerBalance($filtros);

                echo '<table border="1" cellpadding="8" cellspacing="0" width="600">';
                echo '<tr><th colspan="2" ' . $styleHeader . '>ESTADO DE RESULTADOS / BALANCE GENERAL</th></tr>';
                echo '<tr><td ' . $styleKpiTh . '>Total Ingresos por Ventas:</td><td ' . $styleKpiTd . ' style="color:#059669;">Bs. ' . number_format($balance['total_ingresos'], 2) . '</td></tr>';
                echo '<tr><td ' . $styleKpiTh . '>Total Egresos por Compras:</td><td ' . $styleKpiTd . ' style="color:#DC2626;">Bs. ' . number_format($balance['total_egresos'], 2) . '</td></tr>';
                echo '<tr><td ' . $styleTotal . '>UTILIDAD BRUTA ESTIMADA:</td><td ' . $styleTotal . ' style="text-align:right; font-size:12pt; color:' . ($balance['utilidad_bruta'] >= 0 ? '#059669' : '#DC2626') . ';">Bs. ' . number_format($balance['utilidad_bruta'], 2) . '</td></tr>';
                echo '<tr><td ' . $styleTd . '>Transacciones de Ventas:</td><td ' . $styleTdRight . '>' . $balance['total_ventas'] . '</td></tr>';
                echo '<tr><td ' . $styleTd . '>Transacciones de Compras:</td><td ' . $styleTdRight . '>' . $balance['total_compras'] . '</td></tr>';
                echo '<tr><td ' . $styleTd . '>Total Artículos Vendidos:</td><td ' . $styleTdRight . '>' . $balance['unidades_vendidas'] . ' unidades</td></tr>';
                echo '<tr><td ' . $styleTd . '>Total Artículos Comprados:</td><td ' . $styleTdRight . '>' . $balance['unidades_compradas'] . ' unidades</td></tr>';
                echo '</table>';
                break;
        }

        exit;
    }

    /**
     * Renderiza la vista PDF / Imprimible con estilos profesionales y descargable con 1-click
     */
    public function exportarPdf(): void
    {
        $tipo = $_GET['tipo'] ?? 'ventas';
        $filtros = [
            'fecha_desde'  => $_GET['fecha_desde'] ?? '',
            'fecha_hasta'  => $_GET['fecha_hasta'] ?? '',
            'cliente'      => $_GET['cliente'] ?? '',
            'proveedor_id' => $_GET['proveedor_id'] ?? '',
            'categoria_id' => $_GET['categoria_id'] ?? '',
            'marca_id'     => $_GET['marca_id'] ?? '',
            'estado_stock' => $_GET['estado_stock'] ?? '',
            'limite'       => $_GET['limite'] ?? 50,
            'busqueda'     => $_GET['busqueda'] ?? ''
        ];

        $datos = [];
        $resumen = [];

        switch ($tipo) {
            case 'compras':
                $datos = $this->reporteModel->obtenerReporteCompras($filtros);
                $resumen = $this->reporteModel->obtenerResumenCompras($filtros);
                break;

            case 'inventario':
                $datos = $this->reporteModel->obtenerReporteInventario($filtros);
                $resumen = $this->reporteModel->obtenerResumenInventario($filtros);
                break;

            case 'top':
                $datos = $this->reporteModel->obtenerTopVendidos($filtros);
                $resumen = $this->reporteModel->obtenerResumenVentas($filtros);
                break;

            case 'balance':
                $resumen = $this->reporteModel->obtenerBalance($filtros);
                $datos = [
                    'ventas' => $this->reporteModel->obtenerReporteVentas($filtros),
                    'compras' => $this->reporteModel->obtenerReporteCompras($filtros)
                ];
                break;

            case 'ventas':
            default:
                $tipo = 'ventas';
                $datos = $this->reporteModel->obtenerReporteVentas($filtros);
                $resumen = $this->reporteModel->obtenerResumenVentas($filtros);
                break;
        }

        $this->renderStandalone('reportes/pdf_template', [
            'tipo'    => $tipo,
            'filtros' => $filtros,
            'datos'   => $datos,
            'resumen' => $resumen
        ]);
    }
}
