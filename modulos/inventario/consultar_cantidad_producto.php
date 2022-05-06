<?php
if( !isset( $_POST["rowid"] ) ) exit();
#Definimos la raíz del directorio
if ( !defined( "RAIZ" ) ) 
{
	define( "RAIZ", dirname( dirname( dirname( __FILE__ ) ) ) );
}
require_once RAIZ . "/modulos/db.php";
require_once RAIZ . "/modulos/inventario/inventario.php";
$rowid = $_POST["rowid"];
$cantidad = consultar_piezas_producto( $rowid );
echo json_encode($cantidad);
?>