<?php
/**
 * Created by PhpStorm.
 * User: parzibyte
 * Date: 22/09/2016
 * Time: 04:49 PM
 */
$foo = dirname(__DIR__) . "/modulos/inventario.csv";
echo $foo;
$bar = file($foo);
echo "<pre>";
var_dump($bar);