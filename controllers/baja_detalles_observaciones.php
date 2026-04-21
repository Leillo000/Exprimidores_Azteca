<?php
// Desarrollo: mostrar errores

include('../config/connection.php');

// Verificación de que el ID sea válido
$id_detalle_observacion = isset($_GET['id_detalle_observacion']) ? intval($_GET['id_detalle_observacion']) : 0;
$id_pedido = isset($_GET['id_pedido']) ? intval($_GET['id_pedido']) : 0;

if ($id_detalle_observacion <= 0) {
    header("Location : '../HTML/detalles_observaciones.php'");
}

// Se da de baja la observacion
$stmt_baja = $conexion->prepare('DELETE FROM detalles_observaciones WHERE id_detalle_observacion = ?');
$stmt_baja->bind_param('i', $id_detalle_observacion);
$stmt_baja->execute();

// Se verifica que cuando ya no haya detalles, los observaciones cambien a 'Ninguna'
$stmt_verificar = $conexion->prepare('SELECT id_pedido FROM detalles_observaciones WHERE id_pedido = ?');
$stmt_verificar->bind_param('i', $id_pedido);
$stmt_verificar->execute();
$res = $stmt_verificar->get_result();
$verificar_detalles = $res->fetch_assoc();

// Actualiza las columnas si ya no hay detalles del pedido
if (empty($verificar_detalles) || $verificar_detalles === null ) {
    // Se actualizan los registros del pedido si es que ya no tiene observaciones
    $stmt_actualizar = $conexion->prepare("UPDATE pedidos SET tipo_observacion = 'Ninguna' WHERE id_pedido = ?");
    $stmt_actualizar->bind_param('i', $id_pedido);
    $stmt_actualizar->execute();
}

header("Location: ../HTML/detalles_observaciones.php?id_pedido= + $id_pedido");
?>