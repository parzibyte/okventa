<?php
if( !isset( $_POST["rowid"] ) ) exit();
#Definimos la raíz del directorio
if ( !defined( "RAIZ" ) ) 
{
    define( "RAIZ", dirname( dirname( dirname( __FILE__ ) ) ) );
}

$rowid = $_POST["rowid"];
require_once RAIZ . "/modulos/db.php";
require_once RAIZ . "/modulos/funciones.php";
require_once RAIZ . "/modulos/inventario/inventario.php";
inicia_sesion_segura();
if ($_SESSION["administrador"] !== 1) {
    echo json_encode("Tú no eres administrador");
    exit();
}
$resultado = eliminar_producto( $rowid );
echo json_encode($resultado);
?>