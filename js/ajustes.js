/***
 *      ____  ____  __ __  _____ ______    ___  _____
 *     /    T|    ||  T  T/ ___/|      T  /  _]/ ___/
 *    Y  o  |l__  ||  |  (   \_ |      | /  [_(   \_
 *    |     |__j  ||  |  |\__  Tl_j  l_jY    _]\__  T
 *    |  _  /  |  ||  :  |/  \ |  |  |  |   [_ /  \ |
 *    |  |  \  `  |l     |\    |  |  |  |     T\    |
 *    l__j__j\____j \__,_j \___j  l__j  l_____j \___j
 *
 *    Última modificación: 09 de junio de 2016 por Parzibyte
 *    Entorno: Desarrollo
 */


$(document).ready(function () {
    recuperar_impresoras_disponibles();
    recuperar_datos_empresa();
    escuchar_elementos();
    tema_seleccionado();
    poner_impresora();
    $("li#elem_ajustes").addClass("active");
});
function poner_impresora() {
    $.post('./modulos/dame_impresora.php', function (respuesta) {
        respuesta = JSON.parse(respuesta);
        if (respuesta !== false) $("#impresora_seleccionada").text(respuesta);
    });
}
function recuperar_impresoras_disponibles() {
    $lista = $("#impresoras");
    $.post('./modulos/lista_impresoras.php', function (respuesta) {
        respuesta = JSON.parse(respuesta);
        if (Array.isArray(respuesta)) {
            if (respuesta.length <= 0) {
                $lista.empty().append($("<option>", {
                    value: 0,
                    text: "Ninguna impresora disponible. Asegúrate de compartirla."
                }));
                return;
            }
            $lista.empty().prop("disabled", false);
            $lista
                .append(
                    $("<option>", {
                        value: "null",
                        text: "--Por favor seleciona--"
                    })
                );
            respuesta.forEach(function (valor) {
                $lista
                    .append(
                        $("<option>", {
                            value: valor,
                            text: valor
                        })
                    );
            });

        }
    });
}


function recuperar_datos_empresa() {
    $.post('./modulos/dame_datos_empresa.php', function (respuesta) {
        respuesta = JSON.parse(respuesta);
        $("#nombre").val(respuesta[0]);
        $("#telefono").val(respuesta[1]);
        $("#rfc").val(respuesta[2]);
        $("#direccion").val(respuesta[3]);
        $("#colonia").val(respuesta[4]);
        $("#cp").val(respuesta[5]);
        $("#ciudad").val(respuesta[6]);

    });
}


function escuchar_elementos() {
    $("#impresoras").change(function () {
        var nombre = $(this).val();
        if (nombre !== "null") {
            $.post('./modulos/cambia_impresora.php', {nombre: nombre}, function (respuesta) {
                respuesta = JSON.parse(respuesta);
                if (respuesta === true) window.location.reload();
            });
        }
    });

    $("#guardar_datos").click(function () {
        var html_original = $("#guardar_datos").html();
        $("#guardar_datos")
            .html('Guardando <i class="fa fa-refresh fa-spin"></i>');
        var nombre = $("#nombre").val(),
            telefono = $("#telefono").val(),
            rfc = $("#rfc").val(),
            direccion = $("#direccion").val(),
            colonia = $("#colonia").val(),
            cp = $("#cp").val(),
            ciudad = $("#ciudad").val(),
            datos_empresa = [];
        datos_empresa.push(nombre, telefono, rfc, direccion, colonia, cp, ciudad);
        $.post('./modulos/datos_empresa.php', {"datos_empresa": datos_empresa}, function (respuesta) {
            recuperar_datos_empresa();
            respuesta = JSON.parse(respuesta);
            if (respuesta === true) {
                $("#guardar_datos")
                    .html('Correcto <i class="fa fa-check-square"></i>');
                setTimeout(function () {
                    $("#guardar_datos").html(html_original);
                }, 2000);
            } else {
                $("#guardar_datos")
                    .html('<strong>Error:</strong> ' + respuesta);
                setTimeout(function () {
                    $("#guardar_datos").html(html_original);
                }, 2000);
            }
        });
    });

    $("#cambiar_tema").change(function () {
        $.post('./modulos/cambia_tema.php', {tema: $(this).val()});
        window.location.reload(true);
    });
}


function tema_seleccionado() {
    $.post('./modulos/obtener_tema.php', function (respuesta) {
        respuesta = JSON.parse(respuesta);
        $("#cambiar_tema").val(respuesta);
    });
}