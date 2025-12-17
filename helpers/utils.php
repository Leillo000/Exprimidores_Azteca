<?php

function ObtenerFecha()
{
    // Se obtiene la fecha devolviendo un formato de año, mes, día, hora, minutos y segundos; así como está en la base de datos
    ini_set('date.timezone', 'America/Mexico_City');
    return date('Y-m-d H:i:s', time());
    // Devuelve un String
}

// POSTERIORMENTE AGREGAR OTRO ARGUMENTO A ESTE FILTRO, ES DECIR, que diga BuscarProducto($filtro, $producto)

function BuscarProducto($nombre_producto)
{
    // Selecciona la información del producto donde contenga el nombre del producto
    $query = "SELECT id_producto, 
    nombre_producto, precio_unitario, 
    peso FROM productos WHERE nombre_producto REGEXP '" . $nombre_producto . "' 
    ORDER BY nombre_producto";
    return $query;
}

function BuscarPieza($nombre_pieza)
{
    $query = "SELECT po.nombre_producto, ps.nombre_pieza, ps.peso, ps.id_pieza FROM piezas AS ps 
JOIN productos AS po ON po.id_producto = ps.id_producto 
WHERE po.nombre_producto REGEXP '". $nombre_pieza ."' ORDER BY po.nombre_producto";
    return $query;
}
