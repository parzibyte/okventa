<?php
if( !isset( $_POST["nombre_de_usuario"] ) ) exit();
#Definimos la raíz del directorio
if ( !defined( "RAIZ" ) ) define( "RAIZ", dirname( dirname( dirname( __FILE__ ) ) ) );
include RAIZ . "/modulos/db.php";
include RAIZ . "/modulos/usuarios/usuarios.php";
include RAIZ . "/modulos/funciones.php";
inicia_sesion_segura();
if ( intval( $_SESSION["administrador"] ) !== 1)
{
	echo json_encode( "Tú no eres administrador." );
	exit();
}
$nombre_de_usuario = json_decode( $_POST["nombre_de_usuario"] );
$nombre_de_usuario = strtolower( $nombre_de_usuario );
$palabra_secreta = json_decode( $_POST["palabra_secreta"] );
$es_administrador = json_decode( $_POST["es_administrador"] );
if ( preg_match( "/^[_a-z][a-z0-9_\.]{3,11}$/", $nombre_de_usuario ) !== 1 ) 
{
	echo json_encode( "El usuario no es válido." );
	exit();
}
if ( preg_match( "/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,12}$/", $palabra_secreta ) !== 1 ) 
{
	echo json_encode( "La contraseña no es válida." );
	exit();
}
$resultado = insertar_usuario($nombre_de_usuario, $palabra_secreta, $es_administrador);
echo json_encode( $resultado );
?>