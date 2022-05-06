<?php
if ( !isset( $_POST["datos_usuario"] ) ) exit();
if ( !defined( "RAIZ" ) ) 
{
	define( "RAIZ", dirname( dirname( dirname( __FILE__ ) ) ) );
}
$datos_usuario = $_POST["datos_usuario"];
$nombre = $datos_usuario[0];
$palabra_secreta = $datos_usuario[1];
require_once RAIZ . "/modulos/db.php";
require_once RAIZ . "/modulos/funciones.php";
require_once RAIZ . "/modulos/usuarios/usuarios.php";
$resultado = comprobar_datos($nombre, $palabra_secreta);
echo json_encode($resultado);
?>