<?php
if (!defined("RAIZ")) define("RAIZ", dirname(dirname(dirname(__FILE__))));
require_once RAIZ . "/modulos/db.php";
require_once RAIZ . "/modulos/funciones.php";
require_once RAIZ . "/modulos/gastos/gastos.php";
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
$todos_los_gastos = consultar_todos_los_gastos($fecha_inicio, $fecha_fin);
$documento = new Spreadsheet();
$documento
    ->getProperties()
    ->setTitle('Reporte de gastos');
$hoja = $documento->getActiveSheet();
$fila = 1;
$columna = 1;
$encabezados = ["Importe", "Concepto", "Descripción", "Número de remisión", "Fecha", "Usuario"];
foreach ($encabezados as $encabezado) {
    $hoja->setCellValue([$columna, $fila], $encabezado);
    $columna++;
}
$fila++;
$propiedades = ["importe", "concepto", "descripcion", "numero_remision", "fecha", "usuario"];
$total = 0;
for ($x = count($todos_los_gastos) - 1; $x >= 0; $x--) {
    $valor = $todos_los_gastos[$x];
    $columna = 1;
    $total += $valor["importe"];
    foreach ($propiedades as $propiedad) {
        if (in_array($propiedad, ["importe"])) {
            $hoja->getStyle([$columna, $fila])->getNumberFormat()->setFormatCode('[$S/]* #,##0.00_-;[Red][$S/]* -#,##0.00_-;[$S/]* "-"??_-;_-@_-');
        }
        $hoja->setCellValue([$columna, $fila], $valor[$propiedad]);
        $columna++;
    }
    $fila++;
}
$columna = 1;
$hoja->setCellValue([$columna, $fila], "Total");
$columna++;
$hoja->setCellValue([$columna, $fila], $total);
$hoja->getStyle([$columna, $fila])->getNumberFormat()->setFormatCode('[$S/]* #,##0.00_-;[Red][$S/]* -#,##0.00_-;[$S/]* "-"??_-;_-@_-');
ob_clean();
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
$nombreDelDocumento = "reporte.xlsx";
header('Content-Disposition: attachment;filename="' . $nombreDelDocumento . '"');
header('Cache-Control: max-age=0');
$writer = new Xlsx($documento);
$writer->save('php://output');
exit;
