<?php
if ( !isset( $_POST["codigo"] ) ) exit();
if ( !defined( "RAIZ" ) ) 
{
	define( "RAIZ", dirname( dirname( dirname( __FILE__ ) ) ) );
}
$codigo = $_POST["codigo"];
include "../variables.php";
include "../base_de_datos.php";
include "./ventas.php";
$resultado = dame_producto( $codigo );
echo json_encode( $resultado and true);
?>