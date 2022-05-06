var productos = [],
    ayudante_posicion = 0;
$contenedor_productos = $("#contenedor_tabla");
function Producto(rowid, codigo, nombre, cantidad, posicion) {
    this.rowid = rowid;
    this.codigo = codigo;
    this.nombre = nombre;
    this.cantidad = cantidad;
    this.posicion = posicion;
}
Producto.prototype = {
    setCantidad: function (cantidad) {
        this.cantidad = parseFloat(cantidad);
    },
    aumentaCantidad: function () {
        this.cantidad += 1;
    }

};
function dame_posicion_producto(posicion) {
    for (var x = 0; x < productos.length; x++) {
        if (productos[x].posicion === posicion) return x;
    }
    return -1;
}

function quita_producto_local(posicion) {
    var indice = dame_posicion_producto(posicion);
    if (indice !== -1) {
        productos.splice(indice, 1);
        dibujar_productos();
        $("#codigo_producto").focus();
    }
}

function preparar_nueva_alta() {
    $("#codigo_producto").focus();
    productos.length = 0;
    productos = [];
    dibujar_productos();
}

function mostrar_error_de_alta(error) {
    new PNotify({
        title: "Error",
        text: "Error inesperado. El error es: " + error,
        type: "error",
        delay: 2000
    });
}

function escuchar_elementos() {

    $("#terminar_alta").click(function () {
        if (productos.length <= 0) return;
        PNotify.removeAll();
        var p = new PNotify({
            title: "Cargando",
            text: "Realizando alta de inventarios...",
            hide: false,
            icon: "fa fa-spin fa-cog",
            type: "warning"
        });
        $.post("./modulos/inventario/alta_de_inventarios.php", {
            productos: JSON.stringify(productos)

        }, function (datos) {
            try {
                var respuesta_decodificada = JSON.parse(datos);
                if (respuesta_decodificada === true) {
                    new PNotify({
                        title: "Correcto",
                        text: "Alta de inventarios correcta",
                        type: "success",
                        delay: 1000
                    });
                    preparar_nueva_alta();
                } else {
                    throw new Error("Respuesta inesperada: " + respuesta_decodificada);
                }
            } catch (e) {
                mostrar_error_de_alta(e, datos);
            } finally {
                p.remove();
            }
        });
    });

    $(document).on("keyup", ".modificar-cantidad", function (evento) {
        $(this).parent().removeClass('has-error');
        if (evento.keyCode === 13) {
            var nueva_cantidad = $(this).val(),
                posicion = dame_posicion_producto($(this).data("pos"));
            if (
                nueva_cantidad.length > 0
                && nueva_cantidad > 0
                && !isNaN(nueva_cantidad)
            ) {
                productos[posicion].setCantidad(nueva_cantidad);
                dibujar_productos();
                $("#codigo_producto").focus();
            } else {
                $(this).animateCss('shake');
                $(this).parent().addClass('has-error');
            }
        }

    });

    $(document).on("click", ".quitar-producto", function () {
        var posicion = $(this).data("pos");
        quita_producto_local(posicion);
    });

    $("#codigo_producto").keydown(function (evento) {
        switch (evento.keyCode) {
            case 13:
                comprueba_si_existe_codigo($(this).val());
                break;
        }
    });
}


function dibujar_productos() {
    if (productos.length <= 0) {
        $contenedor_productos
            .empty()
            .html(
                $("<h2>")
                    .addClass('text-center')
                    .html('Aquí aparecerán los productos que agregues<br><i class = "fa fa-4x fa-shopping-basket"></i>')
            );
        $("#contenedor_total").parent().hide();
        return;
    }

    $contenedor_productos
        .empty()
        .append(
            $("<table>")
                .addClass('table table-striped table-hover table-condensed')
                .append(
                    $("<thead>")
                        .append(
                            $("<tr>")
                                .append(
                                    $("<th>")
                                        .html('Código'),

                                    $("<th>")
                                        .html('Producto'),

                                    $("<th>")
                                        .html('Cantidad'),

                                    $("<th>")
                                        .html('Quitar')
                                )
                        )
                )
                .append(
                    $("<tbody>")
                )
        );
    for (var i = productos.length - 1; i >= 0; i--) {
        $("#contenedor_tabla tbody")
            .append(
                $("<tr>")
                    .append(
                        $("<td>")
                            .html(productos[i].codigo),

                        $("<td>")
                            .html(productos[i].nombre),

                        $("<td>")
                            .html(
                                $("<div>")
                                    .addClass('form-group')
                                    .html(
                                        $("<input>")
                                            .attr("placeholder", "Cantidad")
                                            .attr("type", "number")
                                            .attr("data-pos", productos[i].posicion)
                                            .addClass('form-control modificar-cantidad')
                                            .val(productos[i].cantidad)
                                    )
                            ),


                        $("<td>")
                            .html(
                                $("<button>")
                                    .addClass('btn btn-danger quitar-producto')
                                    .attr("data-pos", productos[i].posicion)
                                    .html(
                                        $("<i>")
                                            .addClass('fa fa-trash')
                                    )
                            )
                    )
            );
    }
}
$(principal());


function principal() {
    $("li#elem_alta_inventarios").addClass("active");
    autocompletado_input();
    dibujar_productos();
    escuchar_elementos();
    $("#codigo_producto").focus();
}
function producto_ya_esta_en_lista(codigo) {
    for (var x = 0; x < productos.length; x++) {
        if (productos[x].codigo === codigo) {
            productos[x].aumentaCantidad();
            return true;
        }
    }
    return false;
}

function agrega_producto_local(producto) {
    var ya_esta_en_la_lista = producto_ya_esta_en_lista(producto.codigo);
    if (ya_esta_en_la_lista !== true) {
        var producto = new
            Producto(
            producto.rowid,
            producto.codigo,
            producto.nombre,
            1,
            ayudante_posicion
        );
        productos.push(producto);
        ayudante_posicion++;
    }
    dibujar_productos();
}
function comprueba_si_existe_codigo(codigo) {
    $.post('./modulos/ventas/comprueba_si_existe_codigo.php', {"codigo": codigo}, function (respuesta) {
        $("#codigo_producto")
            .val("")
            .trigger(
                jQuery.Event(
                    'keyup', {
                        keyCode: 27,
                        which: 27
                    }
                )
            )
            .focus();
        respuesta = JSON.parse(respuesta);
        if (respuesta !== false) {
            agrega_producto_local(respuesta);
        }

    });
}
function autocompletado_input() {
    var opciones = {
        theme: "bootstrap",

        url: function (busqueda) {
            var sugerencias = "./modulos/ventas/autocompletado.php?busqueda=" + busqueda;
            return sugerencias;
        },

        getValue: function (producto) {
            return producto.nombre;
        },

        requestDelay: 200,

        list: {
            maxNumberOfElements: 20,


            onChooseEvent: function () {
                var producto_seleccionado = $("#codigo_producto").getSelectedItemData();
                comprueba_si_existe_codigo(producto_seleccionado.codigo);
            },

            showAnimation: {
                type: "fade", //normal|fade|fade
                time: 100
            },

            hideAnimation: {
                type: "fade", //normal|fade|fade
                time: 100
            }
        }
    };

    $("#codigo_producto").easyAutocomplete(opciones);
}