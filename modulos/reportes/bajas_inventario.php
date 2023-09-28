<?php
if (!defined("RAIZ")) define("RAIZ", dirname(dirname(dirname(__FILE__))));
require_once RAIZ . "/modulos/db.php";
require_once RAIZ . "/modulos/bajas_inventario/bajas_inventario.php";
require_once RAIZ . "/modulos/funciones.php";
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
$todas_las_bajas = consultar_todas_las_bajas($fecha_inicio, $fecha_fin);
$documento = new Spreadsheet();
$documento
    ->getProperties()
    ->setTitle('Reporte bajas de inventario');
$hoja = $documento->getActiveSheet();
$fila = 1;
$columna = 1;
$encabezados = ["Código del producto", "Nombre del producto", "Cantidad", "Razón de baja", "Usuario", "Fecha"];
foreach ($encabezados as $encabezado) {
    $hoja->setCellValue([$columna, $fila], $encabezado);
    $columna++;
}
$fila++;
$propiedades = ["codigo_producto", "nombre_producto", "numero_piezas", "razon_baja", "usuario", "fecha"];
for ($x = count($todas_las_bajas) - 1; $x >= 0; $x--) {
    $valor = $todas_las_bajas[$x];
    $columna = 1;
    foreach ($propiedades as $propiedad) {
        $hoja->setCellValue([$columna, $fila], $valor[$propiedad]);
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
