<?php
include("../helpers/singleton_connection.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST' || isset($_POST['id_pieza'])) {
    $db = Database :: getDatabase();
    $id_pieza = isset($_POST) ? intval($_POST['id_pieza']) : 0;

    if ($id_pieza <= 0) {
        header("Location: ../HTML/piezas.php?error=id_invalido");
        exit();
    }
    
    $db->doQuery("UPDATE piezas SET activo = 0 WHERE id_pieza = ?", [$id_pieza]);
    $idProducto = $db->doQuery("SELECT id_producto FROM piezas WHERE id_pieza = ?", [$id_pieza]);
    $nuevoPesoTotal = $db->doQuery("SELECT SUM(peso) AS peso_total FROM piezas WHERE id_producto = ? AND activo = 1", [$idProducto[0]["id_producto"]]);
    $db->doQuery("UPDATE productos SET peso = ? WHERE id_producto = ?", [$nuevoPesoTotal[0]["peso_total"], $idProducto[0]["id_producto"]]);
    echo "<script>alert('Operación exitosa')</script>";
    header("Location: ../HTML/piezas.php");
    exit();
} else {
    echo "<script>alert('Método inválido')</script>";
    header("Location: ../HTML/piezas.php");
    exit();
}
?>