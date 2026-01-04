<?php
include("../../config/connection.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST)) {

    // Se inicializan las variables desde $_POST
    $id_cliente = isset($_POST['id_cliente']) ? intval($_POST['id_cliente']) : 0;
    $nombre_cliente = isset($_POST['nombre_cliente']) ? trim($_POST['nombre_cliente']) : '';
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
        header("Location: ../../HTML/clientes.php?error=datos_invalidos");
        exit();
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

    // Verificar si la preparación fue exitosa
    if ($stmt === false) {
        die("Prepare failed: " . $conexion->error);
    }

    // Vincular los parámetros y ejecutar la consulta
    $stmt->bind_param(
        "ssssi",
        $nombre_cliente,
        $rfc,
        $telefono,
        $correo,
        $id_cliente
    );

    if ($stmt->execute()) {
        header("Location: ../../HTML/clientes.php?success=actualizado");
        exit();
    } else {
        header("Location: ../../HTML/clientes.php?error=actualizar_fallo");
        exit();
    }
} else {
    header("Location: ../../HTML/clientes.php");
    exit();
}
?>