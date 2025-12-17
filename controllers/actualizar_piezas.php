<?php
include("../config/connection.php");

if($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST)){

    // Se inicializan las variables desde $_POST
    $id_pieza = isset($_POST['id_pieza']) ? intval($_POST['id_pieza']) : 0;
    $nombre_pieza = isset($_POST['nombre_pieza']) ? trim($_POST['nombre_pieza']) : '';
    $peso = isset($_POST['peso']) ? intval($_POST['peso']) : 0;

    // Validar que no estén vacíos
    if($id_pieza <= 0 || empty($nombre_pieza) || $peso <= 0){
        header("Location: ../HTML/piezas.php?error=datos_invalidos");
        exit();
    }

    // Actualizar la pieza en la base de datos
    $stmt = $conexion->prepare(
        'UPDATE piezas SET nombre_pieza = ?, peso = ? WHERE id_pieza = ?'
    );
    
    // Verificar si la preparación fue exitosa
    if($stmt === false){
        die("Prepare failed: " . $conexion->error);
    }

    // Vincular los parámetros y ejecutar la consulta
    $stmt->bind_param("sii", $nombre_pieza, $peso, $id_pieza);
    
    if($stmt->execute()){
        header("Location: ../HTML/piezas.php?success=actualizado");
        exit();
    } else {
        header("Location: ../HTML/piezas.php?error=actualizar_fallo");
        exit();
    }
}
else {
    header("Location: ../HTML/piezas.php");
    exit();
}
?>