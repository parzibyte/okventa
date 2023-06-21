<?php
require __DIR__ . '/ticket/vendor/autoload.php';
use Mike42\Escpos\Printer;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;

function abre_cajon()
{
    /*Conectamos con la impresora*/
    $nombre_impresora = trim(file(__DIR__ . '/impresora.ini')[0]);
    $connector = new WindowsPrintConnector($nombre_impresora);
    $printer = new Printer($connector);
    /*No imprimimos nada, solamente abrimos cajón*/
    $printer->cut();
    $printer->pulse();
    $printer->close();
}

function imprime_ticket($productos, $id_venta, $cambio)
{


    if (!defined("RAIZ")) define("RAIZ", dirname(__FILE__));
    $datos_empresa_recuperados = file(RAIZ . "/modulos/datos_empresa.txt");
    $datos_empresa = array(
        "nombre" => trim($datos_empresa_recuperados[0]),
        "telefono" => trim($datos_empresa_recuperados[1]),
        "rfc" => trim($datos_empresa_recuperados[2]),
        "direccion" => trim($datos_empresa_recuperados[3]),
        "colonia" => trim($datos_empresa_recuperados[4]),
        "cp" => trim($datos_empresa_recuperados[5]),
        "ciudad" => trim($datos_empresa_recuperados[6])
    );

    /*Conectamos con la impresora*/
    $nombre_impresora = trim(file(__DIR__ . '/impresora.ini')[0]);
    $connector = new WindowsPrintConnector($nombre_impresora);
    $printer = new Printer($connector);

    /*Cargamos el logo*/
    $ruta_imagen_logo = dirname(__DIR__) . "/img/logo.png";
    $logo = EscposImage::load($ruta_imagen_logo, false);

    /*Le decimos que centre lo que vaya a imprimir*/
    $printer->setJustification(Printer::JUSTIFY_CENTER);

    /*Imprimimos imagen y avanzamos el papel*/
    $printer->bitImage($logo);
    $printer->feed();

    /*Imprimimos los datos de la empresa*/
    foreach ($datos_empresa as $dato) {
        $printer->text($dato . "\n");
    }

    /*Hacemos que el texto sea en negritas e imprimimos el nùmero de venta*/
    $printer->setEmphasis(true);
    $printer->text("Venta #" . $id_venta);
    $printer->setEmphasis(false);
    $printer->feed();


    $printer->setJustification(Printer::JUSTIFY_LEFT);
    $total = 0;
    foreach ($productos as $producto) {
        $importe = $producto->cantidad * $producto->precio_venta;
        $total += $importe;
        $importe_formateado = number_format($importe, 2, ".", ",");
        $printer->setJustification(Printer::JUSTIFY_LEFT);
        $printer->text($producto->cantidad . "x" . $producto->nombre . "\n");
        $printer->setJustification(Printer::JUSTIFY_RIGHT);
        $printer->text(' $' . $importe_formateado . "\n");
    }
    $ayudante_total = number_format($total, 2, ".", ",");
    $ayudante_cambio = number_format($cambio, 2, ".", ",");
    $ayudante_pago = number_format($total + $cambio, 2, ".", ",");

    $printer->selectPrintMode(Printer::MODE_EMPHASIZED | Printer::MODE_FONT_B);
    $printer->text("--------\n");
    $printer->text("SU PAGO $" . $ayudante_pago . "\n");
    $printer->text("TOTAL $" . $ayudante_total . "\n");
    $printer->text("CAMBIO $" . $ayudante_cambio);
    $printer->feed();

    /*Calculamos la hora para desearle buenos días, tardes o noches*/
    $hora = date("G");
    $str_deseo = "a";
    if ($hora >= 6 and $hora <= 12) {
        $str_deseo = "le deseamos un buen dia";
    }
    if ($hora >= 12 and $hora <= 19) {
        $str_deseo = "le deseamos una buena tarde";
    }
    if ($hora >= 19 and $hora <= 24) {
        $str_deseo = "le deseamos una buena noche";
    }
    if ($hora >= 0 and $hora <= 6) {
        $str_deseo = "le deseamos un buen dia";
    }
    /*Le deseamos al cliente buenas tardes, noches o días*/

    $printer->selectPrintMode(Printer::MODE_FONT_A);
    $printer->setJustification(Printer::JUSTIFY_CENTER);
    $printer->text(strtoupper($str_deseo));
    $printer->feed();

    /*Terminamos el trabajo de impresión y abrimos el cajón*/
    $printer->feed(2);
    $printer->cut();
    $printer->pulse();
    $printer->close();
}