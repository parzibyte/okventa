<?php
#Definimos la raíz del directorio
if (!defined("RAIZ")) {
    define("RAIZ", dirname(dirname(dirname(__FILE__))));
}
require_once RAIZ . "/modulos/db.php";
require_once RAIZ . "/modulos/inventario/inventario.php";
$familia = $_POST["familia"];
$todos_los_productos_en_stock = consultar_todos_los_productos_en_stock($familia);
echo json_encode($todos_los_productos_en_stock);
?>