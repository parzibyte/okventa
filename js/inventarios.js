/***
 *     ____  ____   __ __    ___  ____   ______   ____  ____   ____  ___
 *    l    j|    \ |  T  |  /  _]|    \ |      T /    T|    \ l    j/   \
 *     |  T |  _  Y|  |  | /  [_ |  _  Y|      |Y  o  ||  D  ) |  TY     Y
 *     |  | |  |  ||  |  |Y    _]|  |  |l_j  l_j|     ||    /  |  ||  O  |
 *     |  | |  |  |l  :  !|   [_ |  |  |  |  |  |  _  ||    \  |  ||     |
 *     j  l |  |  | \   / |     T|  |  |  |  |  |  |  ||  .  Y j  ll     !
 *    |____jl__j__j  \_/  l_____jl__j__j  l__j  l__j__jl__j\_j|____j\___/
 *
 *    Última modificación: 09 de junio de 2016 por Parzibyte
 *    Entorno: Desarrollo
 */
var $mostrar_resultados = $("p#mostrar_resultados"),
    $contenedor_productos = $("#cuerpo_tabla_productos"),
    OFFSET = 0,
    LIMITE = 7,
    id_temporal_ayudante = undefined,
    esta_editando = undefined,
    tooltip_esta_mostrado = undefined,
    ayudante_cantidad = undefined,
    numero_total_productos = undefined,
    esta_buscando = undefined,
    busqueda_anterior = undefined;

var delay = (function () {
    var timer = 0;
    return function (callback, ms) {
        clearTimeout(timer);
        timer = setTimeout(callback, ms);
    };
})();

$(document).ready(function () {
    principal();
});

function esperar_para_buscar() {
    puede_buscar = true;
    buscar_producto($("#buscar_producto").val())
}


function principal() {
    $("#id_producto").focus();
    escuchar_elementos();
    consultar_todos_los_productos();
    $("li#elem_inventarios").addClass("active");
}


function deshabilitar_para_transaccion() {
    $("input, button").prop("disabled", true);
    if (esta_buscando === true) {
        $("#buscar_producto").prop("disabled", false);
    }
}


function habilitar_para_transaccion() {
    $("input, button").prop("disabled", false);
}


function dar_baja(numero_piezas, razon_baja) {
    deshabilitar_para_transaccion();
    $.post('./modulos/inventario/dar_baja_inventario.php',
        {
            rowid: id_temporal_ayudante,
            numero_piezas: numero_piezas,
            razon_baja: razon_baja
        },
        function (respuesta) {
            respuesta = JSON.parse(respuesta);
            if (respuesta === true) {
                $("#mostrar_resultados_dar_baja")
                    .html("<i class = 'fa fa-check'></i> ¡Correcto!")
                    .parent()
                    .removeClass('alert-success alert-warning alert-danger')
                    .addClass('alert-success')
                    .show("slow")
                    .delay(1000)
                    .hide("slow", function () {
                        $("#modal_dar_baja").modal("hide");
                    });
                consultar_todos_los_productos();
            } else {
                $("#mostrar_resultados_dar_baja")
                    .html("<strong>Error: </strong>" + respuesta.toString())
                    .parent()
                    .removeClass('alert-success alert-warning alert-danger')
                    .addClass('alert-danger')
                    .show("slow")
                    .delay(2000)
                    .hide("slow", function () {
                        $("#modal_dar_baja").modal("hide");
                    });
            }
            habilitar_para_transaccion();
        });
}


function agregar_piezas(numero_piezas, id_producto) {
    deshabilitar_para_transaccion();
    $.post('./modulos/inventario/agregar_piezas_producto.php', {
        "numero_piezas": numero_piezas,
        "id_producto": id_producto
    }, function (respuesta) {
        respuesta = JSON.parse(respuesta);
        if (respuesta === true) {
            $("#mostrar_resultados_agregar_piezas")
                .html("<i class='fa-check fa'></i> ¡Piezas agregadas correctamente!")
                .parent()
                .removeClass('alert-success alert-warning')
                .addClass('alert-success')
                .delay(300)
                .hide("slow", function () {
                    $("#modal_agregar_piezas").modal("hide");
                    $("#numero_piezas_agregar").val("");
                    $("#agregar_piezas").prop("disabled", false);
                });
            consultar_todos_los_productos();
        }
        habilitar_para_transaccion();
    });
}


