<?php
if (!defined("RAIZ")) define("RAIZ", dirname(dirname(dirname(__FILE__))));
require_once RAIZ . "/modulos/db.php";
require_once RAIZ . "/modulos/funciones.php";
require_once RAIZ . "/modulos/ventas/ventas.php";
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
$familia = $payload->familia;
$todas_las_ventas = consultar_todas_las_ventas($fecha_inicio, $fecha_fin, $familia);
$todas_las_ventas_por_familia = consultar_todas_las_ventas_por_familia($fecha_inicio, $fecha_fin);
$documento = new Spreadsheet();
$documento
    ->getProperties()
    ->setTitle('Reporte ventas');
$hoja = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($documento, 'Por familia');
$documento->addSheet($hoja, 1);
$hoja = $documento->getSheet(1);
$fila = 1;
$columna = 1;

$encabezados = ["Familia", "Total", "Utilidad"];
foreach ($encabezados as $encabezado) {
    $hoja->setCellValue([$columna, $fila], $encabezado);
    $columna++;
}
$fila++;
$propiedades = ["familia", "total", "utilidad"];
$total_familias = 0;
$total_utilidad_familias = 0;
foreach ($todas_las_ventas_por_familia as $valor) {
    $columna = 1;
    foreach ($propiedades as $propiedad) {
        $hoja->setCellValue([$columna, $fila], $valor[$propiedad]);
        if (in_array($propiedad, ["total", "utilidad"])) {
            $hoja->getStyle([$columna, $fila])->getNumberFormat()->setFormatCode('[$S/]* #,##0.00_-;[Red][$S/]* -#,##0.00_-;[$S/]* "-"??_-;_-@_-');
        }
        if ($propiedad === "total") {
            $total_familias += $valor[$propiedad];
        }
        if ($propiedad === "utilidad") {
            $total_utilidad_familias += $valor[$propiedad];
        }
        $columna++;
    }
    $fila++;
}
$columna = 1;
$hoja->setCellValue([$columna, $fila], "Totales");
$columna++;
$hoja->setCellValue([$columna, $fila], $total_familias);
$hoja->getStyle([$columna, $fila])->getNumberFormat()->setFormatCode('[$S/]* #,##0.00_-;[Red][$S/]* -#,##0.00_-;[$S/]* "-"??_-;_-@_-');
$columna++;
$hoja->setCellValue([$columna, $fila], $total_utilidad_familias);
$hoja->getStyle([$columna, $fila])->getNumberFormat()->setFormatCode('[$S/]* #,##0.00_-;[Red][$S/]* -#,##0.00_-;[$S/]* "-"??_-;_-@_-');




$hoja = $documento->getSheet(0);
$hoja->setTitle("General");
$fila = 1;
$columna = 1;
$encabezados = ["Número de venta", "Fecha", "Productos", "Número de productos", "Total", "Utilidad", "Usuario"];
foreach ($encabezados as $encabezado) {
    $hoja->setCellValue([$columna, $fila], $encabezado);
    $columna++;
}
$ventas_agrupadas = [];

function indiceDeVenta($ventas, $venta)
{
    for ($x = 0; $x < count($ventas); $x++) {
        if ($ventas[$x]["numero_venta"] == $venta["numero_venta"]) {
            return $x;
        }
    }
    return -1;
}
foreach ($todas_las_ventas as $venta) {
    $posibleIndice = indiceDeVenta($ventas_agrupadas, $venta);
    if ($posibleIndice === -1) {
        $venta["productos"] = [
            [
                "numero" => $venta["numero_productos"],
                "nombre" => $venta["nombre_producto"],
            ],
        ];
        $venta["numero_productos"] = $venta["numero_productos"];
        $venta["utilidad"] = $venta["utilidad"];
        $venta["total"] = $venta["total"];
        array_push($ventas_agrupadas, $venta);
    } else {
        $ventas_agrupadas[$posibleIndice]["numero_productos"] += $venta["numero_productos"];
        $ventas_agrupadas[$posibleIndice]["utilidad"] += $venta["utilidad"];
        $ventas_agrupadas[$posibleIndice]["total"] += $venta["total"];
        array_push($ventas_agrupadas[$posibleIndice]["productos"], [
            "numero" => $venta["numero_productos"],
            "nombre" => $venta["nombre_producto"],
        ]);
    }
}
$fila++;
$propiedades = ["numero_venta", "fecha", "productos", "numero_productos", "total", "utilidad", "usuario"];
$propiedades_producto = ["numero", "nombre"];
$total_ventas_generales = 0;
$total_utilidad_general = 0;
for ($x = 0; $x < count($ventas_agrupadas); $x++) {
    $valor = $ventas_agrupadas[$x];
    $total_ventas_generales += $valor["total"];
    $total_utilidad_general += $valor["utilidad"];
    $columna = 1;
    foreach ($propiedades as $propiedad) {
        $verdadero_valor = $valor[$propiedad];
        if ($propiedad === "productos") {
            $verdadero_valor = "";
            foreach ($valor[$propiedad] as $producto) {
                $verdadero_valor .= sprintf("%s: %0.2d\r", $producto["nombre"], $producto["numero"]);
            }
            $hoja->setCellValue([$columna, $fila], $verdadero_valor);
        } else {
            $hoja->setCellValue([$columna, $fila], $verdadero_valor);
            if (in_array($propiedad, ["total", "utilidad"])) {
                $hoja->getStyle([$columna, $fila])->getNumberFormat()->setFormatCode('[$S/]* #,##0.00_-;[Red][$S/]* -#,##0.00_-;[$S/]* "-"??_-;_-@_-');
            }
        }
        $columna++;
    }
    $fila++;
}
$columna = 1;
$hoja->setCellValue([$columna, $fila], "Total");
$columna++;
$hoja->setCellValue([$columna, $fila], $total_ventas_generales);
$hoja->getStyle([$columna, $fila])->getNumberFormat()->setFormatCode('[$S/]* #,##0.00_-;[Red][$S/]* -#,##0.00_-;[$S/]* "-"??_-;_-@_-');
$fila++;
$columna = 1;
$hoja->setCellValue([$columna, $fila], "Utilidad");
$columna++;
$hoja->setCellValue([$columna, $fila], $total_utilidad_general);
$hoja->getStyle([$columna, $fila])->getNumberFormat()->setFormatCode('[$S/]* #,##0.00_-;[Red][$S/]* -#,##0.00_-;[$S/]* "-"??_-;_-@_-');
$fila++;
ob_clean();
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
$nombreDelDocumento = "reporte.xlsx";
header('Content-Disposition: attachment;filename="' . $nombreDelDocumento . '"');
header('Cache-Control: max-age=0');
$writer = new Xlsx($documento);
$writer->save('php://output');
exit;
