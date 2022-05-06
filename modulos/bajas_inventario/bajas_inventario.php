<?php  
function consultar_todas_las_bajas( $fecha_inicio, $fecha_fin )
{
	global $base_de_datos;
	$sentencia = $base_de_datos->prepare("select * from bajas_inventario where fecha > ? and fecha < ?;");
	$sentencia->execute( [$fecha_inicio, $fecha_fin] );
	return $sentencia->fetchAll();
}

?>