function consultar_producto(codigo_producto) {
    $.post('./modulos/inventario/consultar_producto.php', {codigo_producto: codigo_producto}, function (respuesta) {
        respuesta = JSON.parse(respuesta);
        ayudante_cantidad = respuesta.cantidad;
        llenar_formulario_para_editar(respuesta);
    });
}


function consultar_cantidad_producto() {
    $.post('./modulos/inventario/consultar_cantidad_producto.php', {rowid: id_temporal_ayudante}, function (cantidad) {
        cantidad = JSON.parse(cantidad);
        console.log('cantidad ', cantidad);
        ayudante_cantidad = cantidad;
    });
}


function consultar_numero_total_productos() {
    $.post('./modulos/inventario/consultar_numero_total_productos.php', function (respuesta) {
        respuesta = JSON.parse(respuesta);
        numero_total_productos = respuesta;
    });
}


function llenar_formulario_para_editar(datos_producto) {
    $("#cancelar_producto_editado").parent().show();
    $("label[for='inventario']").text("Cantidad");
    $("#id_producto").focus().val(datos_producto.codigo);
    $("#nombre_producto").val(datos_producto.nombre);
    $("#precio_compra").val(datos_producto.precio_compra);
    $("#precio_venta").val(datos_producto.precio_venta);
    $("#inventario").attr("placeholder", "Cantidad").val(datos_producto.existencia);
    $("#stock").val(datos_producto.stock);
    $("#familia").val(datos_producto.familia);
    $("#contenedor_formulario").animateCss("rubberBand");
}


function buscar_producto(busqueda) {
    OFFSET = 0;
    deshabilitar_para_transaccion();
    $.post('./modulos/inventario/buscar_producto.php', {
        busqueda: busqueda,
        limite: LIMITE,
        offset: OFFSET
    }, function (respuesta) {
        respuesta = JSON.parse(respuesta);
        consultar_numero_total_productos_busqueda();
        dibujar_todos_los_productos(respuesta);
        habilitar_para_transaccion();
    });
}


