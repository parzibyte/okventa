<?php
if (!isset($_SESSION)) exit("<script>window.location.href = '../';</script>");
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-xs-12">
            <button id="agregar_cliente" class="btn form-control btn-success"><i class="fa fa-plus"></i> Agregar cliente
            </button>
            <br><br>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <label for="input_buscar">Buscar</label>
            <input type="text" id="input_buscar" placeholder="Buscar por nombre, id o tipo" class="form-control"> <br>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <div class="table-responsive" id="contenedor_tabla_clientes">
                <table class="table table-hover table-bordered">
                    <thead>
                    <tr>
                        <th>Id</th>
                        <th>Nombre</th>
                        <th>Dirección</th>
                        <th>Colonia</th>
                        <th>CP</th>
                        <th>RFC</th>
                        <th>Teléfono 1</th>
                        <th>Teléfono 2</th>
                        <th>Tipo</th>
                        <th>Editar</th>
                        <th>Eliminar</th>
                    </tr>
                    </thead>
                    <tbody id="tabla_mostrar_clientes">

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="./js/clientes.js"></script>

<!-- Trigger the modal with a button -->

<!-- Modal -->
<div id="modal_editar_agregar_cliente" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Agregar cliente</h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="nombre_cliente">Nombre</label>
                    <input data-requerido="true" id="nombre_cliente" class="form-control" type="text"
                           placeholder="Nombre del cliente">
                </div>
                <div class="form-group">
                    <label for="direccion_cliente">Dirección</label>
                    <input data-requerido="true" id="direccion_cliente" class="form-control" type="text"
                           placeholder="Dirección">
                </div>
                <div class="form-group">
                    <label for="colonia_cliente">Colonia</label>
                    <input data-requerido="true" id="colonia_cliente" class="form-control" type="text"
                           placeholder="Colonia">
                </div>
                <div class="form-group">
                    <label for="cp_cliente">Código postal</label>
                    <input data-requerido="true" id="cp_cliente" class="form-control" type="text"
                           placeholder="Código postal">
                </div>
                <div class="form-group">
                    <label for="rfc_cliente">RFC</label>
                    <input data-requerido="true" id="rfc_cliente" class="form-control" type="text" placeholder="RFC">
                </div>
                <div class="form-group">
                    <label for="tel1_cliente">Teléfono 1</label>
                    <input data-requerido="true" id="tel1_cliente" class="form-control" type="tel"
                           placeholder="Teléfono 1">
                </div>
                <div class="form-group">
                    <label for="tel2_cliente">Teléfono 2</label>
                    <input data-requerido="true" id="tel2_cliente" class="form-control" type="tel"
                           placeholder="Teléfono 2">
                </div>
                <div class="form-group">
                    <label for="tipo_cliente">Tipo</label>
                    <input data-requerido="true" id="tipo_cliente" class="form-control" type="text" placeholder="Tipo">
                </div>

                <div id="mostrar-resultados-transaccion" hidden="hidden" class="alert alert-success"></div>
            </div>
            <div class="modal-footer">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="btn-group">
                            <button id="guardar_cliente" type="button" class=" btn btn-success">Guardar</button>
                            <button id="guardar_insertar_cliente" type="button" class="btn btn-info">Guardar e insertar
                                otro
                            </button>
                            <button type="button" class="btn btn-warning" data-dismiss="modal">Cancelar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>