/***
 *     ____     ___  ____    ___   ____  ______    ___        __ __    ___  ____   ______   ____  _____
 *    |    \   /  _]|    \  /   \ |    \|      T  /  _]      |  T  |  /  _]|    \ |      T /    T/ ___/
 *    |  D  ) /  [_ |  o  )Y     Y|  D  )      | /  [_ _____ |  |  | /  [_ |  _  Y|      |Y  o  (   \_
 *    |    / Y    _]|   _/ |  O  ||    /l_j  l_jY    _]     ||  |  |Y    _]|  |  |l_j  l_j|     |\__  T
 *    |    \ |   [_ |  |   |     ||    \  |  |  |   [_l_____jl  :  !|   [_ |  |  |  |  |  |  _  |/  \ |
 *    |  .  Y|     T|  |   l     !|  .  Y |  |  |     T       \   / |     T|  |  |  |  |  |  |  |\    |
 *    l__j\_jl_____jl__j    \___/ l__j\_j l__j  l_____j        \_/  l_____jl__j__j  l__j  l__j__j \___j
 *
 *    Última modificación: 09 de junio de 2016 por Parzibyte
 *    Entorno: Desarrollo
 */
var un_dia_en_milisegundos = (24 * 60 * 60 * 1000),
    ayudante_total = 0,
    ayudante_utilidad = 0.0;
function Producto(numero, nombre) {
    this.numero = numero;
    this.nombre = nombre;
}

function Venta(numero_venta, fecha, nombre_producto, numero_productos, total, usuario, familia, utilidad) {
    this.numero_venta = numero_venta;
    this.fecha = fecha;
    this.lista_productos = [];
    this.numero_productos = parseFloat(numero_productos);
    this.total = total;
    this.lista_productos.push(new Producto(numero_productos, nombre_producto));
    this.usuario = usuario;
    this.familia = familia;
    this.utilidad = utilidad;
}

Venta.prototype.agrega_producto_lista = function (numero_productos, nombre_producto) {
    this.numero_productos += parseFloat(numero_productos);
    this.lista_productos.push(new Producto(numero_productos, nombre_producto));
};

Venta.prototype.productos_como_html = function () {
    var foo = "";
    for (var i = this.lista_productos.length - 1; i >= 0; i--) {
        foo +=
            this.lista_productos[i].nombre.toString()
            + ": <strong>"
            + this.lista_productos[i].numero.toString()
            + "</strong>"
            + "<br>";
    }
    return foo;
};

$(document).ready(function () {
    escuchar_elementos();
    poner_fechas();
    consulta_familias(function () {
        consulta_ventas_fecha($("#fecha_inicio").val(), $("#fecha_fin").val(), $("#familia").val());
    });
    $("li#elem_reportes").addClass("active");
});
function consulta_familias(callback) {
    $.get("./modulos/familias/consultar_familias.php", function (familias) {
        var f = JSON.parse(familias);
        llena_select_familias(f);
        callback();
    });
}
function fecha_justo_ahora() {
    var d = new Date($.now());
    var año = d.getFullYear();
    var mes_temporal = d.getMonth() + 1;
    var mes = (mes_temporal < 10) ? "0" + mes_temporal : mes_temporal;
    var dia = (d.getDate() < 10) ? "0" + d.getDate() : d.getDate();
    var hora = (d.getHours() < 10) ? "0" + d.getHours() : d.getHours();
    var minutos = (d.getMinutes() < 10) ? "0" + d.getMinutes() : d.getMinutes();
    return año + "-" + mes + "-" + dia + "T" + hora + ":" + minutos;
}


function fecha_justamente_mañana() {
    var d = new Date($.now() + un_dia_en_milisegundos);
    var año = d.getFullYear();
    var mes_temporal = d.getMonth() + 1;
    var mes = (mes_temporal < 10) ? "0" + mes_temporal : mes_temporal;
    var dia = (d.getDate() < 10) ? "0" + d.getDate() : d.getDate();
    var hora = (d.getHours() < 10) ? "0" + d.getHours() : d.getHours();
    var minutos = (d.getMinutes() < 10) ? "0" + d.getMinutes() : d.getMinutes();
    return año + "-" + mes + "-" + dia + "T" + hora + ":" + minutos;
}


