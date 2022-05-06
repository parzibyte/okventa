<?php
#Definimos la raíz del directorio
if ( !defined( "RAIZ" ) ) 
{
    define( "RAIZ", dirname( dirname( dirname( __FILE__ ) ) ) );
}
require_once RAIZ . '/modulos/funciones.php';
cierra_sesion_segura();
header("Location: ../../");
?>