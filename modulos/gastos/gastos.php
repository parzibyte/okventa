<?php  
function registrar_gasto( $importe, $concepto, $descripcion, $no_remision, $usuario )
{
	global $base_de_datos;
	$sentencia = $base_de_datos->prepare("insert into gastos 
		(importe, concepto, descripcion, numero_remision, fecha, usuario)
		values
		(?,?,?,?,?,?)");
	return $sentencia->execute( [$importe, $concepto, $descripcion, $no_remision, date("Y-m-d H:i:s"), $usuario] ) and registrar_gasto_caja( $importe, $usuario);
}


function consultar_todos_los_gastos($fecha_inicio, $fecha_fin)
{
	global $base_de_datos;
	$sentencia = $base_de_datos->prepare("select * from gastos where fecha > ? and fecha < ?;");
	$sentencia->execute([$fecha_inicio, $fecha_fin]);
	return $sentencia->fetchAll();
}


function registrar_gasto_caja( $gasto, $usuario )
{
	global $base_de_datos;
	$sentencia = $base_de_datos->prepare("insert into caja (caja_chica, ventas, gastos, fecha, no_venta, usuario) values (?,?,?,?,?,?)");
	return $sentencia->execute( [0,0, $gasto, date("Y-m-d H:i:s"), "null", $usuario] );
}
?>