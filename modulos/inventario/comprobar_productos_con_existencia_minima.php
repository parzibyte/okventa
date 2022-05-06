<?php
#Definimos la raíz del directorio
if (!defined("RAIZ")) define("RAIZ", dirname(dirname(dirname(__FILE__))));
require_once RAIZ . "/modulos/db.php";
require_once RAIZ . "/modulos/inventario/inventario.php";
$cantidad_productos_con_existencia_minima = comprobar_productos_con_existencia_minima();
echo json_encode($cantidad_productos_con_existencia_minima);