function fecha_de_hoy() {
    var d = new Date($.now());
    var año = d.getFullYear();
    var mes_temporal = d.getMonth() + 1;
    var mes = (mes_temporal < 10) ? "0" + mes_temporal : mes_temporal;
    var dia = (d.getDate() < 10) ? "0" + d.getDate() : d.getDate();
    var hora = (d.getHours() < 10) ? "0" + d.getHours() : d.getHours();
    var minutos = (d.getMinutes() < 10) ? "0" + d.getMinutes() : d.getMinutes();
    return año + "-" + mes + "-" + dia + "T00:00";
}

function fecha_de_mañana() {
    var d = new Date($.now());
    var año = d.getFullYear();
    var mes_temporal = d.getMonth() + 1;
    var mes = (mes_temporal < 10) ? "0" + mes_temporal : mes_temporal;
    var dia = (d.getDate() < 10) ? "0" + d.getDate() : d.getDate();
    var hora = (d.getHours() < 10) ? "0" + d.getHours() : d.getHours();
    var minutos = (d.getMinutes() < 10) ? "0" + d.getMinutes() : d.getMinutes();
    return año + "-" + mes + "-" + dia + "T23:59";
}


function poner_fechas() {
    $("#fecha_inicio").val(fecha_de_hoy());
    $("#fecha_fin").val(fecha_de_mañana());
}
function escuchar_elementos() {
    $("#fecha_inicio, #fecha_fin, #familia").on("change", function () {
        consulta_ventas_fecha($("#fecha_inicio").val(), $("#fecha_fin").val(), $("#familia").val());
    });

    $("#generar_reporte").click(function () {
        window.print();
    });
}
function llena_select_familias(familias) {
    var $contenedorFamilia = $("#familia");
    $contenedorFamilia
        .empty()
        .append(
            $("<option>", {
                val: "*",
                text: "--Todas--"
            })
        );
    familias.forEach(function (familia) {
        $contenedorFamilia.append(
            $("<option>", {
                val: familia.familia,
                text: familia.familia
            })
        );
    });
}

function consulta_ventas_fecha(fecha_inicio, fecha_fin, familia) {
    $.post('./modulos/ventas/consultar_todas_las_ventas.php', {
        fecha_inicio: fecha_inicio,
        fecha_fin: fecha_fin,
        familia: familia
    }, function (respuesta) {
        respuesta = JSON.parse(respuesta);
        if (respuesta !== false) {
            consulta_ventas_por_familia(fecha_inicio, fecha_fin);
            dibuja_tabla_ventas(respuesta);
        } else {
            //Manejar error o respuesta
        }
    });
}
function dibuja_tabla_ventas_por_familia(ventas) {
    var $contenedorReportePorFamilia = $("#contenedor_tabla_por_familia"),
        total_por_familia = 0.0,
        utilidad_por_familia = 0.0;
    $contenedorReportePorFamilia.empty();
    ventas.forEach(function (a) {
            total_por_familia += parseFloat(a.total);
            utilidad_por_familia += parseFloat(a.utilidad);
            $contenedorReportePorFamilia
                .append(
                    '<tr>' +
                    '<td>' +
                    (a.familia === "" ? "--Sin familia--" : a.familia) +
                    '</td>' +
                    '<td> $' +
                    a.total +
                    '</td>' +
                    '<td>' +
                    '<strong>$' +
                    a.utilidad +
                    '</strong>' +
                    '</td>' +
                    '</tr>'
                );
        }
    );
    $contenedorReportePorFamilia
        .append(
            '<tr class="success">' +
            '<td>' +
            'Totales' +
            '</td>' +
            '<td> $' +
            total_por_familia +
            '</td>' +
            '<td>' +
            '<strong>$' +
            utilidad_por_familia +
            '</strong>' +
            '</td>' +
            '</tr>'
        );
}
function consulta_ventas_por_familia(fecha_inicio, fecha_fin) {
    $.post('./modulos/ventas/consultar_todas_las_ventas_por_familia.php', {
        fecha_inicio: fecha_inicio,
        fecha_fin: fecha_fin
    }, function (respuesta) {
        respuesta = JSON.parse(respuesta);
        if (respuesta !== false) {
            dibuja_tabla_ventas_por_familia(respuesta);
        } else {
            //Manejar error o respuesta
        }
    });
}
function dame_posicion_venta(ventas, numero_venta) {
    for (var i = ventas.length - 1; i >= 0; i--) {
        if (ventas[i].numero_venta === numero_venta) return i;
    }
    return -1;
}

