<?php
if( !isset( $_POST["datos_producto"] ) ) exit();
#Definimos la raíz del directorio
if ( !defined( "RAIZ" ) ) 
{
    define( "RAIZ", dirname( dirname( dirname( __FILE__ ) ) ) );
}
$datos_producto = $_POST["datos_producto"];
require_once RAIZ . "/modulos/db.php";
require_once RAIZ . "/modulos/inventario/inventario.php";
$resultado = insertar_producto(
		$datos_producto[0], 
		$datos_producto[1], 
		$datos_producto[2], 
		$datos_producto[3], 
		$datos_producto[4], 
		$datos_producto[5],
		$datos_producto[6],
		$datos_producto[7]
	);
echo json_encode( $resultado );
?>