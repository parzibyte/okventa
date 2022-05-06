<?php
if (!isset($_SESSION)) exit("<script>window.location.href = '../';</script>");
?>
<nav class="navbar navbar-default">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Intercambiar navegación</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#" id="marca_del_producto"><?php echo NOMBRE_NEGOCIO ?></a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
                <li id="elem_ventas"><a href="./ventas"><i class="fa fa-usd"></i> Ventas</a></li>
                <li id="elem_inventarios"><a href="./inventarios"><i class="fa fa-book"></i> Inventarios</a></li>
                <li id="elem_caja"><a href="./caja"><i class="fa fa-money"></i> Caja</a></li>
                <li id="elem_gastos"><a href="./gastos"><i class="fa fa-calculator"></i> Gastos</a></li>
                <li id="elem_alta_inventarios"><a href="./alta-de-inventarios"><i class="fa fa-angle-double-up"></i>
                        Alta de inventarios</a></li>
                <li id="elem_reportes" class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa fa-file-pdf-o"></i> Reportes <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="./reportes-inventarios">Inventarios</a></li>
                        <li><a href="./reportes-bajas-inventario">Bajas de inventario</a></li>
                        <li><a href="./reportes-caja">Caja</a></li>
                        <li><a href="./reportes-ventas">Ventas</a></li>
                        <li><a href="./reportes-gastos">Gastos</a></li>
                        <li><a href="./productos-en-stock">Productos en stock</a></li>
                    </ul>
                </li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li id="elem_ajustes" class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa fa-cog"></i> Ajustes <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="./ajustes"><i class="fa fa-cogs"></i> Generales</a></li>
                        <li><a href="./usuarios"><i class="fa fa-user"></i> Usuarios</a></li>
                    </ul>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?php inicia_sesion_segura();
                                                                                                                                        echo $_SESSION["nombre_de_usuario"] . " ";
                                                                                                                                        echo (intval($_SESSION["administrador"]) === 1) ? '<i class="fa fa-unlock"></i>' : '<i class="fa fa-lock"></i>'; ?>
                        <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a id="ajustes" href="./modulos/usuarios/cerrar_sesion.php"><i class="fa fa-sign-out"></i>
                                Cerrar sesión</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>
<script>
    $(document).ready(function() {
        comprobar_productos_con_existencia_minima();
        var veces_tocado = 0,
            ayudante_timeout;
        $("#marca_del_producto").click(function(e) {
            veces_tocado++;
            if (veces_tocado >= 3) {
                $("#modal_acerca_de").modal("show");
            }
            ayudante_timeout = setTimeout(function() {
                veces_tocado = 0;
                clearTimeout(ayudante_timeout);
            }, 500);
            e.preventDefault();
        });
    });

    function abrir_cajon() {
        console.info("Abriendo cajón...");
        $.get("./modulos/abrir_cajon.php");
    }
</script>

<div id="modal_acerca_de" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Acerca de</h4>
            </div>
            <div class="modal-body">
                <div class="well">
                    <h1>OkVenta
                        <small>v1.0</small>
                    </h1>
                    <br>
                    <h2>Desarrollado y mantenido por <a target="_blank" href="https://parzibyte.me/blog">Parzibyte</a></h2>
                </div>
            </div>
            <div class="modal-footer">
                <div class="col-xs-12">
                    <button data-dismiss="modal" class="form-control btn btn-success">Cerrar</button>
                </div>

            </div>
        </div>
    </div>
</div>