function escuchar_elementos() {
    $("input#buscar_producto, input#nombre_producto").keyup(function (evento) {
        if (evento.keyCode >= 64 && evento.keyCode <= 90) this.value = this.value.toUpperCase();
    });

    $("#precio_venta").keyup(function (evento) {
        if (evento.keyCode === 13) $("#inventario").focus();
    });

    $("#inventario").keyup(function (evento) {
        if (evento.keyCode === 13) {
            $("#guardar_producto").click();
        }
    });

    $("#buscar_producto").keyup(function (event) {
        delay(function () {
            if ($("#buscar_producto").val() !== busqueda_anterior) {
                if ($("#buscar_producto").val().length > 0) {
                    esta_buscando = true;
                    buscar_producto($("#buscar_producto").val());
                } else {
                    esta_buscando = false;
                    consultar_todos_los_productos();
                }
                busqueda_anterior = $("#buscar_producto").val();
            }
        }, 200);
    });

    $(document).on("click", "#cargar_productos_nuevos", function (evento) {
        if (OFFSET - LIMITE > 0) {
            OFFSET = OFFSET - LIMITE;
        } else {
            OFFSET = 0;
        }
        if (esta_buscando === true) {
            buscar_producto($("#buscar_producto").val());
        } else {
            consultar_todos_los_productos();
        }
    });

    $(document).on("click", "#cargar_productos_antiguos", function (evento) {
        if (OFFSET + LIMITE < numero_total_productos) {
            OFFSET = OFFSET + LIMITE;
        }
        if (esta_buscando === true) {
            buscar_producto($("#buscar_producto").val());
        } else {
            consultar_todos_los_productos();
        }
    });

    $(document).on("click", ".eliminar, .editar, .agregar-piezas, .dar-baja", function () {
        $("button[data-toggle='tooltip']").tooltip("hide");
        tooltip_esta_mostrado = false;
    });

    $(document).on("click", ".dar-baja", function () {
        id_temporal_ayudante = $(this).data("id");
        $("#modal_dar_baja").modal("show");
        consultar_cantidad_producto();
    });

    $(document).on("click", "button[data-toggle='tooltip']", function () {
        if (tooltip_esta_mostrado) {
            $(this).tooltip("hide");
            tooltip_esta_mostrado = false;
        } else {
            $(this).tooltip("show");
            tooltip_esta_mostrado = true;
        }
    });

    $("#cancelar_producto_editado").click(function () {
        esta_editando = false;
        limpiar_formulario("div#contenedor_formulario");
        $("#buscar_producto").focus();
        $(this).parent().hide();
        $("#mostrar_resultados").parent().hide();
    });

    $(document).on("click", ".editar", function (evento) {
        esta_editando = true;
        id_temporal_ayudante = $(this).data("id");
        consultar_producto($(this).data("id"));
    });

    $("#modal_agregar_piezas").on("hidden.bs.modal", function () {
        $("#numero_piezas_agregar").val("").parent().removeClass('has-error');
    });

    $("#modal_dar_baja").on("shown.bs.modal", function () {
        $("#numero_piezas_baja").focus();
    });

    $("#modal_dar_baja").on("hidden.bs.modal", function () {
        $("#numero_piezas_baja, #motivo_baja").val("");
        $("#mostrar_resultados_dar_baja").parent().hide();
        $("#numero_piezas_baja").parent().removeClass('has-error');
    });

    $("#modal_agregar_piezas").on("shown.bs.modal", function () {
        $("#numero_piezas_agregar").focus();
    });

    $(document).on("click", ".eliminar", function (evento) {
        id_temporal_ayudante = $(this).data("id");
        $("#modal_confirmar").modal("show");
    });

    $(document).on("click", ".agregar-piezas", function (evento) {
        id_temporal_ayudante = $(this).data("id");
        $("#modal_agregar_piezas").modal("show");
    });

    $("#numero_piezas_agregar").keyup(function (evento) {
        if ($(this).val().length > 0) $(this).parent().removeClass('has-error');
    });

    $("#agregar_piezas").click(function (evento) {
        var numero_piezas = $("#numero_piezas_agregar").val();
        if (isNaN(numero_piezas) || numero_piezas.length <= 0 || numero_piezas <= 0) {
            $("#numero_piezas_agregar").focus().animateCss("shake");
            $("#numero_piezas_agregar").parent().addClass('has-error');
            return;
        }
        $("#mostrar_resultados_agregar_piezas")
            .html("<i class='fa fa-spinner fa-spin'></i> Agregando piezas...")
            .parent()
            .removeClass('alert-success alert-warning')
            .addClass('alert-warning')
            .show("slow");
        $(this).prop("disabled", true);
        agregar_piezas(numero_piezas, id_temporal_ayudante);
    });

    $("[data-requerido='true']").keyup(function (evento) {
        if (evento.keyCode === 13) {
            $(this).parent().next("div").children("input").focus();
        }
        if ($(this).val()) $(this).parent().removeClass('has-error');
        $mostrar_resultados.parent().hide();
    });

    $("#guardar_producto").click(function (evento) {
        insertar_producto();
    });

    $("#precio_venta, #precio_compra").keyup(function (evento) {
        var precio_compra = $("#precio_compra").val();
        var precio_venta = $("#precio_venta").val();
        var utilidad = precio_venta - precio_compra;
        if (precio_compra > 0 && precio_venta > 0 && utilidad > 0) {
            $("#mostrar_utilidad")
                .show()
                .text(utilidad)
                .parent()
                .show();
        }
        else {
            $("#mostrar_utilidad")
                .hide()
                .text("")
                .parent()
                .hide();
        }
    });

    $("#precio_venta").keypress(function (evento) {
        if (!isNaN($(this).val())) $(this).tooltip("hide");
    });

    $("#eliminar_producto").click(function () {
        $("#mostrar_resultados_eliminar")
            .html('<i class="fa fa-spin fa-spinner"></i> Eliminando...')
            .parent()
            .removeClass('alert-success alert-warning')
            .addClass('alert-warning')
            .show("slow");
        deshabilitar_para_eliminar();
        eliminar_producto(id_temporal_ayudante);
    });

    $("#numero_piezas_baja").keyup(function () {
        if (!isNaN($(this).val()) && $(this).val() > 0) {
            $(this).parent().removeClass('has-error');
        }
    });

    $("#dar_baja").click(function () {
        var numero_piezas = $("#numero_piezas_baja").val(),
            razon = $("#motivo_baja").val();
        var mensaje_error = undefined;
        if (numero_piezas === "")
            mensaje_error = "No puedes dejar el campo vacío.";
        if (numero_piezas <= 0)
            mensaje_error = "No puedes quitar menos de cero piezas.";
        if (ayudante_cantidad - numero_piezas < 0)
            mensaje_error = "No puedes quitar más piezas de las que tienes.";
        if (mensaje_error !== undefined) {
            $("#mostrar_resultados_dar_baja")
                .html("<strong>Error:</strong> " + mensaje_error)
                .parent()
                .removeClass('alert-success alert-warning alert-danger')
                .addClass('alert-warning')
                .show("slow", function () {
                    $("#numero_piezas_baja").focus().animateCss("shake");
                    $("#numero_piezas_baja").parent().addClass("has-error");

                });
            return;
        }

        if (razon.length <= 0) {
            $("#mostrar_resultados_dar_baja")
                .html("<strong>Error:</strong> No puedes dejar el campo vacío.")
                .parent()
                .removeClass('alert-success alert-warning alert-danger')
                .addClass('alert-warning')
                .show("slow", function () {
                    $("#motivo_baja").focus().animateCss("shake");
                    $("#motivo_baja").parent().addClass("has-error");

                });
            return;
        }

        $("#numero_piezas_baja, #motivo_baja").parent().removeClass('has-error');
        $("#mostrar_resultados_dar_baja")
            .html("<i class = 'fa fa-spin fa-spinner'></i> Dando de baja...")
            .parent()
            .show()
            .removeClass('alert-success alert-warning alert-danger')
            .addClass('alert-warning');
        dar_baja(numero_piezas, razon);
    });

}

