<?php
if ( !defined("RAIZ") ) define("RAIZ", dirname(__FILE__));
require_once RAIZ . '/funciones.php';
echo json_encode( devuelve_datos_empresa() );
?>