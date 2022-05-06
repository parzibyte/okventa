/**
 * Created by parzibyte on 01/08/2016.
 */
$(principal);
function principal() {
    consulta_todo();
    escuchar_elementos();
}
function consulta_todo() {
    consulta_familias(function () {
        consultar_todos_los_productos_en_stock($("#familia").val());
    });
}
function consultar_todos_los_productos_en_stock(familia) {
    $.post("./modulos/inventario/consultar_todos_los_productos_en_stock.php",
        {
            familia: familia
        },
        function (respuesta) {
            dibujar_tabla(respuesta.decodificar());
        });
}
function consulta_familias(callback) {
    $.get("./modulos/familias/consultar_familias_en_productos.php", function (familias) {
        var f = JSON.parse(familias);
        llenar_select(f);
        callback();
    });
}
function escuchar_elementos() {
    $("#familia").change(function () {
        consultar_todos_los_productos_en_stock($(this).val());
    });
}
function llenar_select(familias) {
    var $contenedorFamilia = $("#familia");
    $contenedorFamilia
        .empty()
        .append(
            $("<option>", {
                val: "*",
                text: "--Todas--"
            })
        );
    familias.forEach(function (familia) {
        $contenedorFamilia.append(
            $("<option>", {
                val: familia.familia,
                text: familia.familia
            })
        );
    });
}
function dibujar_tabla(datos) {
    var longitud = datos.length,
        $cuerpoTabla = $("#cuerpo_tabla");
    $cuerpoTabla.empty();
    for (var x = 0; x < longitud; x++) {
        $cuerpoTabla.append(
            $("<tr>")
                .append(
                    $("<td>").html(datos[x].codigo),
                    $("<td>").html(datos[x].nombre),
                    $("<td>").html(datos[x].existencia),
                    $("<td>").html(datos[x].stock),
                    $("<td>").html(datos[x].familia)
                )
        );
    }

}