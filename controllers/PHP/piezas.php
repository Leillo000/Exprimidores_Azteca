<?php
include('../../config/connection.php');
include("../../helpers/singleton_connection.php");
if ($_SERVER['REQUEST_METHOD'] === 'GET' && !empty($_GET)) {

    $id_pieza = isset($_GET['id_pieza']) ? intval($_GET['id_pieza']) : 0;

    $stmt = $conexion->prepare('SELECT ps.id_pieza, po.nombre_producto, ps.nombre_pieza, ps.peso, po.id_producto 
FROM piezas AS ps 
JOIN productos AS po ON po.id_producto = ps.id_producto 
WHERE ps.id_pieza= ?');

    $stmt->bind_param('i', $id_pieza);

    if ($stmt->execute()) {
        $StmtResut = $stmt->get_result();
        $Response = $StmtResut->fetch_assoc();
        echo json_encode($Response);

    }  else {
        http_response_code(500);
        echo json_encode(['Status' => 'Error', 'Message' => 'Algo salió mal.']);
    } 
} else if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST)) {
        $db = Database::getDatabase();
        $id_pieza = isset($_POST['id_pieza']) ? intval($_POST['id_pieza']) : 0;
        $id_producto = isset($_POST['id_producto']) ? intval($_POST['id_producto']) : 0;
        $peso = isset($_POST['peso']) ? intval($_POST['peso']) : 0;

        if ($id_pieza <= 0 || $id_producto <= 0 || $peso <= 0) {
            echo json_encode(["status" => "error", "message" => "Valores inválidos"]);
            exit();
        }

        $db->doQuery("UPDATE piezas SET peso = ? WHERE id_pieza = ?", [$peso, $id_pieza]);
        $pesoTotalProducto = $db->doQuery("SELECT SUM(peso) AS peso_total FROM piezas WHERE id_producto = ?", [$id_producto]);
        $db->doQuery("UPDATE productos SET peso = ? WHERE id_producto = ? AND activo = 1", [$pesoTotalProducto[0]["peso_total"], $id_producto]);
        echo json_encode(["status" => "success"]);
        exit();
    }

?>