function isInt(n) {
    return n % 1 === 0;
}


function eliminar_producto(id_temporal_ayudante) {
    deshabilitar_para_transaccion();
    $.post('./modulos/inventario/eliminar_producto.php', {rowid: id_temporal_ayudante}, function (respuesta) {
        respuesta = JSON.parse(respuesta);
        if (respuesta === true) {
            $("#mostrar_resultados_eliminar")
                .html("<i class='fa fa-check'></i>¡Correcto!")
                .parent()
                .removeClass("alert-warning alert-success")
                .addClass('alert-success')
                .delay(200)
                .hide("slow", function () {
                    $("#modal_confirmar").modal("hide");
                    habilitar_para_eliminar();
                })
            consultar_todos_los_productos();
        } else {
            $("#mostrar_resultados_eliminar")
                .html("<strong>Error: </strong>" + respuesta.toString())
                .parent()
                .removeClass("alert-warning alert-success")
                .addClass('alert-danger')
                .delay(2000)
                .hide("slow", function () {
                    $("#modal_confirmar").modal("hide");
                    habilitar_para_eliminar();
                })
        }
        habilitar_para_transaccion();
    });
}


function deshabilitar_para_eliminar() {
    $("#cancelar_confirmacion_eliminar, #eliminar_producto").prop("disabled", true);
}


function habilitar_para_eliminar() {
    $("#cancelar_confirmacion_eliminar, #eliminar_producto").prop("disabled", false);
}


