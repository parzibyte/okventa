<?php


function consultar_movimientos( $fecha_inicio, $fecha_fin )
{
	global $base_de_datos;
	$sentencia = $base_de_datos->prepare( "select * from caja where fecha > ? and fecha < ?;" );
	$sentencia->execute( [$fecha_inicio, $fecha_fin] );
	return $sentencia->fetchAll();
}


function ingresar_dinero( $cantidad, $usuario )
{
	global $base_de_datos;
	$sentencia = $base_de_datos->prepare("insert into caja 
		(caja_chica, ventas, gastos, fecha, no_venta, usuario)
		values
		(?,?,?,?,?,?)");
	$usuario = $_SESSION["nombre_de_usuario"];
	return $sentencia->execute([$cantidad, 0, 0, date("Y-m-d H:i:s") ,"null", $usuario]);
}
?>