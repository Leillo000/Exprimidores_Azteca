<?php
include("../config/connection.php");
if($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST)){

    $peso = intval($_POST['peso']);
    $nombre = trim($_POST['nombre_pieza']);
    $producto = $_POST['id_producto'];

    $stmt = $conexion->prepare(
        'INSERT INTO piezas(id_producto, nombre_pieza, peso)
    VALUES (?, ?, ?)');
    $stmt->bind_param("isi", $producto, $nombre, $peso);
    $stmt->execute();
    header("Location: ../HTML/piezas.php");
}
else
    header("Location: ../HTML/piezas.php");
?>