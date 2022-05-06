/***
 *        __   ____  ____   ____
 *       /  ] /    T|    | /    T
 *      /  / Y  o  |l__  |Y  o  |
 *     /  /  |     |__j  ||     |
 *    /   \_ |  _  /  |  ||  _  |
 *    \     ||  |  \  `  ||  |  |
 *     \____jl__j__j\____jl__j__j
 *
 *    Última modificación: 09 de junio de 2016 por Parzibyte
 *    Entorno: Desarrollo
 */


$(document).ready(function () {
    abrir_cajon();
    $("input#cantidad").focus();
    escuchar_elementos();
    $("#ingresar_dinero").animateCss("zoomIn");
    $("li#elem_caja").addClass("active");
});


function deshabilita_para_ingresar_dinero() {
    $("input, button").prop("disabled", true);

}


function habilita_para_ingresar_dinero() {
    $("input, button").prop("disabled", false);

}


function ingresa_cantidad_caja(cantidad) {
    deshabilita_para_ingresar_dinero();
    $("#ingresar_dinero")
        .removeClass('btn-success btn-warning')
        .addClass('btn-warning')
        .html('<i class="fa fa-spin fa-spinner"></i> Guardando dinero...');
    $.post('./modulos/caja/ingresar_dinero.php', {cantidad: cantidad}, function (respuesta) {
        var mensaje = "";
        respuesta = JSON.parse(respuesta);
        console.log('respuesta ', respuesta);

        if (respuesta === true) {
            mensaje = "<i class='fa fa-check'></i>Correcto";
            $("#ingresar_dinero")
                .removeClass('btn-success btn-warning')
                .addClass('btn-success');
        } else {
            mensaje = "<strong>Error:</strong> " + respuesta;
            $("#ingresar_dinero")
                .removeClass('btn-success btn-warning')
                .addClass('btn-warning')
        }
        $("#ingresar_dinero")
            .html(mensaje);
        habilita_para_ingresar_dinero();
        setTimeout(poner_mensaje_original, 2000);
        $("input#cantidad").val("").focus();
    });
}


function poner_mensaje_original() {
    $("#ingresar_dinero").html("Ingresar dinero a la caja");
}


function valida_cantidad() {
    var cantidad = $("#cantidad").val();
    if (cantidad.length > 0 && !isNaN(cantidad) && cantidad > 0) {
        ingresa_cantidad_caja(cantidad);
    } else {
        $("input#cantidad").focus().animateCss("shake");
        $("input#cantidad").parent().addClass('has-error');
    }
}


function escuchar_elementos() {
    $("#ingresar_dinero").click(function () {
        valida_cantidad();
    });

    $("#cantidad").keyup(function (evento) {
        $(this).parent().removeClass('has-error');
        if (evento.keyCode === 13) {
            valida_cantidad();
        }
    });
}