function dibujar_todos_los_productos(productos) {
    $contenedor_productos.empty();
    if (productos.length <= 0) {
        $("#cargar_productos_nuevos, #cargar_productos_antiguos").hide();
        var html_contenedor = "<tr><td colspan='7'>"
            + "<h2 class='text-center'>"
            + "<i class='fa fa-4x fa-archive'></i><br>"
            + "Todavía no hay productos <br>"
            + "<small>Agrega unos con el formulario de la izquierda</small></h2>"
            + "</td></tr>";

        if (esta_buscando === true) {
            html_contenedor = "<tr><td colspan='7'>"
                + "<h2 class='text-center'>"
                + "<i class='fa fa-4x fa-frown-o'></i><br>"
                + "Ningún producto coincide con tu búsqueda <br>"
                + "<small>Comprueba tu ortografía e inténtalo de nuevo</small></h2>"
                + "</td></tr>";
        }
        $contenedor_productos
            .append(html_contenedor)
            .parent()
            .find("thead")
            .hide();
        if (esta_buscando !== true) {
            $("#buscar_producto").prop("disabled", true);
        }
        return;
    }
    $("#cargar_productos_nuevos, #cargar_productos_antiguos").show();
    $contenedor_productos
        .parent()
        .find("thead")
        .show();

    $("#buscar_producto").prop("disabled", false);
    var nombre, codigo;
    for (var i = 0; i < productos.length; i++) {
        nombre = productos[i].nombre;
        codigo = productos[i].codigo;
        if (esta_buscando === true) {
            nombre = nombre.replace($("#buscar_producto").val(), "<kbd>" + $("#buscar_producto").val() + "</kbd>");
            codigo = codigo.replace($("#buscar_producto").val(), "<kbd>" + $("#buscar_producto").val() + "</kbd>");
        }
        $contenedor_productos
            .append(
                $('<tr>')
                    .append(
                        $('<td>').html(codigo),
                        $('<td style="word-wrap: break-word; white-space: normal;">').html(nombre),
                        $('<td>').html("$" + productos[i].precio_compra),
                        $('<td>').html("$" + productos[i].precio_venta),
                        $('<td>').html("$" + productos[i].utilidad),
                        $('<td>').html(productos[i].existencia),
                        $('<td>').html(productos[i].stock),
                        $('<td>').html(productos[i].familia),
                        $('<td>')
                            .append(
                                $("<button data-toggle='tooltip'>")
                                    .addClass('btn btn-default')
                                    .html("<i class='fa fa-ellipsis-v'></i>")
                                    .tooltip({
                                        trigger: "manual",
                                        html: true,
                                        placement: "right",
                                        title: $("<div>")
                                            .addClass('btn-group')
                                            .append(
                                                $("<button data-id = '" + productos[i].rowid + "'>")
                                                    .addClass('eliminar btn btn-danger')
                                                    .html(
                                                        $("<i>")
                                                            .addClass('fa fa-trash')
                                                    )
                                            )
                                            .append(
                                                $("<button data-id = '" + productos[i].rowid + "'>")
                                                    .addClass('editar btn btn-warning')
                                                    .html(
                                                        $("<i>")
                                                            .addClass('fa fa-pencil')
                                                    )
                                            )
                                            .append(
                                                $("<button data-id = '" + productos[i].rowid + "'>")
                                                    .addClass('agregar-piezas btn btn-success')
                                                    .html(
                                                        $("<i>")
                                                            .addClass('fa fa-plus-circle')
                                                    )
                                            )
                                            .append(
                                                $("<button data-id = '" + productos[i].rowid + "'>")
                                                    .addClass('dar-baja btn btn-info')
                                                    .html(
                                                        $("<i>")
                                                            .addClass('fa fa-minus-circle')
                                                    )
                                            )
                                    })
                            )
                    )
            );
    }
}


