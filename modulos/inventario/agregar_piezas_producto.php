<?php
if( !isset( $_POST["numero_piezas"] ) ) exit();
#Definimos la raíz del directorio
if ( !defined( "RAIZ" ) ) 
{
	define( "RAIZ", dirname( dirname( dirname( __FILE__ ) ) ) );
}
require_once RAIZ . "/modulos/db.php";
require_once RAIZ . "/modulos/inventario/inventario.php";
$numero_piezas = $_POST["numero_piezas"];
$id_producto = $_POST["id_producto"];
$resultado = agregar_piezas( $numero_piezas, $id_producto );
echo json_encode( $resultado );
?>