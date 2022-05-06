<?php

error_reporting(0); #Desactivamos el reporte de errores. 
/*******************************
 * Nombre del archivo: index.php
 * Autor: parzibyte
 * Última modificación: 14 de septiembre del 2016 por parzibyte
 * Este archivo es el principal; es el índice.
 * Desde aquí se incluyen los demás archivos (ventas, inventarios, etcétera)
 * Para que funcione bien, el archivo .htaccess debe estar configurado correctamente
 *******************************/

#Definimos la raíz del directorio
if (!defined("RAIZ")) define("RAIZ", dirname(__FILE__));
if (!defined("MINUTOS_EXPIRACION_SESION")) define("MINUTOS_EXPIRACION_SESION", 24 * 60);

include_once RAIZ . "/modulos/funciones.php"; #Este archivo trae consigo algunas funciones que nos ayudan
inicia_sesion_segura(); #Iniciamos la sesión para ocupar sus datos
include_once RAIZ . "/inc/encabezado.php";
#Comprobamos si el usuario ha iniciado sesión. En caso de que no, mostramos el formulario de login.
if (!file_exists(RAIZ . "/config.php")) {
    if (isset($_SESSION["hora_de_inicio"]) and ((time() - $_SESSION["hora_de_inicio"]) / 60) <= MINUTOS_EXPIRACION_SESION) {
        include_once RAIZ . "/inc/navbar.php";
        include_once RAIZ . "/modulos/funciones.php"; #Aquí tenemos nuestros estilos, título del documento, etcétera

        #La lista blanca es para evitar ataques. Si en el futuro se añade una opción, aquí se tiene que agregar
        $lista_blanca = array("pasar-inventario", "alta-de-inventarios", "caja", "clientes", "compras", "inventarios", "ventas", "reportes-inventarios", "reportes", "reportes-caja", "reportes-ventas", "gastos", "ajustes", "reportes-gastos", "reportes-bajas-inventario", "usuarios", "productos-en-stock");

        #Comprobamos si nos están pidiendo una página, en caso de que no, los redireccionamos a "ventas"
        if (isset($_GET["pagina"])) {

            $pagina = $_GET["pagina"];

            #Comprobamos si la página requerida está dentro de nuestra lista blanca definida arriba
            if (in_array($pagina, $lista_blanca)) {
                include_once RAIZ . "/inc/" . $pagina . ".php"; #En caso de que lo esté, incluimos el archivo requerido
            } else {
                #En caso de que no, simplemente le decimos que no puede pasar y compruebe su ortografía
                echo
                <<<EOD
			<div class="row">
				<div class="well">
					<strong>Error</strong>: La ruta que escribiste no está permitida. Por favor haz clic en un elemento del menú que sea válido y no escribas la dirección manualmente. También puedes verificar tu ortografía para ver si no lo hiciste de una forma equivocada.
				</div>
				<br>
				<div class="col-xs-12 text-center">
					<img class="img img-responsive img-thumbnail" src="http://img03.deviantart.net/f059/i/2010/184/f/2/you_shall_not_pass_by_twilight_05.jpg">
				<br>
				</div>
			</div>
EOD;
            }
        } else {
            include_once RAIZ . "/inc/main.php";
        }


    } else {
        cierra_sesion_segura();
        include_once RAIZ . "/inc/login.php";
    }
} else {
    include_once RAIZ . "/config.php";
}
include_once RAIZ . "/inc/pie.php"; #En todos los casos incluimos el pie de página
?>
