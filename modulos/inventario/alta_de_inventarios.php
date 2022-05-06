<?php
/**
 * Created by PhpStorm.
 * User: parzibyte
 * Date: 05/08/2016
 * Time: 12:17 PM
 */
if (!isset($_POST["productos"])) exit();
if (!defined("RAIZ")) define("RAIZ", dirname(dirname(dirname(__FILE__))));
require_once RAIZ . "/modulos/db.php";
require_once RAIZ . "/modulos/inventario/inventario.php";
$productos = json_decode($_POST["productos"]);
$resultado = dar_alta_productos($productos);
echo json_encode($resultado);