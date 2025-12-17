<?php
include("../../config/connection.php");
include("../../helpers/utils.php");
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST)) {
    $fecha = ObtenerFecha();
    $cantidad = isset($_POST['cantidad']) ? floatval($_POST['cantidad']) : 0.0;

    // Validar cantidad mínima
    if ($cantidad <= 0) {
        header("Location: ../../HTML/materiales.php?error=cantidad_invalida");
        exit();
    }

    // Obtener el stock actual
    $stmt = $conexion->prepare("SELECT
     cantidad_kg AS cantidad 
     FROM stock_aluminio 
    ORDER BY id_stock DESC LIMIT 1");
    $stmt->execute();
    $res = $stmt->get_result();
    $stock_aluminio_actual = $res->fetch_assoc();

    $nueva_cantidad = $cantidad;
    
    // Calcular nueva cantidad
    if (!empty($stock_aluminio_actual) || $stock_aluminio_actual != null) {
        $nueva_cantidad += $stock_aluminio_actual['cantidad'];
    }

    // Insertar la nueva cantidad
    $stmt_actualizar_stock = $conexion->prepare("INSERT INTO stock_aluminio(cantidad_kg, fecha) VALUES (?, ?)");
    $stmt_actualizar_stock->bind_param('ds', $nueva_cantidad, $fecha);
    $stmt_actualizar_stock->execute();
    header("Location: ../../HTML/materiales.php");
    exit();
} else
    header("Location:../../HTML/materiales.php");
exit();
?>