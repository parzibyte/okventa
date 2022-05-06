<?php
if( !isset( $_POST["rowid"] ) ) exit();
#Definimos la raíz del directorio
if ( !defined( "RAIZ" ) ) define( "RAIZ", dirname( dirname( dirname( __FILE__ ) ) ) );
include RAIZ . "/modulos/db.php";
include RAIZ . "/modulos/usuarios/usuarios.php";
include RAIZ . "/modulos/funciones.php";
inicia_sesion_segura();
if ( $_SESSION["administrador"] !== 1)
{
	echo json_encode( "Tú no eres administrador." );
	exit();
}
$rowid = $_POST["rowid"];
$resultado = eliminar_usuario( $rowid );
echo json_encode( $resultado );
?>