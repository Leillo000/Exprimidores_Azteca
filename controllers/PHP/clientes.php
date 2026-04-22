<?php

include('../../config/connection.php');
// Se hace una petición a PHP donde devuelva los datos de un cliente según su ID enviados desde
// clientes.php
// Recibir id de la cliente (desde la tabla en clientes.php)
// LOS MENSAJES DEBEN DE APARECER EN EL FRONTEND, ESTO SOLO ACTUA PARA PROCESAR DATOS.
// NO COMBINAR AQUÍ POR NADA JAVASCRIPT Y PHP, no debemos mezclar Backend y Frontend
if ($_SERVER['REQUEST_METHOD'] === 'GET' && !empty($_GET)) {

    // Recibir id de la cliente (desde la tabla en clientes.php)
    $id_cliente = isset($_GET['id_cliente']) ? intval($_GET['id_cliente']) : 0;

    $query_cliente = $conexion->prepare(
        'SELECT 
        e.nombre, e.rfc, e.correo, e.telefono, c.id_cliente
        FROM clientes AS c
        JOIN empresas AS e ON e.id_cliente = c.id_cliente
        WHERE c.id_cliente = ?'
    );

    // Falla la conexión
    if ($query_cliente === false) {
        die("Prepare failed: " . $conexion->error);
    }

    $query_cliente->bind_param('i', $id_cliente);
    $query_cliente->execute();
    $res_cliente = $query_cliente->get_result();
    $cliente = $res_cliente->fetch_assoc();

    // Si no existen registros del cliente seleccionado
    if ($cliente) {
        echo json_encode($cliente);
    } else {
        http_response_code(404);
        echo json_encode(["error" => "Cliente no encontrado."]);
    }

} else if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST)) {
    // Se inicializan las variables desde $_POST
    $id_cliente = isset($_POST['id_cliente']) ? intval($_POST['id_cliente']) : 0;
    $nombre_cliente = isset($_POST['nombre']) ? trim($_POST['nombre']) : '';
    $rfc = isset($_POST['rfc']) ? trim($_POST['rfc']) : '';
    $telefono = isset($_POST['telefono']) ? trim($_POST['telefono']) : '';
    $correo = isset($_POST['correo']) ? trim($_POST['correo']) : '';

    // Validar que no estén vacíos
    if (
        $id_cliente <= 0
        || empty($nombre_cliente)
        || empty($rfc)
        || empty($telefono)
        || empty($correo)
    ) {
        echo json_encode(['status' => 'error', 'message' => 'Datos inválidos']);
    }

    // Actualizar la cliente en la base de datos
    $stmt = $conexion->prepare(
        'UPDATE empresas SET 
        nombre = ?, 
        rfc = ?, 
        telefono = ?,  
        correo = ? 
        WHERE id_cliente = ?'
    );

    // Vincular los parámetros y ejecutar la consulta
    $stmt->bind_param(
        "ssssi",
        $nombre_cliente,
        $rfc,
        $telefono,
        $correo,
        $id_cliente
    );
    if($stmt -> execute()){
        echo json_encode(['status' => 'success', 'message' => 'Datos actualizados correctamente.']);
    } else {
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => 'Falló al actualizar.']);
    }
    // Si es que decidimos borrar datos
} else if ($_SERVER ['REQUEST_METHOD'] === 'DELETE') {
    
// Se verifica primer que el String sea válido para poder continuar en la petición de DELETE
    $id_cliente = isset($_GET['id_cliente']) ? intval($_GET['id_cliente']) : 0;

    $stmt = $conexion->prepare( "
    UPDATE empresas SET activo = 0 WHERE id_cliente = ?"
    );

    $stmt->bind_param('i', $id_cliente);
    
    if($stmt->execute()){
        echo json_encode(['Status' => 'success', 'Message' => 'La operación se realizó con éxito' ]);
    } else { 
        http_response_code(500);
        echo json_encode(['Status' => 'error', 'Message' => 'Hubo problemas durante la ejecución, no se eliminó apropiadamente.' ]);
    }
} 
else {
    // Método incorrecto
    http_response_code(405);
    echo json_encode([
        'error' => 'Método no permitido'
    ]);
}
?>