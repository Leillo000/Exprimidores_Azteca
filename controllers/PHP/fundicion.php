<?php

include("../../helpers/singleton_connection.php");
include("../../helpers/utils.php");

if ($_SERVER['REQUEST_METHOD'] === "POST" && !empty($_POST)) {

    $db = Database::getDatabase();

    $id_pedido = isset($_POST["id_pedido"]) ? intval($_POST["id_pedido"]) : 0;
    $pesoRetorno = isset($_POST["peso_retorno"]) ? doubleval($_POST["peso_retorno"]) : 0;
    $verificarPesaje = $db->doQuery("SELECT pesaje_total FROM pedidos WHERE id_pedido = ?", [$id_pedido]);
    $pesoSupuesto = $verificarPesaje[0]["pesaje_total"];

    if ($pesoRetorno <= 0 || $id_pedido <= 0) {
        echo "<script>
        window.location.href = '../../HTML/retorno.php' 
        alert('Datos inválidos')
        </script>";
        exit();
    }

    if ($pesoSupuesto <= $pesoRetorno) {
        echo "<script>
        alert('El pesaje de retorno no puede ser mayor o igual que " . $pesoSupuesto . "kg')
        window.location.href = '../../HTML/retorno.php';
        </script>";
        exit();
    }

    // Se registra el cambio en el stock de aluminio global
    $fecha = ObtenerFecha();
    $pesoStockAluminio = $db->doQuery("SELECT cantidad_kg FROM stock_aluminio ORDER BY id_stock DESC LIMIT 1");
    $pesoStockAluminio[0]["cantidad_kg"] += $pesoSupuesto - $pesoRetorno;
    $descripcion = "Entrada de " . $pesoSupuesto - $pesoRetorno . "kg por retorno de aluminio del pedido No." . $id_pedido;
    $db->doQuery("INSERT INTO stock_aluminio(cantidad_kg, fecha, tipo, descripcion) VALUES (?, ?, ?, ?)", [$pesoStockAluminio[0]["cantidad_kg"], $fecha, "Retorno", $descripcion]);

    // Se introduce el movimiento en stock fundicion
    $descripcion = "Entrada de " . $pesoSupuesto - $pesoRetorno . "kg de aluminio del pedido No." . $id_pedido;
    $db->doQuery("INSERT INTO stock_fundicion(id_pedido, tipo, descripcion, fecha, cantidad) VALUES(?, ?, ?, ?, ?)", [$id_pedido, "Entrada", $descripcion, $fecha, ($pesoSupuesto - $pesoRetorno)]);
    $idFundicion = $db->doQuery("SELECT id_fundicion FROM stock_fundicion ORDER BY id_fundicion DESC LIMIT 1");
    // Se registra el cambio en el stock de aluminio de fundición
    $stockAluminioFundicion = $db->doQuery("SELECT cantidad FROM stock_fundicion_total ORDER BY id_stock_fundicion DESC LIMIT 1");
    
    // Se suma la diferencia de la cantidad del peso supuesto con el peso de retorno en la cantidad global de aluminio en fundición
    if(!isset($stockAluminioFundicion[0]["cantidad"])){
        $db->doQuery("INSERT INTO stock_fundicion_total(id_fundicion, cantidad) VALUES(?, ?)", [$idFundicion[0]["id_fundicion"], ($pesoSupuesto - $pesoRetorno)]);
        echo "<script>
        alert('Se registró correctamente')
        window.location.href = '../../HTML/retorno.php';
        </script>";
        exit();
    }
    $db->doQuery("INSERT INTO stock_fundicion_total(cantidad) VALUES(?)", [($stockAluminioFundicion[0]["cantidad"] + ($pesoSupuesto - $pesoRetorno))]);
    
    echo "<script>
        alert('Se registró correctamente');
        window.location.href = '../../HTML/retorno.php';
        </script>";
}

?>