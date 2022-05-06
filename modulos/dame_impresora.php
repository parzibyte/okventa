<?php
if ( !defined("RAIZ") ) define("RAIZ", dirname(__FILE__));
echo json_encode( file_get_contents( RAIZ . "/impresora.ini" ) );
?>