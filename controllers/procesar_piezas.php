<?php
include("../helpers/singleton_connection.php");
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST)) {
    $db = Database::getDatabase();
    $peso = isset($_POST['peso']) ? intval($_POST['peso']) : 0;
    ;
    $nombre = isset($_POST['nombre_pieza']) ? trim($_POST['nombre_pieza']) : "";
    $id_producto = isset($_POST['id_producto']) ? intval($_POST['id_producto']) : 0;

    if ($id_producto <= 0 || empty($nombre)) {
        echo "<script>window.location.href = '../HTML/piezas.php'</script>";
        exit();
    }

    $db->doQuery("INSERT INTO piezas(id_producto, nombre_pieza, peso)
    VALUES (?, ?, ?)", [$id_producto, $nombre, $peso]);

    $pesoTotalProducto = $db->doQuery("SELECT SUM(peso) AS peso_total FROM piezas WHERE id_producto = ? AND activo = 1", [$id_producto]);
    $updateProducto = $db->doQuery("UPDATE productos SET peso = ? WHERE id_producto = ?", [$pesoTotalProducto[0]["peso_total"], $id_producto]);
    echo "<script>window.location.href = '../HTML/piezas.php'</script>";
} else
    header("Location: ../HTML/piezas.php");
?>