<?php
if ( !defined( "RAIZ" ) ) 
{
	define( "RAIZ", dirname( dirname( dirname( __FILE__ ) ) ) );
}
require_once RAIZ . "/modulos/db.php";
require_once RAIZ . "/modulos/usuarios/usuarios.php";
$todos_los_usuarios = consultar_todos_los_usuarios();
echo json_encode( $todos_los_usuarios );
?>