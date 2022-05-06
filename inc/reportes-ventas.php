<?php
if (!isset($_SESSION)) exit("<script>window.location.href = '../';</script>");
?>
<div class="row visible-print-block">
    <h1 class="text-center">Reporte sobre ventas</h1>
</div>
<div class="row hidden-print">
    <div class="col-xs-12">
        <p class="h5 text-justify">Elige el lapso de tiempo en el que quieres que se genere el reporte. Lo que veas aquí
            es lo mismo que aparecerá en él.<br>
        </p>
    </div>
</div>
<div class="row hidden-print">
    <div class="col-xs-12 col-sm-12 col-md-4 text-center">
        <div class="form-group">

            <label for="fecha_inicio">Del</label>
            <input id="fecha_inicio" type="datetime-local" class="form-control">
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-4 text-center">
        <div class="form-group">
            <label for="fecha_fin">Hasta</label>
            <input id="fecha_fin" type="datetime-local" class="form-control">
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-4 text-center">
        <div class="form-group">
            <label for="familia">Familia</label>
            <select class="form-control" name="familia" id="familia">
            </select>
        </div>
    </div>
</div>
<br>
<div class="row hidden-print">
    <div class="col-xs-12">
        <button class="btn btn-info form-control" id="generar_reporte">Generar reporte <i class="fa fa-file-pdf-o"></i>
        </button>
    </div>
</div>
<div class="row">
    <div class="col-xs-6">
        <h2 class="text-center" hidden="hidden"><strong>Total:</strong> $<span id="mostrar_total"></span></h2>
    </div>
    <div class="col-xs-6">
        <h2 class="text-center" hidden="hidden"><strong>Utilidad:</strong> $<span id="mostrar_utilidad"></span></h2>
    </div>
</div>

<div class="row"><br>
    <div class="col-xs-12 col-sm-12 col-md-3">
        <h3 class="text-center">Por familia</h3>
        <div class="table-responsive">
            <table class="table table-condensed table-bordered table-hover">
                <thead>
                <tr>
                    <th>Familia</th>
                    <th>Total</th>
                    <th>Utilidad</th>
                </tr>
                </thead>
                <tbody id="contenedor_tabla_por_familia">
                </tbody>
            </table>
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-9">
        <h3 class="text-center">General</h3>
        <div id="contenedor_tabla" class="table-responsive">
        </div>
    </div>
</div>
<script src="./js/reporte-ventas.js"></script>