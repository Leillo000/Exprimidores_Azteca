<?php
include("../../config/connection.php");
include('../../helpers/utils.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST)) {

    $fecha = ObtenerFecha();
    $nombre = isset($_POST['nombre']) ? trim($_POST['nombre']) : '';
    $rfc = isset($_POST['rfc']) ? trim($_POST['rfc']) : '';
    $correo = isset($_POST['correo']) ? trim($_POST['correo']) : '';
    $telefono = isset($_POST['telefono']) ? trim($_POST['telefono']) : '';
    $tipo_cliente = 'empresa';


    $stmt_clientes = $conexion->prepare(
        'INSERT INTO clientes(tipo_cliente, fecha_registro)
    VALUES (?, ?)'
    );
    $stmt_clientes->bind_param("ss", $tipo_cliente, $fecha);
    $stmt_clientes->execute();

    $obtenerUltimoId = $conexion->query("SELECT id_cliente FROM clientes ORDER BY fecha_registro DESC LIMIT 0,1");
    $IdCliente = $obtenerUltimoId -> fetch_assoc();

    $stmt_empresa = $conexion->prepare(
        'INSERT INTO empresas(id_cliente, nombre, rfc, correo, telefono)
    VALUES (?, ?, ?, ?, ?)'
    );
    $stmt_empresa->bind_param("issss", $IdCliente['id_cliente'], $nombre, $rfc, $correo, $telefono);
    $stmt_empresa->execute();



    header("Location: ../../HTML/clientes.php");
} else
    header("Location: ../../HTML/clientes.php");
?>