function dibuja_tabla_ventas(ventas) {
    $("#mostrar_total").text("").parent().hide();
    $("#generar_reporte").hide();
    $("#contenedor_tabla")
        .empty();
    if (ventas.length <= 0) return;
    ayudante_total = 0;
    ayudante_utilidad = 0;
    $("#contenedor_tabla")
        .append(
            $("<table>")
                .addClass('table table-bordered table-striped table-hover table-condensed')
                .append(
                    $("<thead>")
                        .append(
                            $("<tr>")
                                .append(
                                    $("<th>")
                                        .html('Número de venta'),

                                    $("<th>")
                                        .html('Fecha'),

                                    $("<th>")
                                        .html('Productos'),

                                    $("<th>")
                                        .html('Número de productos'),

                                    $("<th>")
                                        .html('Total'),

                                    $("<th>")
                                        .html('Utilidad'),

                                    $("<th>")
                                        .html('Usuario')
                                )
                        )
                )
                .append(
                    $("<tbody>")
                )
        );
    var ayudante_numero_venta = undefined,
        numero_productos = 0.0;
    var ventas_totales = [];
    var subtotal = 0,
        subtotal_utilidad = 0;
    for (var i = ventas.length - 1; i >= 0; i--) {
        subtotal = ventas[i].total;
        subtotal_utilidad = ventas[i].utilidad;
        if (ayudante_numero_venta === ventas[i].numero_venta) {
            var posicion = dame_posicion_venta(ventas_totales, ventas[i].numero_venta);
            ventas_totales[posicion].agrega_producto_lista(ventas[i].numero_productos, ventas[i].nombre_producto);
            ventas_totales[posicion].total = parseFloat(ventas_totales[posicion].total) + parseFloat(subtotal);
            ventas_totales[posicion].utilidad = parseFloat(ventas_totales[posicion].utilidad) + parseFloat(subtotal_utilidad);
            numero_productos += parseFloat(ventas[i].numero_productos);
        } else {
            ventas_totales.push(
                new Venta(
                    ventas[i].numero_venta,
                    ventas[i].fecha,
                    ventas[i].nombre_producto,
                    parseFloat(ventas[i].numero_productos),
                    subtotal,
                    ventas[i].usuario,
                    ventas[i].familia,
                    subtotal_utilidad
                )
            );
            ayudante_numero_venta = ventas[i].numero_venta;
        }
    }
    for (var i = ventas_totales.length - 1; i >= 0; i--) {
        ayudante_total += parseFloat(ventas_totales[i].total);
        ayudante_utilidad += parseFloat(ventas_totales[i].utilidad);
        $("#contenedor_tabla tbody")
            .append(
                $("<tr>")
                    .append(
                        $("<td>").html(ventas_totales[i].numero_venta),
                        $("<td>").html(ventas_totales[i].fecha),
                        $("<td>").html(ventas_totales[i].productos_como_html()),
                        $("<td>").html(ventas_totales[i].numero_productos),
                        $("<td>").html("$" + ventas_totales[i].total),
                        $("<td>").html("$" + ventas_totales[i].utilidad),
                        $("<td>").html(ventas_totales[i].usuario)
                    )
            );
    }
    $("#contenedor_tabla").animateCss("fadeInUp");
    $("#mostrar_total").text(ayudante_total).parent().show();
    $("#mostrar_utilidad").text(ayudante_utilidad).parent().show();
    $("#generar_reporte").show();
}