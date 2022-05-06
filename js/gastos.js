/***
 *      ____   ____  _____ ______   ___    _____
 *     /    T /    T/ ___/|      T /   \  / ___/
 *    Y   __jY  o  (   \_ |      |Y     Y(   \_
 *    |  T  ||     |\__  Tl_j  l_j|  O  | \__  T
 *    |  l_ ||  _  |/  \ |  |  |  |     | /  \ |
 *    |     ||  |  |\    |  |  |  l     ! \    |
 *    l___,_jl__j__j \___j  l__j   \___/   \___j
 *
 *    Última modificación: 09 de junio de 2016 por Parzibyte
 *    Entorno: Desarrollo
 */


$(document).ready(function () {
    $("#importe").focus();
    escuchar_elementos();
    $("li#elem_gastos").addClass("active");
    abrir_cajon();
});


function registrar_gasto() {
    if (validar_formulario("#contenedor_form") === true) {
        var importe = $("#importe").val(),
            concepto = $("#concepto").val(),
            descripcion = $("#descripcion").val(),
            no_remision = $("#no_remision").val();
        if (isNaN(importe) || importe <= 0 || importe.length <= 0) {
            $("#importe").focus().parent().addClass('has-error');
            $("#importe").animateCss("shake");
            return;
        }

        if (concepto.length <= 0 || concepto.length >= 1024) {
            $("#concepto").focus().parent().addClass('has-error');
            $("#concepto").animateCss("shake");
            return;
        }

        if (descripcion.length <= 0 || descripcion.length >= 1024) {
            $("#concepto").focus().parent().addClass('has-error');
            $("#concepto").animateCss("shake");
            return;
        }

        if (no_remision.length > 0) {
            if (no_remision.length >= 1024) {
                $("#no_remision").focus().parent().addClass('has-error');
                $("#no_remision").animateCss("shake");
                return;
            }
        }
        deshabilita_para_gastos();
        $("#guardar_gasto")
            .removeClass('btn-success btn-warning')
            .addClass('btn-warning')
            .html('<i class="fa fa-spin fa-spinner"></i> Guardando...');
        $.post('./modulos/gastos/registrar_gasto.php', {datos_gasto: [importe, concepto, descripcion, no_remision]}, function (respuesta) {
            respuesta = JSON.parse(respuesta);
            var mensaje = "";
            if (respuesta === true) {
                mensaje = '<i class="fa fa-check"></i> Correcto';
                $("#guardar_gasto")
                    .removeClass('btn-success btn-warning')
                    .addClass('btn-success');
            } else {
                mensaje = '<strong>Error: </strong>' + respuesta;
                $("#guardar_gasto")
                    .removeClass('btn-success btn-warning')
                    .addClass('btn-warning');
            }
            $("#guardar_gasto")
                .html(mensaje);
            setTimeout(restaurar_boton, 2000);
            $("#contenedor_form input").val("");
            habilita_para_gastos();
            $("#importe").focus();
        });
    }
}


function restaurar_boton() {
    $("#guardar_gasto")
        .html("Guardar")
        .removeClass('btn-success btn-warning')
        .addClass('btn-success');
}


function deshabilita_para_gastos() {
    $("input, button").prop("disabled", true);
}


function habilita_para_gastos() {
    $("input, button").prop("disabled", false);
}


function escuchar_elementos() {
    $("input").keyup(function () {
        $(this).parent().removeClass('has-error');
    });
    $("#importe").keyup(function (evento) {
        if (evento.keyCode === 13) {
            $("#concepto").focus();
        }
    });
    $("#concepto").keyup(function (evento) {
        if (evento.keyCode === 13) {
            $("#descripcion").focus();
        }
    });
    $("#descripcion").keyup(function (evento) {
        if (evento.keyCode === 13) {
            $("#no_remision").focus();
        }
    });
    $("#no_remision").keyup(function (evento) {
        if (evento.keyCode === 13) {
            registrar_gasto();
        }
    });
    $("#guardar_gasto").click(function () {
        registrar_gasto();
    });
}