<?php
include("../config/connection.php");
include("../helpers/singleton_connection.php");
if($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST)){
    $db = Database :: getDatabase();
    $peso = intval($_POST['peso']);
    $nombre = trim($_POST['nombre_pieza']);
    $id_producto = $_POST['id_producto'];

    $stmt = $conexion->prepare(
        'INSERT INTO piezas(id_producto, nombre_pieza, peso)
    VALUES (?, ?, ?)');
    $stmt->bind_param("isi", $id_producto, $nombre, $peso);
    $stmt->execute();
    $pesoTotalProducto = $db->doQuery("SELECT SUM(peso) AS peso_total FROM piezas WHERE id_producto = ?", [$id_producto]);
    $updateProducto = $db->doQuery("UPDATE productos SET peso = ? WHERE id_producto = ?", [$pesoTotalProducto[0]["peso_total"], $id_producto]);
}

else
    header("Location: ../HTML/piezas.php");
?>