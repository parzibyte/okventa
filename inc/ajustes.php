<?php
if (!isset($_SESSION)) exit("<script>window.location.href = '../';</script>");
?>
<div class="row">
    <div class="col-xs-12">
        <h3 class="text-center">Impresora para tickets <i class="fa fa-print"></i></h3>
        <h5><strong>Nombre de la impresora actualmente seleccionada:</strong> <span id="impresora_seleccionada"></span></h5>
        <div class="form-group">
            <label for="impresoras">
                <strong>Nota: </strong> Por favor no selecciones aquellas impresoras que conviertan el archivo a PDF,
                XPS o algo parecido. Elige solamente impresoras físicas.
            </label>
            <select class="form-control" disabled name="impresoras" id="impresoras">
                <option value="0">Cargando...</option>
            </select>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-xs-12">
        <h3 class="text-center">Tema <i class="fa fa-paint-brush"></i></h3>
        <div class="form-group">
            <label for="cambiar_tema">
                Cambiar tema
            </label>
            <select class="form-control" name="cambiar_tema" id="cambiar_tema">
                <option value="2">Cosmo</option>
                <option value="3">Cyborg</option>
                <option value="5">Flatly</option>
                <option value="6">Journal</option>
                <option value="7">Lumen</option>
                <option value="8">Paper</option>
                <option value="9">Readable</option>
                <option value="10">Sandstone</option>
                <option value="11">Simplex</option>
                <option value="12">Slate</option>
                <option value="15">United</option>
                <option value="16">Yeti</option>
            </select>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-xs-12">
        <h3 class="text-center">Datos de la empresa <i class="fa fa-building"></i></h3>
        <p>Si no tienes algún dato o no quieres ponerlo, no importa, deja el campo vacío.</p>
    </div>
    <div class="col-xs-12">
        <div class="form-group">
            <label for="nombre">Nombre</label><input placeholder="Nombre" type="text" id="nombre" class="form-control">
        </div>
        <div class="form-group">
            <label for="nombre">Teléfono</label><input placeholder="Teléfono" type="tel" id="telefono"
                                                       class="form-control">
        </div>
        <div class="form-group">
            <label for="rfc">RFC</label><input placeholder="RFC" type="text" id="rfc" class="form-control">
        </div>
        <div class="form-group">
            <label for="direccion">Dirección</label><input placeholder="Dirección" type="text" id="direccion"
                                                           class="form-control">
        </div>
        <div class="form-group">
            <label for="colonia">Colonia</label><input placeholder="Colonia" type="text" id="colonia"
                                                       class="form-control">
        </div>
        <div class="form-group">
            <label for="cp">Código postal</label><input placeholder="Código postal" type="text" id="cp"
                                                        class="form-control">
        </div>
        <div class="form-group">
            <label for="cp">Ciudad</label><input placeholder="Ciudad" type="text" id="ciudad" class="form-control">
        </div>
    </div>
</div>
<div class="row">
    <div class="col-xs-12">
        <div class="col-xs-12">
            <button id="guardar_datos" class="form-control btn btn-success">
                Guardar
            </button>
        </div>
    </div>
</div><br>
<div class="row"></div><br>
<script src="./js/ajustes.js"></script>