function insertar_producto() {
    if (validar_formulario("div#contenedor_formulario")) {
        var id_producto = $("#id_producto").val(),
            nombre_producto = $("#nombre_producto").val(),
            precio_compra = $("#precio_compra").val(),
            precio_venta = $("#precio_venta").val(),
            inventario_inicial = $("#inventario").val(),
            stock = $("#stock").val(),
            familia = $("#familia").val(),
            datos_producto = [],
            utilidad = precio_venta - precio_compra;
        if (utilidad < 0) {
            $("#precio_venta").focus().tooltip("show");
            return;
        }
        datos_producto.push(
            id_producto,
            nombre_producto,
            precio_compra,
            precio_venta,
            utilidad,
            inventario_inicial,
            stock,
            familia
        );
        var ruta = "./modulos/inventario/insertar_producto.php",
            texto_resultado = "<i class='fa fa-spinner fa-spin'></i> Registrando producto...";
        if (esta_editando) {
            if (isNaN($("#inventario").val()) || $("#inventario").val() < ayudante_cantidad) {
                $("#inventario").animateCss('shake');
                $("#inventario").parent().addClass('has-error');
                return;
            }
            ruta = "./modulos/inventario/editar_producto.php";
            texto_resultado = "<i class='fa fa-spinner fa-spin'></i> Guardando producto...";
            datos_producto.push(id_temporal_ayudante);
        }
        console.log("Enviando los siguientes datos:" , datos_producto);
        $mostrar_resultados
            .html("<i class='fa fa-spinner fa-spin'></i> Registrando producto...")
            .parent()
            .removeClass("alert-success alert-warning")
            .addClass('alert-warning')
            .show("slow")
            .scrollTop(0);
        deshabilitar_para_transaccion();
        $.post(ruta, {datos_producto: datos_producto}, function (data, textStatus, xhr) {
            habilitar_para_transaccion();
            data = JSON.parse(data);
            switch (parseInt(data)) {
                case 0:
                    if (esta_editando) {
                        $("label[for='inventario']").text("Inventario inicial");
                        $("#inventario").attr("placeholder", "Inventario inicial");
                        $("#cancelar_producto_editado").parent().hide();
                    }
                    $mostrar_resultados
                        .html("<i class='fa fa-check'></i> ¡Correcto!")
                        .parent()
                        .removeClass('alert-success alert-warning alert-danger')
                        .addClass('alert-success')
                        .delay(200)
                        .hide("slow", function () {
                            consultar_todos_los_productos();
                            limpiar_formulario("div#contenedor_formulario");
                            $("#mostrar_utilidad").hide().text("").parent().hide();
                            $("#id_producto").focus();
                        });
                    break;
                case 1:
                    $mostrar_resultados
                        .html(
                            "<i class='fa fa-exclamation-circle'></i> " +
                            "<strong>Error:</strong><br>Error en la base de datos.")
                        .parent()
                        .removeClass('alert-success alert-warning alert-danger')
                        .addClass('alert-danger').focus();
                    $('html, body').animate({
                        scrollTop: $mostrar_resultados.parent().offset().top
                    }, 1000);
                    return;
                    break;
                case 2:
                    $mostrar_resultados
                        .html(
                            "<i class='fa fa-exclamation-circle'></i> " +
                            "<strong>Error:</strong><br>El id del producto ya está registrado")
                        .parent()
                        .removeClass('alert-success alert-warning alert-danger')
                        .addClass('alert-danger').focus();
                    $('html, body').animate({
                        scrollTop: $mostrar_resultados.parent().offset().top
                    }, 1000, function () {
                        $('html, body').animate({
                            scrollTop: $("#id_producto").offset().top
                        }, 1000, function () {
                            $("#id_producto").focus().animateCss("shake");
                            $("#id_producto").focus().parent().addClass('has-error');
                        });
                    });
                    return;
                    break;
                default:
                    // error sin manejar
                    break;
            }

            esta_editando = false;
        });
    }
}


function consultar_numero_total_productos_busqueda() {
    $.post('./modulos/inventario/consultar_numero_total_productos_busqueda.php', {busqueda: $("#buscar_producto").val()}, function (respuesta) {
        respuesta = JSON.parse(respuesta);
        numero_total_productos = respuesta;
    });
}


function consultar_todos_los_productos() {
    $.post('./modulos/inventario/consultar_todos_los_productos.php', {
        limite: LIMITE,
        offset: OFFSET
    }, function (respuesta) {
        respuesta = JSON.parse(respuesta);
        dibujar_todos_los_productos(respuesta);
        consultar_numero_total_productos();
    });
}


function limpiar_formulario(padre_formulario) {
    $(padre_formulario + " input").val("").parent().removeClass('has-error');
}


function validar_formulario(padre_formulario) {
    var focus_fue_dado = false,
        todos_los_campos_llenos = true;
    $(padre_formulario + " input").each(function () {
        if ($(this).data("requerido")) {
            if (
                $(this).val().length <= 0 || (($(this).attr("type") == "number") && (isNaN($(this).val())))) {
                todos_los_campos_llenos = false;
                $(this).parent().addClass("has-error");
                $(this).animateCss("shake");
                if (!focus_fue_dado) {
                    $(this).focus();
                    focus_fue_dado = true;
                }
            }
        }
    });
    return todos_los_campos_llenos;
}