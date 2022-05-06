<?php
if( !isset( $_POST["cantidad"] ) ) exit();
#Definimos la raíz del directorio
if ( !defined( "RAIZ" ) ) 
{
	define( "RAIZ", dirname( dirname( dirname( __FILE__ ) ) ) );
}
$cantidad = $_POST["cantidad"];
require_once RAIZ . "/modulos/funciones.php";
require_once RAIZ . "/modulos/db.php";
require_once RAIZ . "/modulos/caja/caja.php";
inicia_sesion_segura();
$resultado = ingresar_dinero( $cantidad, $_SESSION["nombre_de_usuario"] );
echo json_encode($resultado);
?>