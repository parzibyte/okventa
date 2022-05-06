<?php
if (!isset($_POST["fecha_inicio"])) exit();
if (!defined("RAIZ")) {
    define("RAIZ", dirname(dirname(dirname(__FILE__))));
}
$fecha_inicio = $_POST["fecha_inicio"];
$fecha_fin = $_POST["fecha_fin"];
$familia = $_POST["familia"];
require_once RAIZ . "/modulos/db.php";
require_once RAIZ . "/modulos/ventas/ventas.php";
require_once RAIZ . "/modulos/funciones.php";
inicia_sesion_segura();
$todas_las_ventas = consultar_todas_las_ventas($fecha_inicio, $fecha_fin, $familia);
echo json_encode($todas_las_ventas);