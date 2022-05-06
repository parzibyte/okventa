<?php
if (!isset($_SESSION)) exit("<script>window.location.href = '../';</script>");
?>
<?php if ($_SESSION["administrador"] !== 1) exit('<h1 class="text-center">Lo sentimos, solamente el administrador puede ver esta sección<br><br><i class="fa fa-hand-paper-o fa-4x"></i></h1>'); ?>
<div class="row visible-print-block">
    <h1 class="text-center">Reporte sobre inventarios</h1>
    <h3 class="text-center" id="fecha_hoy">ss</h3>
</div>
<div class="row hidden-print">
    <div class="col-xs-12">
        <p class="h5 text-justify">Elige el lapso de tiempo en el que quieres que se genere el reporte. Lo que veas aquí
            es lo mismo que aparecerá en él.
        </p>
    </div>
</div>
<div class="row hidden-print">
    <div class="col-xs-12">
        <div class="form-group">
            <label for="filtro">Ordenar por</label>
            <select class="form-control" name="filtro" id="filtro">
                <option value="codigo">Código</option>
                <option value="nombre">Nombre</option>
                <option value="precio_compra">Precio de compra</option>
                <option value="precio_venta">Precio de venta</option>
                <option value="utilidad">Utilidad</option>
                <option value="existencia">Existencia</option>
                <option value="familia">Familia</option>
            </select>
        </div>
    </div>
</div>
<div class="row hidden-print">
    <div class="col-xs-12">
        <div class="form-group">
            <label for="orden">Orden</label>
            <select class="form-control" name="orden" id="orden">
                <option value="desc">Ascendente</option>
                <option value="asc">Descendente</option>
            </select>
        </div>
    </div>
</div>
<br>
<div class="row text-center">
    <div class="col-xs-6">
        <h2 hidden="hidden"><strong>Total de productos:</strong> <span id="total_productos"></span></h2>
    </div>
    <div class="col-xs-6">
        <h2 hidden="hidden"><strong>Valor del inventario: </strong><span id="total_dinero"></span></h2>
    </div>
</div>
<div class="row hidden-print">
    <div class="col-xs-12">
        <button class="btn btn-info form-control" id="generar_reporte">Generar reporte <i class="fa fa-file-pdf-o"></i>
        </button>
    </div>
</div>
<div class="row"><br>
    <div class="col-xs-12">
        <div id="contenedor_tabla" class="table-responsive">
        </div>
    </div>
</div>
<script src="./js/reportes-inventarios.js"></script>