<?php
if (!isset($_SESSION)) exit("<script>window.location.href = '../';</script>");
?>
<link rel="stylesheet" href="./css/eac.css">
<div class="row">

    <div class="col-xs-12">
        <button class="form-control btn-success btn" id="terminar_alta">Terminar alta</button>
    </div>
    <div class="col-xs-12">
        <br>
        <input type="text" id="codigo_producto" class="form-control" placeholder="Nombre o código del producto">
    </div>
    <div class="col-xs-12">
        <br>
        <div class="table-responsive" id="contenedor_tabla">

        </div>
    </div>
</div>

<script src="./lib/eac.js"></script>
<script src="./js/alta-de-inventarios.js"></script>
