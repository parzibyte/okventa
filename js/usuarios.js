var expresion_palabras_secretas = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,12}$/;
var expresion_nombre_usuario = /^[_a-z][a-z0-9_\.]{3,11}$/;
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

function principal() {
    consultar_todos_los_usuarios();
    escuchar_elementos();
}

function consultar_todos_los_usuarios() {
    $.post('./modulos/usuarios/consultar_todos_los_usuarios.php', function (respuesta) {
        respuesta = JSON.parse(respuesta);
        dibuja_tabla_usuarios(respuesta)

    });
}
function eliminar_usuario(rowid) {
    $.post('./modulos/usuarios/eliminar_usuario.php', {rowid: rowid}, function (respuesta) {
        respuesta = JSON.parse(respuesta);
        if (respuesta === true) {
            consultar_todos_los_usuarios();
            console.log('respuesta eliminar ', respuesta);
        } else {
            //Error y manejamos el error
        }
    });
}
function change_password(rowid, new_password, callback) {
    var data = JSON.stringify({"rowid": rowid, "new_password": new_password});
    $.post(
        "./modulos/usuarios/change_password.php"
        , {data: data}, function (respuesta) {
            respuesta = JSON.parse(respuesta);
            callback(respuesta);
        });
}
function escuchar_elementos() {
    var coincide_palabra_secreta = false,
        coincide_nombre_usuario = false,
        rowid = undefined;
    $(document).on("click", ".eliminar-usuario", function () {
        rowid = $(this).data("rowid");
        eliminar_usuario(rowid);
    });

    $(document).on("click", ".change-password", function () {
        $("#modal_change_password_title").html("Cambiando la contraseña de " + $(this).attr("data-user-name"))
        $("#confirm_new_password").attr("data-rowid-user", $(this).attr("data-rowid-user"));
        $("#modal_formulario_cambiar_pass").modal("show");
    });
    $("#cambiar_pass").click(function () {
        var rowid = $("#confirm_new_password").attr("data-rowid-user"), new_password = $("#new_password").val(), confirm_new_password = $("#confirm_new_password").val();
        if(!new_password || ! confirm_new_password) return;
        if (!expresion_palabras_secretas.test(new_password)) {
            alert("La contraseña no es segura. Prueba poniendo otra.")
        } else if (new_password !== confirm_new_password) {
            console.log("Tipo 1: %s\nTipo2: %s", typeof new_password, typeof confirm_new_password);
            alert("Las contraseñas no coinciden.");
        } else {
            change_password(rowid, new_password, function (respuesta) {
                if (respuesta === true) {
                    alert("Cambiado con éxito");
                    $("#modal_formulario_cambiar_pass").modal("hide");
                }
            });
        }
    });

    $("#palabra_secreta").keyup(function (event) {
        coincide_palabra_secreta = expresion_palabras_secretas.test($(this).val());
        console.log('coincide_palabra_secreta ', coincide_palabra_secreta);
        if (coincide_palabra_secreta == true) {
            $(this).parent().removeClass("has-error").addClass('has-success');
        } else {
            $(this).parent().removeClass("has-success").addClass('has-error');
        }
    });

    $("#palabra_secreta_confirm").keyup(function () {
        if ($(this).val() === $("#palabra_secreta").val()) {
            coincide_palabra_secreta = expresion_palabras_secretas.test($(this).val());
            if (coincide_palabra_secreta == true) {
                $(this)
                    .parent()
                    .removeClass("has-error")
                    .addClass('has-success');
                $("#palabra_secreta").parent().removeClass("has-error").addClass('has-success');
            } else {
                $("#palabra_secreta").parent().removeClass("has-success").addClass('has-error');
            }
        } else {
            $(this)
                .parent()
                .removeClass("has-success")
                .addClass('has-error');
        }
    });


    $("#usuario").keyup(function (evento) {
        if (evento.keyCode === 32) {
            $(this).val($(this).val().replace(/ /g, ''));
        }

        if (evento.keyCode >= 65 && evento.keyCode <= 90 || evento.keyCode === 192) {
            this.value = this.value.toLowerCase();
        }

        coincide_nombre_usuario = expresion_nombre_usuario.test($(this).val());
        if (coincide_nombre_usuario === true) {
            $(this)
                .parent()
                .removeClass('has-error')
                .addClass('has-success');
            delay(function () {
                $("#mostrar_si_existe").html('Comprobando... <i class="fa fa-circle-o-notch fa-spin"></i>');
                $.post('./modulos/usuarios/usuario_existe.php', {nombre_de_usuario: $("#usuario").val().toLowerCase()}, function (respuesta) {
                    respuesta = JSON.parse(respuesta);
                    if (respuesta == false) {
                        $("#mostrar_si_existe").html('Correcto <i class="fa fa-check-circle"></i>');
                        $("#usuario").parent().removeClass('has-error').addClass('has-success');
                    } else {
                        $("#mostrar_si_existe").html('<i class="fa fa-user-times"></i> El nombre de usuario ya existe. Elige otro.');
                        $("#usuario").parent().removeClass('has-success').addClass('has-error');
                        $("#usuario").animateCss("flash");
                    }
                });
            }, 500);

        } else {
            $(this)
                .parent()
                .removeClass('has-success')
                .addClass('has-error');
        }
    });

    $("#nuevo").click(function () {
        $("#modal_formulario").modal("show");
    });

    $(document).on("shown.bs.modal", "#modal_formulario", function () {
        $("#usuario").focus();
    });
    $(document).on("shown.bs.modal", "#modal_formulario_cambiar_pass", function () {
        $("#new_password").focus();
    });

    $(document).on("hidden.bs.modal", "#modal_formulario", function () {
        $("input").val("").parent().removeClass('has-error has-success');
        $("#mostrar_resultados").html("").parent().hide();
    });
    $("#guardar_usuario").click(function () {
        var usuario = $("#usuario").val(),
            palabra_secreta = $("#palabra_secreta").val(),
            palabra_secreta_confirm = $("#palabra_secreta_confirm").val(),
            administrador = $("#administrador").prop("checked");
        var mensaje_error = undefined;
        if (!usuario) {
            $("#usuario")
                .parent()
                .addClass('has-error')
            $("#usuario").focus().animateCss("shake");
            return;
        }
        if (!palabra_secreta) {
            $("#palabra_secreta")
                .parent()
                .addClass('has-error')
            $("#palabra_secreta").focus().animateCss("shake");
            return;
        }
        if (!palabra_secreta_confirm) {
            $("#palabra_secreta_confirm")
                .parent()
                .addClass('has-error')
            $("#palabra_secreta_confirm").focus().animateCss("shake");
            return;
        }
        if (
            expresion_nombre_usuario.test($("#usuario").val()) &&
            expresion_palabras_secretas.test($("#palabra_secreta").val()) &&
            $("#palabra_secreta").val() === $("#palabra_secreta_confirm").val()
        ) insertar_usuario(usuario, palabra_secreta, administrador);
    });
}

