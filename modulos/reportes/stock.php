<?php
if (!defined("RAIZ")) define("RAIZ", dirname(dirname(dirname(__FILE__))));
require_once RAIZ . "/modulos/db.php";
require_once RAIZ . "/modulos/funciones.php";
require_once RAIZ . "/modulos/inventario/inventario.php";
require RAIZ . "/vendor/autoload.php";

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

inicia_sesion_segura();
if ($_SESSION["administrador"] !== 1) {
    echo json_encode("Restringido");
    exit();
}
$payload = json_decode(file_get_contents("php://input"));
$familia = $payload->familia;
$todos_los_productos_en_stock = consultar_todos_los_productos_en_stock($familia);
$documento = new Spreadsheet();
$documento
    ->getProperties()
    ->setTitle('Reporte de stock');
$hoja = $documento->getActiveSheet();
$fila = 1;
$columna = 1;
$encabezados = ["Código", "Nombre", "Existencia", "Existencia permitida", "Familia"];
foreach ($encabezados as $encabezado) {
    $hoja->setCellValue([$columna, $fila], $encabezado);
    $columna++;
}
$fila++;
$propiedades = ["codigo", "nombre", "existencia", "stock", "familia"];
for ($x = 0; $x < count($todos_los_productos_en_stock); $x++) {
    $valor = $todos_los_productos_en_stock[$x];
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
