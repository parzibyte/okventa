<?php
if ( !isset( $_GET["busqueda"] ) ) exit();
if ( !defined( "RAIZ" ) ) 
{
	define( "RAIZ", dirname( dirname( dirname( __FILE__ ) ) ) );
}
$busqueda = $_GET["busqueda"];
require_once RAIZ . "/modulos/db.php";
require_once RAIZ . "/modulos/ventas/ventas.php";
$datos = devolver_datos_autocompletado( $busqueda );
echo json_encode( $datos );
?>