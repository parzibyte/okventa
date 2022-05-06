<?php
if( !isset( $_POST["datos_gasto"] ) ) exit();
#Definimos la raíz del directorio
if ( !defined( "RAIZ" ) ) 
{
	define( "RAIZ", dirname( dirname( dirname( __FILE__ ) ) ) );
} 
require_once RAIZ . "/modulos/funciones.php";
require_once RAIZ . "/modulos/db.php";
require_once RAIZ . "/modulos/gastos/gastos.php";
$datos_gasto = $_POST["datos_gasto"];
inicia_sesion_segura();
$resultado = registrar_gasto($datos_gasto[0], $datos_gasto[1], $datos_gasto[2], $datos_gasto[3], $_SESSION["nombre_de_usuario"]);
echo json_encode($resultado);
?>