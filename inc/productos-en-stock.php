<?php
if (!isset($_SESSION)) exit("<script>window.location.href = '../';</script>");
?>
<?php if ($_SESSION["administrador"] !== 1) exit('<h1 class="text-center">Lo sentimos, solamente el administrador puede ver esta sección<br><br><i class="fa fa-hand-paper-o fa-4x"></i></h1>'); ?>
<div class="col-xs-12">
    <h1>Productos en stock</h1>
    <h3>Aquí se muestran aquellos productos cuya cantidad es menor a la permitida</h3>
    <div class="form-group">

        <label for="familia">Familia o proveedor</label>
        <select name="familia" id="familia" class="form-control">

        </select>
    </div>
    <div class="table-responsive">
        <table class="table table-bordered table-condensed">
            <thead>
            <tr>
                <th>Código</th>
                <th>Nombre</th>
                <th>Existencia</th>
                <th>Existencia permitida</th>
                <th>Familia</th>
            </tr>
            </thead>
            <tbody id="cuerpo_tabla">

            </tbody>
        </table>
    </div>
</div>
<script src="./js/productos-en-stock.js" type="text/javascript"></script>