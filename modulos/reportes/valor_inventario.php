<?php
if (!defined("RAIZ")) define("RAIZ", dirname(dirname(dirname(__FILE__))));
require_once RAIZ . "/modulos/db.php";
require_once RAIZ . "/modulos/funciones.php";
require_once RAIZ . "/modulos/inventario/inventario.php";
require RAIZ . "/vendor/autoload.php";

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

inicia_sesion_segura();
if ($_SESSION["administrador"] !== 1) {
    echo json_encode("Restringido");
    exit();
}
$payload = json_decode(file_get_contents("php://input"));
$filtro = $payload->filtro;
$orden = $payload->orden;
$valor_del_inventario = consultar_valor_del_inventario();
$todos_los_productos = consultar_todos_los_productos_reportes($filtro, $orden);
$documento = new Spreadsheet();
$documento
    ->getProperties()
    ->setTitle('Reporte valor inventario');
$hoja = $documento->getActiveSheet();
$fila = 1;
$columna = 1;
$valores = ["Total de productos", count($todos_los_productos), "Valor del inventario", $valor_del_inventario];
foreach ($valores as $valor) {
    $hoja->setCellValue([$columna, $fila], $valor);
    $columna++;
}

$hoja->getStyle([4, 1])->getNumberFormat()->setFormatCode('[$S/]* #,##0.00_-;[Red][$S/]* -#,##0.00_-;[$S/]* "-"??_-;_-@_-');
$fila++;
$encabezados = ["Código", "Nombre", "Precio de compra", "Precio de venta", "Utilidad", "Cantidad", "Familia"];
$columna = 1;
foreach ($encabezados as $encabezado) {
    $hoja->setCellValue([$columna, $fila], $encabezado);
    $columna++;
}
$fila++;
$propiedades = ["codigo", "nombre", "precio_compra", "precio_venta", "utilidad", "existencia", "familia"];

for ($x = count($todos_los_productos) - 1; $x >= 0; $x--) {
    $producto = $todos_los_productos[$x];
    $columna = 1;
    foreach ($propiedades as $propiedad) {
        $hoja->setCellValue([$columna, $fila], $producto[$propiedad]);
        if (in_array($propiedad, ["precio_compra", "precio_venta", "utilidad"])) {
            $hoja->getStyle([$columna, $fila])->getNumberFormat()->setFormatCode('[$S/]* #,##0.00_-;[Red][$S/]* -#,##0.00_-;[$S/]* "-"??_-;_-@_-');
        }
        $columna++;
    }
    $fila++;
}
ob_clean();
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
$nombreDelDocumento = "reporte.xlsx";
header('Content-Disposition: attachment;filename="' . $nombreDelDocumento . '"');
header('Cache-Control: max-age=0');
$writer = new Xlsx($documento);
$writer->save('php://output');
exit;
