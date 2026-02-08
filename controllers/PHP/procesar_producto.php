<?php
include("../../config/connection.php");
include('../../helpers/utils.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST)) {

    $nombre = isset($_POST['nombre']) ? trim($_POST['nombre']) : '';
    $precio_unitario = isset($_POST['precio_unitario']) ? intval($_POST['precio_unitario']) : 0;
    $peso = isset($_POST['peso']) ? trim($_POST['peso']) : 0;


    $stmt_productos = $conexion->prepare(
        'INSERT INTO productos(nombre_producto, precio_unitario, peso)
    VALUES (?, ?, ?)'
    );
    $stmt_productos->bind_param("sdi", $nombre, $precio_unitario, $peso);
    $stmt_productos->execute();

    header("Location: ../../HTML/productos.php");
} else
    header("Location: ../../HTML/productos.php");
?>