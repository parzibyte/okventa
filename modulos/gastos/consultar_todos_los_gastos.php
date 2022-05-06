<?php
if( !isset( $_POST["fecha_inicio"] ) ) exit();
#Definimos la raíz del directorio
if ( !defined( "RAIZ" ) ) 
{
	define( "RAIZ", dirname( dirname( dirname( __FILE__ ) ) ) );
} 
$fecha_inicio = $_POST["fecha_inicio"];
$fecha_fin = $_POST["fecha_fin"];
require_once RAIZ . "/modulos/db.php";
require_once RAIZ . "/modulos/gastos/gastos.php";
$todos_los_gastos = consultar_todos_los_gastos( $fecha_inicio, $fecha_fin );
echo json_encode( $todos_los_gastos );
?>