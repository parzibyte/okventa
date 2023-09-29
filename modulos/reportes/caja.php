<?php
if (!defined("RAIZ")) define("RAIZ", dirname(dirname(dirname(__FILE__))));
require_once RAIZ . "/modulos/db.php";
require_once RAIZ . "/modulos/funciones.php";
require_once RAIZ . "/modulos/caja/caja.php";
require RAIZ . "/vendor/autoload.php";

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

inicia_sesion_segura();
if ($_SESSION["administrador"] !== 1) {
    echo json_encode("Restringido");
    exit();
}
$payload = json_decode(file_get_contents("php://input"));
$fecha_inicio = $payload->fecha_inicio;
$fecha_fin = $payload->fecha_fin;
$movimientos_caja = consultar_movimientos($fecha_inicio, $fecha_fin);
$documento = new Spreadsheet();
$documento
    ->getProperties()
    ->setTitle('Reporte caja');
$hoja = $documento->getActiveSheet();
$fila = 1;
$columna = 1;
$encabezados = ["Caja chica", "Ventas", "Gastos", "Fecha", "Número de venta", "Usuario"];
foreach ($encabezados as $encabezado) {
    $hoja->setCellValue([$columna, $fila], $encabezado);
    $columna++;
}
$fila++;
$propiedades = ["caja_chica", "ventas", "gastos", "fecha", "no_venta", "usuario"];
$total_caja_chica = 0;
$total_ventas = 0;
$total_gastos = 0;
$total = 0;
$utilidad = 0;
for ($x = count($movimientos_caja) - 1; $x >= 0; $x--) {
    $valor = $movimientos_caja[$x];
    $columna = 1;
    $hoja->setCellValue([$columna, $fila], $valor["caja_chica"] == 0 ? "No aplica" : $valor["caja_chica"]);
    $hoja->getStyle([$columna, $fila])->getNumberFormat()->setFormatCode('[$S/]* #,##0.00_-;[Red][$S/]* -#,##0.00_-;[$S/]* "-"??_-;_-@_-');
    if ($valor["caja_chica"] != 0) {
        $total_caja_chica += $valor["caja_chica"];
    }
    $columna++;
    $hoja->setCellValue([$columna, $fila], $valor["ventas"] == 0 ? "No aplica" : $valor["ventas"]);
    $hoja->getStyle([$columna, $fila])->getNumberFormat()->setFormatCode('[$S/]* #,##0.00_-;[Red][$S/]* -#,##0.00_-;[$S/]* "-"??_-;_-@_-');
    if ($valor["ventas"] != 0) {
        $total_ventas += $valor["ventas"];
    }
    $columna++;
    $hoja->setCellValue([$columna, $fila], $valor["gastos"] == 0 ? "No aplica" : $valor["gastos"]);
    $hoja->getStyle([$columna, $fila])->getNumberFormat()->setFormatCode('[$S/]* #,##0.00_-;[Red][$S/]* -#,##0.00_-;[$S/]* "-"??_-;_-@_-');
    if ($valor["gastos"] != 0) {
        $total_gastos += $valor["gastos"];
    }
    $columna++;
    $hoja->setCellValue([$columna, $fila], $valor["fecha"]);
    $columna++;
    $hoja->setCellValue([$columna, $fila], $valor["no_venta"] == "null" ? "No aplica" : $valor["no_venta"]);
    $columna++;
    $hoja->setCellValue([$columna, $fila], $valor["usuario"]);
    $columna++;
    $fila++;
}
$columna = 1;
$total = $total_ventas + $total_caja_chica - $total_gastos;
$utilidad = $total_ventas - $total_gastos;


$hoja->setCellValue([$columna, $fila], "Caja chica");
$columna++;
$hoja->setCellValue([$columna, $fila], $total_caja_chica);
$hoja->getStyle([$columna, $fila])->getNumberFormat()->setFormatCode('[$S/]* #,##0.00_-;[Red][$S/]* -#,##0.00_-;[$S/]* "-"??_-;_-@_-');
$fila++;
$columna = 1;


$hoja->setCellValue([$columna, $fila], "Gastos");
$columna++;
$hoja->setCellValue([$columna, $fila], $total_gastos);
$hoja->getStyle([$columna, $fila])->getNumberFormat()->setFormatCode('[$S/]* #,##0.00_-;[Red][$S/]* -#,##0.00_-;[$S/]* "-"??_-;_-@_-');
$fila++;
$columna = 1;


$hoja->setCellValue([$columna, $fila], "Ventas");
$columna++;
$hoja->setCellValue([$columna, $fila], $total_ventas);
$hoja->getStyle([$columna, $fila])->getNumberFormat()->setFormatCode('[$S/]* #,##0.00_-;[Red][$S/]* -#,##0.00_-;[$S/]* "-"??_-;_-@_-');
$fila++;
$columna = 1;


$hoja->setCellValue([$columna, $fila], "Total en caja");
$columna++;
$hoja->setCellValue([$columna, $fila], $total);
$hoja->getStyle([$columna, $fila])->getNumberFormat()->setFormatCode('[$S/]* #,##0.00_-;[Red][$S/]* -#,##0.00_-;[$S/]* "-"??_-;_-@_-');
$fila++;
$columna = 1;


$hoja->setCellValue([$columna, $fila], "Utilidad");
$columna++;
$hoja->setCellValue([$columna, $fila], $utilidad);
$hoja->getStyle([$columna, $fila])->getNumberFormat()->setFormatCode('[$S/]* #,##0.00_-;[Red][$S/]* -#,##0.00_-;[$S/]* "-"??_-;_-@_-');
$fila++;
$columna = 1;

ob_clean();
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
$nombreDelDocumento = "reporte.xlsx";
header('Content-Disposition: attachment;filename="' . $nombreDelDocumento . '"');
header('Cache-Control: max-age=0');
$writer = new Xlsx($documento);
$writer->save('php://output');
exit;
