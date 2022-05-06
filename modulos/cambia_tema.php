<?php  
if ( !defined("RAIZ") ) define("RAIZ", dirname(__FILE__));
$tema = $_POST["tema"];
if ($tema >= 1 and $tema <=16) {
	file_put_contents(RAIZ . "/tema.txt", $tema);
}
?>