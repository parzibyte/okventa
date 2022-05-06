<?php
if(!isset($_SESSION)) exit("<script>window.location.href = '../';</script>");
if ($_SESSION["administrador"] !== 1)
    exit('<h1 class="text-center">Lo sentimos, solamente el administrador puede ver esta sección<br><br><i class="fa fa-hand-paper-o fa-4x"></i></h1>');
?>
<link rel="stylesheet" href="./css/abc.css">
<div class="row">
    <div class="col-md-4 col-md-offset-4 col-xs-12">
        <button id="nuevo" class="btn btn-info form-control">Nuevo <i class="fa fa-user-plus"></i></button>
    </div>
</div>
<br>
<div class="row">
    <div class="col-xs-12 table-responsive">
        <table id="tabla-contenedora" class="table table-bordered table-striped table-hover table-condensed">
            <thead>
            <tr>
                <th>Nombre</th>
                <th>Privilegios</th>
                <th>Eliminar</th>
                <th>Editar</th>
                <th>Cambiar contraseña</th>
            </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>
<script src="./js/usuarios.js"></script>


<div id="modal_formulario" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Registrar usuario</h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="usuario">Nombre</label>
                    <input type="text" id="usuario" placeholder="Nombre" class="form-control">
                    <span id="mostrar_si_existe">El nombre de usuario debe tener entre <strong>4 y 12</strong><br> carácteres, puede contener letras, números (excepto al inicio) y guiones bajos <strong>(_)</strong>.</span>
                </div>
                <div class="form-group">
                    <label for="palabra_secreta">Contraseña</label>
                    <input type="password" id="palabra_secreta" placeholder="Contraseña" class="form-control">
                    <span>La contraseña debe tener entre <strong>8 y 12</strong> carácteres, entre ellos debe haber al menos un dígito <strong>[0-9]</strong>, una letra mayúscula <strong>[A-Z]</strong> y una letra minúscula <strong>[a-z]</strong>.</span>
                </div>
                <div class="form-group">
                    <label for="palabra_secreta">Confirmar contraseña</label>
                    <input type="password" id="palabra_secreta_confirm" placeholder="Contraseña" class="form-control">
                </div>
                <div class="form-group">
                    <div class="checkbox checkbox-warning checkbox-circle">
                        <input checked="false" type="checkbox" id="administrador">
                        <label for="administrador">
                            ¿Administrador?
                        </label>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="row">
                    <div class="col-xs-12">
                        <div hidden="hidden" class="alert">
                            <span id="mostrar_resultados">Hola</span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-6">
                        <button id="guardar_usuario" class="form-control btn btn-success">Guardar</button>
                    </div>
                    <div class="col-xs-6">
                        <button id="cancelar_confirmacion_eliminar" data-dismiss="modal"
                                class="form-control btn btn-warning">Cancelar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="modal_formulario_cambiar_pass" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" id="modal_change_password_title">Cambiar contraseña</h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="usuario">Nueva contraseña</label>
                    <input type="password" id="new_password" placeholder="Nueva contraseña" class="form-control">
                </div>
                <div class="form-group">
                    <label for="usuario">Repite la nueva contraseña</label>
                    <input type="password" id="confirm_new_password" placeholder="Confirmar contraseña" class="form-control">
                </div>
            </div>
            <div class="modal-footer">
                <div class="row">
                    <div class="col-xs-6">
                        <button id="cambiar_pass" class="form-control btn btn-success">Cambiar</button>
                    </div>
                    <div class="col-xs-6">
                        <button id="cancelar_confirmacion_eliminar" data-dismiss="modal"
                                class="form-control btn btn-warning">Cancelar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>