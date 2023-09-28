<?php
#Definimos la raíz del directorio
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
$filtro = $_POST["filtro"];
$orden = $_POST["orden"];
$valor_del_inventario = consultar_valor_del_inventario();
$todos_los_productos = consultar_todos_los_productos_reportes($filtro, $orden);
$documento = new Spreadsheet();
$documento
    ->getProperties()
    ->setTitle('Reporte valor inventario');

$writer = new Xlsx($documento);
$hoja = $documento->getActiveSheet();
$fila = 1;
$columna = 1;
#$valores = ["Total de productos", count($todos_los_productos), "Valor del inventario", $valor_del_inventario];
$valores = ["Total de productos", 1, "Valor del inventario", $valor_del_inventario];
foreach ($valores as $valor) {
    $hoja->setCellValue([$columna, $fila], $valor);
    $columna++;
}
$fila++;
$encabezados = ["Código", "Nombre", "Precio de compra", "Precio de venta", "Utilidad", "Cantidad", "Familia"];
$columna = 1;
foreach ($encabezados as $encabezado) {
    $hoja->setCellValue([$columna, $fila], $encabezado);
    $columna++;
}
$fila++;
$propiedades = ["codigo", "nombre", "precio_compra", "precio_venta", "utilidad", "existencia", "familia"];
foreach ($todos_los_productos as $producto) {
    $columna = 1;
    foreach ($propiedades as $propiedad) {
        $hoja->setCellValue([$columna, $fila], $producto[$propiedad]);
        $columna++;
    }
    $fila++;
}
$writer->save(sprintf('otro_%s_%s.xlsx',$filtro,$orden));
