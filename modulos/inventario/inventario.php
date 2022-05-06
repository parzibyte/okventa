<?php
#   ____  ____   __ __    ___  ____   ______   ____  ____   ____  ___  
#  l    j|    \ |  T  |  /  _]|    \ |      T /    T|    \ l    j/   \ 
#   |  T |  _  Y|  |  | /  [_ |  _  Y|      |Y  o  ||  D  ) |  TY     Y
#   |  | |  |  ||  |  |Y    _]|  |  |l_j  l_j|     ||    /  |  ||  O  |
#   |  | |  |  |l  :  !|   [_ |  |  |  |  |  |  _  ||    \  |  ||     |
#   j  l |  |  | \   / |     T|  |  |  |  |  |  |  ||  .  Y j  ll     !
#  |____jl__j__j  \_/  l_____jl__j__j  l__j  l__j__jl__j\_j|____j\___/ 
#                                                                      
# Autor: parzibyte
# Última modificación: 06 de junio de 2016

function consultar_valor_del_inventario()
{
    global $base_de_datos;
    $sentencia = $base_de_datos->prepare("SELECT sum(precio_venta * existencia) AS valor_inventario FROM inventario");
    $sentencia->execute();
    $fila = $sentencia->fetch();
    return $fila["valor_inventario"];
}

function buscar_producto($busqueda, $limite, $offset)
{
    global $base_de_datos;
    $sentencia = $base_de_datos->prepare("SELECT * FROM inventario WHERE codigo LIKE ? OR nombre LIKE ? ORDER BY rowid DESC LIMIT ? OFFSET ?;");
    $sentencia->execute(["%" . $busqueda . "%", "%" . $busqueda . "%", $limite, $offset]);
    return $sentencia->fetchAll();
}


function dar_alta_productos($productos)
{
    $r = true;
    foreach ($productos as $producto) {
        $r = $r and agregar_piezas($producto->cantidad, $producto->rowid);
    }
    return $r;
}


function comprobar_productos_con_existencia_minima()
{
    global $base_de_datos;
    $sentencia = $base_de_datos->prepare("SELECT count(rowid) AS cantidad FROM inventario WHERE existencia <= stock;");
    $sentencia->execute();
    $datos = $sentencia->fetch();
    return $datos["cantidad"];
}

function consultar_todos_los_productos_en_stock($familia)
{
    global $base_de_datos;
    if ($familia === "*") {
        $sql = "SELECT codigo, nombre, existencia, stock, familia FROM inventario WHERE existencia < stock ORDER BY existencia DESC";
    } else {
        $sql = "SELECT codigo, nombre, existencia, stock, familia FROM inventario WHERE existencia < stock AND familia = ? ORDER BY existencia DESC";
    }
    $sentencia = $base_de_datos->prepare($sql);
    if ($familia === "*") $sentencia->execute();
    else $sentencia->execute(array($familia,
    ));
    return $sentencia->fetchAll();
}

function existe_codigo_producto($codigo_producto, $rowid = false)
{
    global $base_de_datos;
    $cadena_consulta = "SELECT count(codigo) AS count FROM inventario WHERE rowid <> ? AND codigo = ?;";
    if ($rowid === false) $cadena_consulta = "SELECT count(codigo) AS count FROM inventario WHERE codigo = ?;";
    $sentencia = $base_de_datos->prepare($cadena_consulta);
    if ($rowid !== false) {
        $sentencia->execute([$rowid, $codigo_producto]);
    } else {
        $sentencia->execute([$codigo_producto]);
    }
    $fila = $sentencia->fetch();
    return (bool)($fila["count"] >= 1);
}


function dar_baja($rowid, $numero_piezas, $razon_baja, $usuario)
{
    global $base_de_datos;
    $datos_producto = consultar_producto($rowid);
    $codigo_producto = $datos_producto["codigo"];
    $nombre_producto = $datos_producto["nombre"];
    $sentencia = $base_de_datos->prepare("INSERT INTO bajas_inventario (codigo_producto, nombre_producto, numero_piezas, razon_baja, usuario, fecha) VALUES (?, ?, ?, ?, ?, ?);");
    $resultado_sentencia = $sentencia->execute([$codigo_producto, $nombre_producto, $numero_piezas, $razon_baja, $usuario, date("Y-m-d H:i:s")]);
    return $resultado_sentencia and retirar_producto($rowid, $numero_piezas);
}


function retirar_producto($rowid, $cantidad)
{
    global $base_de_datos;
    $numero_piezas_anterior = consultar_piezas_producto($rowid);
    $piezas_nuevas = $numero_piezas_anterior - $cantidad;
    $sentencia = $base_de_datos->prepare("UPDATE inventario SET existencia = ? WHERE rowid = ?");
    return $sentencia->execute([$piezas_nuevas, $rowid]);
}


