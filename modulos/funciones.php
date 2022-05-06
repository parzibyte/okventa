<?php
if (!defined("RAIZ")) define("RAIZ", dirname(__FILE__));
function sesion_esta_iniciada()
{
	return session_status() === PHP_SESSION_ACTIVE ? TRUE : FALSE;
}


function inicia_sesion_segura()
{
    if (sesion_esta_iniciada() === FALSE ) {
        session_start();
        session_regenerate_id(true);
    }
}

function cierra_sesion_segura()
{
	if (session_status() !== PHP_SESSION_ACTIVE) {
		
	session_start();
    session_destroy();
	}
}


function establece_datos_empresa($nombre, $telefono, $rfc, $direccion, $colonia, $cp, $ciudad)
{
    $cadena =
        $nombre . PHP_EOL .
        $telefono . PHP_EOL .
        $rfc . PHP_EOL .
        $direccion . PHP_EOL .
        $colonia . PHP_EOL .
        $cp . PHP_EOL .
        $ciudad . PHP_EOL;
    $correcto = file_put_contents(RAIZ . "/datos_empresa.txt", $cadena);
    return ($correcto === FALSE) ? FALSE : TRUE;
}

function devuelve_datos_empresa()
{
    return file(RAIZ . "/datos_empresa.txt");
}

?>