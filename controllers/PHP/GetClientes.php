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
    if($cliente){
        echo json_encode($cliente);
    } else{
        http_response_code(404);
        echo json_encode(["error" => "Cliente no encontrado."]);
    }

} else {
    // Método incorrecto
    http_response_code(405);
    echo json_encode([
        'error' => 'Método no permitido'
    ]);
}
?>