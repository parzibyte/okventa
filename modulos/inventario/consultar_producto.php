<?php
if( !isset( $_POST["codigo_producto"] ) ) exit();
#Definimos la raíz del directorio
if ( !defined( "RAIZ" ) ) 
{
	define( "RAIZ", dirname( dirname( dirname( __FILE__ ) ) ) );
}
require_once RAIZ . "/modulos/db.php";
require_once RAIZ . "/modulos/inventario/inventario.php";
$codigo_producto = $_POST["codigo_producto"];
$datos_producto = consultar_producto( $codigo_producto );
echo json_encode($datos_producto);
?>