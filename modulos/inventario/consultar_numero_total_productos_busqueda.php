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
$cantidad = consultar_numero_total_productos_busqueda( $busqueda );
echo json_encode( $cantidad );
?>