<?php
#Definimos la raíz del directorio
if ( !defined( "RAIZ" ) )define( "RAIZ", dirname( dirname( dirname( __FILE__ ) ) ) );
require_once RAIZ . "/modulos/db.php";
require_once RAIZ . "/modulos/funciones.php";
require_once RAIZ . "/modulos/inventario/inventario.php";
inicia_sesion_segura();
if ($_SESSION["administrador"] !== 1) {
    echo json_encode("Restringido");
    exit();
}
$valor_del_inventario = consultar_valor_del_inventario();
echo json_encode( $valor_del_inventario );
?>