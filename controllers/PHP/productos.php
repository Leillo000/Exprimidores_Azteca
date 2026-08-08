<?php

include('../../config/connection.php');

if ($_SERVER['REQUEST_METHOD'] === 'GET' && !empty($_GET)) {

    $id_producto = isset($_GET['id_producto']) ? intval($_GET['id_producto']) : 0;

    $query_producto = $conexion->prepare(
        "SELECT * FROM productos WHERE id_producto = ?"
    );

    // Falla la conexión
    if ($query_producto === false) {
        die("Prepare failed: " . $conexion->error);
    }

    $query_producto->bind_param('i', $id_producto);
    $query_producto->execute();
    $res_producto = $query_producto->get_result();
    $producto = $res_producto->fetch_assoc();

    if ($producto) {
        echo json_encode($producto);
    } else {
        http_response_code(404);
        echo json_encode(["error" => "Producto no encontrado."]);
    }

} else if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST)) {
    // Se inicializan las variables desde $_POST
    $id_producto = isset($_POST['id_producto']) ? intval($_POST['id_producto']) : 0;
    $nombre_producto = isset($_POST['nombre']) ? trim($_POST['nombre']) : '';
    $precio = isset($_POST['precio']) ? doubleval($_POST['precio']) : 0;
    $peso = isset($_POST['peso']) ? intval($_POST['peso']) : 0;

    if (
        $id_producto <= 0
        || empty($nombre_producto)
        || empty($precio)
        || empty($peso)
    ) {
        echo json_encode(['status' => 'error', 'message' => 'Datos inválidos']);
    }

    $stmt = $conexion->prepare(
        'UPDATE productos SET 
        nombre_producto = ?, 
        precio_unitario = ?, 
        peso = ? 
        WHERE id_producto = ?'
    );

    $stmt->bind_param(
        "sdii",
        $nombre_producto,
        $precio,
        $peso,
        $id_producto
    );

    if($stmt -> execute()){
        echo json_encode(['status' => 'success', 'message' => 'Datos actualizados correctamente.']);
    } else {
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => 'Falló al actualizar.']);
    }
    // Si es que decidimos borrar datos
} else if ($_SERVER ['REQUEST_METHOD'] === 'DELETE') {
    
    $id_producto = isset($_GET['id_producto']) ? intval($_GET['id_producto']) : 0;

    $stmt = $conexion->prepare( "UPDATE productos SET activo = 0 WHERE id_producto = ?");

    $stmt->bind_param('i', $id_producto);
    
    if($stmt->execute()){
        echo json_encode(['Status' => 'success', 'Message' => 'La operación se realizó con éxito' ]);
    } else { 
        http_response_code(500);
        echo json_encode(['Status' => 'error', 'Message' => 'Hubo problemas durante la ejecución, no se eliminó apropiadamente.' ]);
    }
} 
else {
    http_response_code(405);
    echo json_encode([
        'error' => 'Método no permitido'
    ]);
}
?>