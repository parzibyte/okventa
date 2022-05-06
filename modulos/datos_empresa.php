<?php
if ( !defined("RAIZ") ) define("RAIZ", dirname(__FILE__));
if ( !isset( $_POST["datos_empresa"] ) ) exit();
$datos_empresa = $_POST["datos_empresa"];
require_once RAIZ . '/funciones.php';
$resultado = establece_datos_empresa(
	$datos_empresa[0],
	$datos_empresa[1],
	$datos_empresa[2],
	$datos_empresa[3],
	$datos_empresa[4],
	$datos_empresa[5],
	$datos_empresa[6]
	);
echo json_encode( $resultado );
?>