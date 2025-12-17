<?php
include('../config/connection.php');
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST)) {

    // Se selecciona lo que se quiera hacer mediante el boton
    $accion = isset($_POST['accion']) ? $_POST['accion'] : '';

    // ===== INSERTAR OBSERVACION =====

    if ($accion === 'agregar') {
        // Validaciones en caso de no mandar correctamente los datos 
        $id_pedido = isset($_POST['id_pedido']) ? intval($_POST['id_pedido']) : 0;
        $id_producto = isset($_POST['id_producto']) ? intval($_POST['id_producto']) : 0;
        $id_pieza = isset($_POST['id_pieza']) ? intval($_POST['id_pieza']) : 0;
        $cantidad = isset($_POST['cantidad']) ? intval($_POST['cantidad']) : 0;

        if ($id_pedido <= 0 || $id_producto <= 0 || $id_pieza <= 0) {
            // Mensaje de alerta en caso de no cumplir las condiciones
            echo "<script>
    alert('Datos inválidos.');
    window.location.href = '../HTML/agregar_observaciones.php';</script>";
            exit();
        }

        // Se insertan las observaciones 

        $stmt_observaciones = $conexion->prepare("INSERT INTO detalles_observaciones(id_pedido, id_pieza, id_producto, cantidad)
    VALUES (?, ?, ?, ?)");
        $stmt_observaciones->bind_param('iiii', $id_pedido, $id_pieza, $id_producto, $cantidad);
        $stmt_observaciones->execute();

        // Se actualiza la tabla de pedidos para recalcar que ya se tienen observaciones de ese pedido

        $stmt_actualizar_pedido = $conexion->prepare("UPDATE pedidos SET tipo_observacion = 'Faltan piezas', etapa = 'Fundición' WHERE id_pedido = ?");
        $stmt_actualizar_pedido->bind_param('i', $id_pedido);
        $stmt_actualizar_pedido->execute();

        // Se redirige a pedidos.php automáticamente una vez que agrego la observación.

        echo "<script>
        alert('Observación agregada exitosamente');
        window.location.href = '../HTML/pedidos.php';</script>";
    }
}


?>