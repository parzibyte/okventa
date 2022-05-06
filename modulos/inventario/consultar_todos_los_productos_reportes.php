<?php
if( !isset( $_POST["filtro"] ) ) exit();
#Definimos la raíz del directorio
if ( !defined( "RAIZ" ) ) 
{
	define( "RAIZ", dirname( dirname( dirname( __FILE__ ) ) ) );
}
require_once RAIZ . "/modulos/db.php";
require_once RAIZ . "/modulos/inventario/inventario.php";
$filtro = $_POST["filtro"];
$orden = $_POST["orden"];
$todos_los_productos = consultar_todos_los_productos_reportes($filtro, $orden );
echo json_encode( $todos_los_productos );
?>