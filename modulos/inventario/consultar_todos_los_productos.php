<?php  
if( !isset( $_POST["limite"] ) ) exit();
#Definimos la raíz del directorio
if ( !defined( "RAIZ" ) ) 
{
	define( "RAIZ", dirname( dirname( dirname( __FILE__ ) ) ) );
}
require_once RAIZ . "/modulos/db.php";
require_once RAIZ . "/modulos/inventario/inventario.php";
$limite = $_POST["limite"];
$offset = $_POST["offset"];
$todos_los_productos = consultar_todos_los_productos( $limite, $offset );
echo json_encode( $todos_los_productos );
?>