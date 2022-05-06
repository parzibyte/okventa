<?php  
if( !isset( $_POST["nombre_de_usuario"] ) ) exit();
#Definimos la raíz del directorio
if ( !defined( "RAIZ" ) ) define( "RAIZ", dirname( dirname( dirname( __FILE__ ) ) ) );
include RAIZ . "/modulos/db.php";
include RAIZ . "/modulos/usuarios/usuarios.php";
$nombre_de_usuario = $_POST["nombre_de_usuario"];
$resultado = usuario_existe( $nombre_de_usuario );
echo json_encode($resultado);
?>