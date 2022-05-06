/***
 *     ____     ___  ____    ___   ____  ______    ___         ____   ____  _____ ______   ___    _____
 *    |    \   /  _]|    \  /   \ |    \|      T  /  _]       /    T /    T/ ___/|      T /   \  / ___/
 *    |  D  ) /  [_ |  o  )Y     Y|  D  )      | /  [_ _____ Y   __jY  o  (   \_ |      |Y     Y(   \_ 
 *    |    / Y    _]|   _/ |  O  ||    /l_j  l_jY    _]     ||  T  ||     |\__  Tl_j  l_j|  O  | \__  T
 *    |    \ |   [_ |  |   |     ||    \  |  |  |   [_l_____j|  l_ ||  _  |/  \ |  |  |  |     | /  \ |
 *    |  .  Y|     T|  |   l     !|  .  Y |  |  |     T      |     ||  |  |\    |  |  |  l     ! \    |
 *    l__j\_jl_____jl__j    \___/ l__j\_j l__j  l_____j      l___,_jl__j__j \___j  l__j   \___/   \___j
 *  
 *    Última modificación: 09 de junio de 2016 por Parzibyte
 *    Entorno: Desarrollo                                                                                                         
 */
$(document).ready(function() {
	escuchar_elementos();
	poner_fechas();
	poner_usuarios();
	consulta_bajas( $("#fecha_inicio").val(), $("#fecha_fin").val() );
	$("li#elem_reportes").addClass("active");
});

function poner_usuarios(){
	$.post('./modulos/usuarios/consultar_nombres_usuarios.php', function( respuesta ) {
		respuesta = JSON.parse( respuesta );
		for (var i = respuesta.length - 1; i >= 0; i--) {
			$("#usuarios").append( 
				$("<option>").val(respuesta[i].nombre).text(respuesta[i].nombre) 
				);
		}
	});
}

function escuchar_elementos(){
	$("#fecha_inicio, #fecha_fin").on("change", function(){
		consulta_bajas( $("#fecha_inicio").val(), $("#fecha_fin").val() );
	});

	$("#generar_reporte").click(function(){
		window.print();
	});
}

function dibuja_tabla_bajas_inventario(datos){
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
					.html('Código del producto'),
					
					$( "<th>" )
					.html('Nombre del producto'),
					
					$( "<th>" )
					.html('Cantidad'),
					
					$( "<th>" )
					.html('Razón de baja'),
					
					$( "<th>" )
					.html('Usuario'),
					
					$( "<th>" )
					.html('Fecha')
				)
			)
		)
		.append(
			$( "<tbody>" )
		)
	);
	for (var i = datos.length - 1; i >= 0; i--) {
		$( "#contenedor_tabla tbody" )
		.append( 
			$("<tr>")
			.append(
				$("<td>").html( datos[i].codigo_producto ),
				$("<td>").html( datos[i].nombre_producto ),
				$("<td>").html( datos[i].numero_piezas ),
				$("<td>").html( datos[i].razon_baja ),
				$("<td>").html( datos[i].usuario ),
				$("<td>").html( datos[i].fecha )
			)
		);
	}
	$( "#generar_reporte" ).show();
	return;
}

function consulta_bajas(fecha_inicio, fecha_fin){
	$.post('./modulos/bajas_inventario/consultar_bajas_inventario.php', {fecha_inicio: fecha_inicio, fecha_fin: fecha_fin}, function(respuesta) {
		respuesta = JSON.parse(respuesta);
		if (respuesta !== false) {
			dibuja_tabla_bajas_inventario( respuesta );
		}else{
			console.log("Error en la respuesta");
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