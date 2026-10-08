<?php
include("../../helpers/singleton_connection.php");
include("../../helpers/utils.php");
$db = Database::getDatabase();

if ($_SERVER['REQUEST_METHOD'] === "GET" && !empty($_GET)) {
    $id_detalle_observacion = isset($_GET['id_detalle_observacion']) ? intval($_GET['id_detalle_observacion']) : 0;

    if ($id_detalle_observacion <= 0) {
        echo "<script>
        alert('Datos inválidos');
        window.location.href = '../../HTML/detalles_observaciones.php';
        </script>";
        exit();
    }
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
    $peso = isset($_POST["peso"]) ? intval($_POST["peso"]) : 0;
    $nombreProducto = isset($_POST["nombre_producto"]) ? trim($_POST["nombre_producto"]) : "";
    $nombrePieza = isset($_POST["nombre_pieza"]) ? trim($_POST["nombre_pieza"]) : "";
    $usarAluminioFundicion = isset($_POST['usarAluminio'])
        ? ($_POST['usarAluminio'] === 'on' ? 1 : intval($_POST['usarAluminio']))
        : 0;



    // ========= VALIDACIÓN DE PARÁMETROS  =========  

    if ($id_detalle_observacion <= 0) {
        echo json_encode(["status" => "error", "message" => "Parámetros incorrectos"]);
        exit();
    }

    // Validar si la cantidad máxima es menor que la requerida, en ese caso, no hacer lo demas
    $valCantidadMaxima = $db -> doQuery("SELECT cantidad FROM detalles_observaciones WHERE id_detalle_observacion = ?", [$id_detalle_observacion]);
    $cantidad_max = $valCantidadMaxima[0]["cantidad"];
    if ($cantidad_max < $cantidad_requerida){
        echo json_encode(["status" => "error", "message" => "La cantidad requerida no puede ser mayor a la cantidad maxima"]);
        exit();
    }

    // Comprobar si existen registros en fundición
    $aluminioFundicion = $db->doQuery("SELECT cantidad FROM stock_fundicion_total ORDER BY id_stock_fundicion DESC LIMIT 1");
    if ($usarAluminioFundicion != 1 && $usarAluminioFundicion != 0) {
        echo "<script>
            alert('Error de parámetros');
            window.location.href = '../HTML/tomar_pedido.php';
          </script>";
        exit();
    } else if ($usarAluminioFundicion == 1 && !isset($aluminioFundicion[0]["cantidad"])) {
        echo "<script>
                    alert('No existen registros de aluminio en fundición.')
                    window.location.href = '../../HTML/retorno.php';
                    </script>";
        exit();
    }

    // Verificar si hay suficiente aluminio
    $stock_aluminio = $db->doQuery("SELECT cantidad_kg FROM stock_aluminio ORDER BY fecha DESC LIMIT 1");
    if ($stock_aluminio[0]["cantidad_kg"] <= (($cantidad_requerida * $peso) / 1000) * 1.10) {
        echo json_encode(["status" => "alert", "message" => "no hay suficiente alumuinio"]);
        exit();
    }


    // ====== ELABORACIÓN DE LA CONSULTA =======

    // Registro del movimiento
    $query = "INSERT INTO stock_aluminio (cantidad_kg, fecha, tipo, descripcion) VALUES (?, ?, ?, ?)";
    $pesoSalida = (($cantidad_requerida * $peso) / 1000) * 1.10;
    $resultadoObtenido = $stock_aluminio[0]["cantidad_kg"] - $pesoSalida;
    $descripcion = "Salida de " . (string) $pesoSalida . " kg de aluminio para la liberación de " . (string) $cantidad_requerida . " piezas de " . $nombrePieza . " del producto " . $nombreProducto . " del pedido No. " . $id_pedido;
    $db->doQuery($query, [$resultadoObtenido, ObtenerFecha(), "Salida", $descripcion]);

    /* 
        El aluminio de salida es el aluminio que se le va a restar al de fundición. Si el aluminio que está
        en fundición es menor o igual al pedido, entonces es igual al aluminio que está en fundición para así
        obtener 0kg y no un número negativo.
    */

        $aluminioSalida = 0;

        if(!isset($aluminioFundicion[0]["cantidad"])){
            echo "<script>alert('No está definida cantidad')</script>";
            exit();
        }
        if(!isset($aluminioSalida)){
            echo "<script>alert('No está definida salida')</script>";
            exit();
        }
        // Registrar el movimiento en el stock del aluminio en fundicion
        if ($usarAluminioFundicion == 1) {

            // El aluminio de salida es igual al requerido si supera o es igual que el total que está en el carrito
            $aluminioSalida = $aluminioFundicion[0]["cantidad"] <= $pesoSalida ? $aluminioFundicion[0]["cantidad"] : $pesoSalida;
            if(!isset($aluminioSalida)){
            echo "<script>alert('No está definida salida')</script>";
            exit();
        }
            // Diferencia entre el aluminio pedido y el stock en fundicion
            $aluminioFundicion[0]["cantidad"] -= $pesoSalida;
            $aluminioFundicion[0]["cantidad"] = $aluminioFundicion[0]["cantidad"] < 0 ? 0 : $aluminioFundicion[0]["cantidad"];

            /*
            El registro solo se verá reflejado dentro de una tabla de registros en fundición. El registro global se ve dentro
            de los movimientos del aluminio global.
            */
            $descripcion = "Salida de " . $aluminioSalida . "kg para liberación de piezas del pedido No." . (string) $id_pedido;
            $db->doQuery("INSERT INTO stock_fundicion(id_pedido, tipo, descripcion, fecha, cantidad) VALUES(?, ?, ?, ?, ?)", [$id_pedido, "Salida", $descripcion, ObtenerFecha(), $aluminioSalida]);
            $idFundicion = $db->doQuery("SELECT id_fundicion FROM stock_fundicion ORDER BY id_fundicion DESC LIMIT 1");
            $db->doQuery("INSERT INTO stock_fundicion_total(id_fundicion, cantidad) VALUES(?, ?)", [$idFundicion[0]["id_fundicion"], $aluminioFundicion[0]["cantidad"]]);
        }
    
    // Si se cumple, se eliminan las observaciones
    if ($cantidad_max == $cantidad_requerida) {
        $db->doQuery("DELETE FROM detalles_observaciones WHERE id_detalle_observacion = ?", [$id_detalle_observacion]);
        $verificarObservaciones = $db->doQuery("SELECT id_pedido FROM detalles_observaciones WHERE id_pedido = ? LIMIT 1", [$id_pedido]);
        if (!$verificarObservaciones) {
            $db->doQuery("UPDATE pedidos SET tipo_observacion = 'Ninguna' WHERE id_pedido = ?", [$id_pedido]);
        }
        echo json_encode(["status" => "success", "message" => "Piezas liberadas correctamente"]);
        exit();
    } else if ($cantidad_max > $cantidad_requerida) {
        $cantidad_restante = $cantidad_max - $cantidad_requerida;
        $db->doQuery("UPDATE detalles_observaciones SET cantidad = ? WHERE id_detalle_observacion = ?", [$cantidad_restante, $id_detalle_observacion]);
        echo json_encode(["status" => "success", "message" => "Piezas liberadas correctamente"]);
        exit();
    }

}
?>