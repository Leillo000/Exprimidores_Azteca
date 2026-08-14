<?php
include('../config/connection.php');
include('../helpers/singleton_connection.php');

$db = Database::getDatabase();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST)) {

    $id_pedido = isset($_POST['id_pedido']) ? intval($_POST['id_pedido']) : 0;
    $id_producto = isset($_POST['id_producto']) ? intval($_POST['id_producto']) : 0;
    $id_pieza = isset($_POST['id_pieza']) ? intval($_POST['id_pieza']) : 0;
    $cantidad = isset($_POST['cantidad']) ? intval($_POST['cantidad']) : 0;

    // Mensaje de alerta
    if ($id_pedido <= 0 || $id_producto <= 0 || $id_pieza <= 0) {
        echo "<script>
    alert('Rellene los campos correctamente.');
    window.location.href = '../HTML/pedidos.php';</script>";
        exit();
    }

    // Verificar si existen observaciones del mismo tipo

    $cantidadAntigua = $db->doQuery("SELECT cantidad FROM detalles_observaciones WHERE id_pieza = ? AND id_pedido = ?", [$id_pieza, $id_pedido]);
    if ($cantidadAntigua) {
        $nuevaCantidad = intval($cantidadAntigua[0]["cantidad"]) + $cantidad ;
        $resultado = $db->doQuery("UPDATE detalles_observaciones SET cantidad = ? WHERE id_pieza = ? AND id_pedido = ?", [$nuevaCantidad , $id_pieza, $id_pedido]);
    } else {
        // Se insertan las observaciones 
        $db->doQuery("INSERT INTO detalles_observaciones(id_pedido, id_pieza, id_producto, cantidad) VALUES (?, ?, ?, ?)", [$id_pedido, $id_pieza, $id_producto, $cantidad]);

        // Se actualiza la tabla de pedidos para recalcar que ya se tienen observaciones de ese pedido
        $db->doQuery("UPDATE pedidos SET tipo_observacion = 'Faltan piezas', etapa = 'Fundición' WHERE id_pedido = ?",[$id_pedido]);
    }
        // Se redirige a pedidos.php automáticamente una vez que agrego la observación.
        echo "<script>
        alert('Observaciones actualizadas correctamente');
        window.location.href = '../HTML/pedidos.php';</script>";
}


?>