function insertar_usuario(usuario, palabra_secreta, administrador) {
    $("#mostrar_resultados").html("Registrando... <i class='fa fa-spin fa-circle-o-notch'></i>").parent().addClass('alert-warning').show();
    usuario = JSON.stringify(usuario);
    administrador = JSON.stringify(administrador);
    palabra_secreta = JSON.stringify(palabra_secreta);
    $.post('./modulos/usuarios/insertar_usuario.php', {
        nombre_de_usuario: usuario,
        palabra_secreta: palabra_secreta,
        es_administrador: administrador
    }, function (respuesta) {
        respuesta = JSON.parse(respuesta);
        if (respuesta === true) {
            $("#mostrar_resultados").html("Correcto").parent().addClass('alert-success').show();
            $("input").val("");
            setTimeout(function () {
                $("#modal_formulario").modal("hide");
                consultar_todos_los_usuarios();
            }, 1000)
        } else {
            $("#mostrar_resultados").html("Error: " + respuesta).parent().addClass('alert-danger').show();
        }
    });
}

function dibuja_tabla_usuarios(respuesta) {
    $("#tabla-contenedora tbody")
        .empty();
    for (var i = respuesta.length - 1; i >= 0; i--) {
        $("#tabla-contenedora tbody")
            .append(
                $("<tr>")
                    .append(
                        $("<td>")
                            .html(respuesta[i].nombre),
                        $("<td>")
                            .html(( respuesta[i].administrador == 1 ) ? '<i class="fa fa-unlock"></i>' : '<i class="fa fa-lock"></i>'),
                        $("<td>")
                            .html('<button data-rowid = "' + respuesta[i].rowid + '" class="btn btn-danger eliminar-usuario"><i class="fa fa-remove"></i></button>'),
                        $("<td>")
                            .html('<button class="btn btn-warning"><i class="fa fa-edit"></i></button>'),
                        $("<td>")
                            .html('<button data-user-name="' + respuesta[i].nombre + '" data-rowid-user = "' + respuesta[i].rowid + '" class="btn btn-success change-password"><i class="fa fa-key"></i></button>')
                    )
            );

    }
}