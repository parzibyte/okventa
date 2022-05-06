/***
 *     ____     ___  ____    ___   ____  ______    ___           __   ____  ____   ____ 
 *    |    \   /  _]|    \  /   \ |    \|      T  /  _]         /  ] /    T|    | /    T
 *    |  D  ) /  [_ |  o  )Y     Y|  D  )      | /  [_ _____   /  / Y  o  |l__  |Y  o  |
 *    |    / Y    _]|   _/ |  O  ||    /l_j  l_jY    _]     | /  /  |     |__j  ||     |
 *    |    \ |   [_ |  |   |     ||    \  |  |  |   [_l_____j/   \_ |  _  /  |  ||  _  |
 *    |  .  Y|     T|  |   l     !|  .  Y |  |  |     T      \     ||  |  \  `  ||  |  |
 *    l__j\_jl_____jl__j    \___/ l__j\_j l__j  l_____j       \____jl__j__j\____jl__j__j
 *
 *    Última modificación: 09 de junio de 2016 por Parzibyte
 *    Entorno: Desarrollo                                                                                                                       
 */
$(document).ready(function() {
	escuchar_elementos();
	poner_fechas();
	consulta_caja_fecha( $("#fecha_inicio").val(), $("#fecha_fin").val() );
	$("li#elem_reportes").addClass("active");
});


function escuchar_elementos(){
	$("#fecha_inicio, #fecha_fin").on("change", function(){
		consulta_caja_fecha( $("#fecha_inicio").val(), $("#fecha_fin").val() );
	});

	$("#generar_reporte").click(function(){
		window.print();
	});
}


function dibuja_tabla_caja(datos){
	$( "#caja_chica, #gastos, #ventas, #total_caja" ).text("").parent().hide();
	$( "#generar_reporte" ).hide();
	$( "#contenedor_tabla" )
	.empty();
	if (datos.length <= 0) return;
	ayudante_total = 0;
	$( "#contenedor_tabla" )
	.append(
		$( "<table>" )
		.addClass( 'table table-striped table-bordered table-hover table-condensed' )
		.append(
			$( "<thead>" )
			.append(
				$( "<tr>" )
				.append(
					$( "<th>" )
					.html('Caja chica'),
					
					$( "<th>" )
					.html('Ventas'),
					
					$( "<th>" )
					.html('Gastos'),
					
					$( "<th>" )
					.html('Fecha'),
					
					$( "<th>" )
					.html('Número de venta'),
					
					$( "<th>" )
					.html('Usuario')
				)
			)
		)
		.append(
			$( "<tbody>" )
		)
	);
	var ayudante_ventas = 0,
		ayudante_gastos = 0,
		ayudante_caja_chica = 0,
		ayudante_total = 0;
	for (var i = datos.length - 1; i >= 0; i--) {
		ayudante_ventas += parseFloat( datos[i].ventas );
		ayudante_gastos += parseFloat( datos[i].gastos );
		ayudante_caja_chica += parseFloat( datos[i].caja_chica );
		$( "#contenedor_tabla tbody" )
		.append( 
			$("<tr>")
			.append(
				$("<td>").html( (datos[i].caja_chica == 0 ) ? "No aplica" : "$" + datos[i].caja_chica ),
				$("<td>").html( (datos[i].ventas == 0) ? "No aplica" : "$" + datos[i].ventas ),
				$("<td>").html( (datos[i].gastos == 0) ? "No aplica" : datos[i].gastos ),
				$("<td>").html(datos[i].fecha),
				$("<td>").html( (datos[i].no_venta === "null") ? "No aplica" : datos[i].no_venta ),
				$("<td>").html(datos[i].usuario)
			)
		);
	}
	ayudante_total = (ayudante_ventas + ayudante_caja_chica) - ayudante_gastos;
	ayudante_total = Math.round(ayudante_total * 100) / 100;
	ayudante_caja_chica = Math.round( ayudante_caja_chica * 100 ) / 100;
	ayudante_gastos = Math.round( ayudante_gastos * 100 ) / 100;
	ayudante_ventas = Math.round( ayudante_ventas * 100 ) / 100;
	var ayudante_utilidad = ayudante_ventas - ayudante_gastos;
	ayudante_utilidad = Math.round( ayudante_utilidad *100 ) / 100;
	console.log('ayudante_total ' , ayudante_total);
	$("#caja_chica").text(ayudante_caja_chica).parent().show();
	$("#gastos").text(ayudante_gastos).parent().show();
	$("#ventas").text(ayudante_ventas).parent().show();
	$("#total_caja").text(ayudante_total).parent().show();
	$("#utilidad").text(ayudante_utilidad).parent().show();
	$( "#generar_reporte" ).show();
	return;
}


function consulta_caja_fecha(fecha_inicio, fecha_fin){
	$.post('./modulos/caja/consultar_caja_fecha.php', {fecha_inicio: fecha_inicio, fecha_fin: fecha_fin}, function(respuesta) {
		respuesta = JSON.parse(respuesta);
		console.log('respuesta ' , respuesta);
		if (respuesta !== false) {
			dibuja_tabla_caja(respuesta);
		}else{
			//Manejar error o respuesta
		}
	});
}


function fecha_de_hoy(){
	var d = new Date( $.now() );
	var año = d.getFullYear();
	var mes_temporal = d.getMonth() + 1;
	var mes = (mes_temporal < 10) ? "0" + mes_temporal : mes_temporal;
	var dia = (d.getDate() < 10) ? "0" + d.getDate() : d.getDate();
	var hora = (d.getHours() < 10) ? "0" + d.getHours() : d.getHours();
	var minutos = (d.getMinutes() < 10) ? "0" + d.getMinutes() : d.getMinutes();
	return año + "-" + mes + "-" + dia + "T00:00";
}


function fecha_de_mañana(){
	var d = new Date( $.now() );
	var año = d.getFullYear();
	var mes_temporal = d.getMonth() + 1;
	var mes = (mes_temporal < 10) ? "0" + mes_temporal : mes_temporal;
	var dia = (d.getDate() < 10) ? "0" + d.getDate() : d.getDate();
	var hora = (d.getHours() < 10) ? "0" + d.getHours() : d.getHours();
	var minutos = (d.getMinutes() < 10) ? "0" + d.getMinutes() : d.getMinutes();
	return año + "-" + mes + "-" + dia + "T23:59";
}


function poner_fechas(){
	$("#fecha_inicio").val( fecha_de_hoy() );
	$("#fecha_fin").val( fecha_de_mañana() );
}