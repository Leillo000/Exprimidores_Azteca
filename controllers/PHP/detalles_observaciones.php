<?php
include("../../helpers/singleton_connection.php");
include("../../helpers/utils.php");
$db = Database::getDatabase();

if ($_SERVER['REQUEST_METHOD'] === "GET" && !empty($_GET)) {
    $id_detalle_observacion = isset($_GET['id_detalle_observacion']) ? intval($_GET['id_detalle_observacion']) : 0;
    $resultado = $db->doQuery("SELECT dto.cantidad, pz.peso, pz.nombre_pieza, pto.nombre_producto
    FROM detalles_observaciones AS dto
    JOIN piezas AS pz ON pz.id_pieza = dto.id_pieza
    JOIN productos AS pto ON pz.id_producto = pto.id_producto
    WHERE id_detalle_observacion = ?", [$id_detalle_observacion]);
   
    echo json_encode([
        "status" => "success",
        "cantidad" => $resultado[0]["cantidad"],
        "id" => $id_detalle_observacion, 
        "peso" => $resultado[0]["peso"],
        "nombre_producto" => $resultado[0]["nombre_producto"],
        "nombre_pieza" => $resultado[0]["nombre_pieza"]
        ]);

    } else if ($_SERVER['REQUEST_METHOD'] === "POST" && !empty($_POST)) {

    $id_detalle_observacion = isset($_POST["id_detalle_observacion"]) ? intval($_POST["id_detalle_observacion"]) : 0;
    $id_pedido = isset($_POST["id_pedido"]) ? intval($_POST["id_pedido"]) : 0;
    $cantidad_requerida = isset($_POST["cantidad"]) ? intval($_POST["cantidad"]) : 0;
    $cantidad_max = isset($_POST["cantidad_max"]) ? intval($_POST["cantidad_max"]) : 0;
    $peso = isset($_POST["peso"]) ? intval($_POST["peso"]) : 0;
    $nombreProducto = isset($_POST["nombre_producto"]) ? trim($_POST["nombre_producto"]) : "";
    $nombrePieza = isset($_POST["nombre_pieza"]) ? trim($_POST["nombre_pieza"]) : "";
    

    $stock_aluminio = $db->doQuery("SELECT cantidad_kg FROM stock_aluminio ORDER BY fecha DESC LIMIT 1");

    // Verificar si hay suficiente aluminio
    if ($stock_aluminio[0]["cantidad_kg"] <= (($cantidad_requerida * $peso) / 1000)){
        echo json_encode(["status" => "alert", "message" => "no hay suficiente alumuinio"]);
        exit();
    }

    if ($id_detalle_observacion <= 0) {
        echo json_encode(["status" => "error", "message" => "id invalido", "id" => $_POST["cantidad"]]);
        exit();
    }

        $query =  "INSERT INTO stock_aluminio (cantidad_kg, fecha, tipo, descripcion) VALUES (?, ?, ?, ?)";
        $pesoSalida = (($cantidad_requerida * $peso) / 1000) * 1.10;
        $resultadoObtenido = $stock_aluminio[0]["cantidad_kg"] - $pesoSalida ;
        $descripcion = "Salida de ". (string) $pesoSalida . " kg de aluminio para la liberación de ". (string) $cantidad_requerida . " piezas de ". $nombrePieza . " del producto ". $nombreProducto. " del pedido No. ". $id_pedido;

    if ($cantidad_max == $cantidad_requerida) {
        
    // Eliminar las observacion si ya se liberó esa pieza
        $db->doQuery("DELETE FROM detalles_observaciones WHERE id_detalle_observacion = ?", [$id_detalle_observacion]);
        $verificarObservaciones = $db->doQuery("SELECT * FROM detalles_observaciones WHERE id_pedido = ? LIMIT 1", [$id_pedido]);
    // Verificar si ya no hay observaciones
        if (!$verificarObservaciones) {
            $db->doQuery("UPDATE pedidos SET tipo_observacion = 'Ninguna' WHERE id_pedido = ?", [$id_pedido]);
        }
        $db->doQuery($query, [$resultadoObtenido, ObtenerFecha(), "Salida", $descripcion]);
        echo json_encode(["status" => "success"]);
        exit();
    } else if ($cantidad_max > $cantidad_requerida) {
        $cantidad_restante = $cantidad_max - $cantidad_requerida;
        $db->doQuery("UPDATE detalles_observaciones SET cantidad = ? WHERE id_detalle_observacion = ?", [$cantidad_restante, $id_detalle_observacion]);
        $db->doQuery($query, [$resultadoObtenido, ObtenerFecha(), "Salida", $descripcion]);
        echo json_encode(["status" => "success"]);
        exit();
    } else {
        echo json_encode(["status" => "error", "message" => "la cantidad requerida no puede ser mayor a la cantidad maxima"]);
        exit();
    }

}
?>