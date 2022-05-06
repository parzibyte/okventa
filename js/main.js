/***
 *     ___ ___   ____  ____  ____
 *    |   T   T /    Tl    j|    \
 *    | _   _ |Y  o  | |  T |  _  Y
 *    |  \_/  ||     | |  | |  |  |
 *    |   |   ||  _  | |  | |  |  |
 *    |   |   ||  |  | j  l |  |  |
 *    l___j___jl__j__j|____jl__j__j
 *
 *    Última modificación: 09 de junio de 2016 por Parzibyte
 *    Entorno: Desarrollo
 */
$(document)
    .ready(function () {
        PNotify.prototype.options.styling = "fontawesome";
        $.fn.extend({
            animateCss: function (animationName) {
                var animationEnd = 'webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend';
                $(this).addClass('animated ' + animationName).one(animationEnd, function () {
                    $(this).removeClass('animated ' + animationName);
                });
            }
        });
    });

String.prototype.decodificar = function () {
    return JSON.parse(this);
};
String.prototype.termina_con = function (busqueda) {
    return busqueda === this.substring(this.length - busqueda.length, this.length);
};
function comprobar_productos_con_existencia_minima() {
    if (!window.location.href.termina_con("productos-en-stock") && !window.location.href.termina_con("inventarios")) {
        $.post("./modulos/inventario/comprobar_productos_con_existencia_minima.php", function (datos) {
            var numero_productos = parseInt(datos.decodificar());
            if (numero_productos > 0) {
                var a = new PNotify(
                    {
                        title: "Atención",
                        text: "<a style='color: inherit;' href='./productos-en-stock'>Hay algunos productos con existencia debajo de la permitida. Haga clic aquí para solucionar el problema</a>.",
                        type: "error",
                        delay: 2000
                    }
                );
            }
        });
    }

}
function validar_formulario(padre_formulario) {
    var focus_fue_dado = false,
        todos_los_campos_llenos = true;
    $(padre_formulario + " input").each(function () {
        if ($(this).data("requerido")) {
            if ($(this).val().length <= 0) {
                todos_los_campos_llenos = false;
                $(this).parent().addClass("has-error");
                $(this).animateCss("shake");
                if (!focus_fue_dado) {
                    $(this).focus();
                    focus_fue_dado = true;
                }
            }
        }
    });
    return todos_los_campos_llenos;
}