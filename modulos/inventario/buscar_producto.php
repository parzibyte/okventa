<?php
if( !isset( $_POST["busqueda"] ) ) exit();
#Definimos la raíz del directorio
if ( !defined( "RAIZ" ) ) 
{
	define( "RAIZ", dirname( dirname( dirname( __FILE__ ) ) ) );
}
require_once RAIZ . "/modulos/db.php";
require_once RAIZ . "/modulos/inventario/inventario.php";
$busqueda = $_POST["busqueda"];
$limite = $_POST["limite"];
$offset = $_POST["offset"];
$datos_producto = buscar_producto($busqueda, $limite, $offset);
echo json_encode($datos_producto);
?>