function editar_producto($codigo_producto, $nombre_producto, $precio_compra, $precio_venta, $utilidad, $existencia, $stock, $familia, $rowid)
{
    if (existe_codigo_producto($codigo_producto, $rowid)) return 2;
    global $base_de_datos;
    $sentencia = $base_de_datos->prepare("UPDATE inventario SET codigo = ?, nombre = ?, precio_compra = ?, precio_venta = ?, utilidad = ?, existencia = ?, stock = ?, familia = ? WHERE rowid = ?");
    $resultado_sentencia = $sentencia->execute([$codigo_producto, $nombre_producto, $precio_compra, $precio_venta, $utilidad, $existencia, $stock, $familia, $rowid]);
    if ($resultado_sentencia) return 0;
    else return 1;
}

function consultar_producto($rowid)
{
    global $base_de_datos;
    $sentencia = $base_de_datos->prepare("SELECT * FROM inventario WHERE rowid = ? LIMIT 1;");
    $sentencia->execute([$rowid]);
    return $sentencia->fetch();
}


function agregar_piezas($numero_piezas, $rowid)
{
    global $base_de_datos;
    $numero_piezas_final = consultar_piezas_producto($rowid) + $numero_piezas;
    $sentencia = $base_de_datos->prepare('
		UPDATE inventario 
		SET existencia = ? 
		WHERE rowid = ?');
    return $sentencia->execute([$numero_piezas_final, $rowid]);
}


function quitar_piezas($numero_piezas, $rowid)
{
    global $base_de_datos;
    $numero_piezas_final = consultar_piezas_producto($rowid) - $numero_piezas;
    $sentencia = $base_de_datos->prepare('
		UPDATE inventario 
		SET existencia = :numero_piezas 
		WHERE rowid = :rowid');
    $sentencia->bindParam(":numero_piezas", $numero_piezas_final);
    $sentencia->bindParam(":rowid", $rowid);
    return $sentencia->execute();
}


function consultar_piezas_producto($rowid)
{
    global $base_de_datos;
    $sentencia = $base_de_datos->prepare("SELECT existencia FROM inventario WHERE rowid = ?;");
    $sentencia->execute([$rowid]);
    $fila = $sentencia->fetch();
    return $fila["existencia"];
}

function eliminar_producto($rowid)
{
    global $base_de_datos;
    $sentencia = $base_de_datos->prepare("DELETE FROM inventario WHERE rowid = ?");
    return $sentencia->execute([$rowid]);
}

function consultar_todos_los_productos($limite, $offset)
{
    global $base_de_datos;
    $sentencia = $base_de_datos->prepare("SELECT * FROM inventario ORDER BY rowid DESC LIMIT ? OFFSET ?;");
    $sentencia->execute([$limite, $offset]);
    return $sentencia->fetchAll();
}


function consultar_todos_los_productos_reportes($filtro = "nombre", $orden = "asc")
{
    global $base_de_datos;
    $filtros_permitidos = ["codigo", "nombre", "precio_compra", "precio_venta", "utilidad", "existencia", "familia"];
    if (!($orden === "asc" or $orden === "desc")) exit("ss");//$orden = "asc";
    if (!in_array($filtro, $filtros_permitidos)) $filtro = "nombre";
    $sentencia = $base_de_datos->prepare("select * from inventario order by $filtro $orden;");
    $sentencia->execute();
    return $sentencia->fetchAll();

}


function consultar_numero_total_productos()
{
    global $base_de_datos;
    $sentencia = $base_de_datos->prepare("SELECT count(codigo) AS count FROM inventario;");
    $sentencia->execute();
    $fila = $sentencia->fetch();
    return $fila["count"];
}


function consultar_numero_total_productos_busqueda($busqueda)
{
    global $base_de_datos;
    $sentencia = $base_de_datos->prepare("SELECT count(codigo) AS count FROM inventario WHERE codigo LIKE ? OR nombre LIKE ?;");
    $busqueda = "%$busqueda%";
    $sentencia->execute([$busqueda, $busqueda]);
    $fila = $sentencia->fetch();
    return $fila["count"];
}


function insertar_producto($codigo_producto, $nombre_producto, $precio_compra, $precio_venta, $utilidad, $inventario_inicial, $stock, $familia)
{
    if (existe_codigo_producto($codigo_producto)) return 2;
    global $base_de_datos;
    $sentencia = $base_de_datos->prepare("
	INSERT INTO inventario 
	(codigo, nombre, precio_compra, precio_venta, utilidad, existencia, stock, familia)
	VALUES
	(?,?,?,?,?,?,?,?);
	");
    $resultado_sentencia = $sentencia->execute([$codigo_producto, $nombre_producto, $precio_compra, $precio_venta, $utilidad, $inventario_inicial, $stock, $familia]);
    if ($resultado_sentencia === TRUE) return 0;
    return 1;
}