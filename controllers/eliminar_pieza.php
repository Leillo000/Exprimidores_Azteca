<?php
include("../config/connection.php");

// Validar que se reciba POST con id_pieza
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['id_pieza'])) {
    header("Location: ../HTML/piezas.php?error=sin_id");
    exit();
}

$id_pieza = intval($_POST['id_pieza']);

if ($id_pieza <= 0) {
    header("Location: ../HTML/piezas.php?error=id_invalido");
    exit();
}

// Obtener datos de la pieza antes de eliminarla
$query_obtener = $conexion->prepare(
    'SELECT pz.nombre_pieza, pz.peso, pd.nombre_producto
    FROM piezas pz
    JOIN productos pd ON pz.id_producto = pd.id_producto
    WHERE pz.id_pieza = ?
    LIMIT 1'
);

if ($query_obtener === false) {
    die("Error en prepare(): " . $conexion->error);
}

$query_obtener->bind_param("i", $id_pieza);
$query_obtener->execute();
$resultado = $query_obtener->get_result();
$pieza = $resultado->fetch_assoc();

if (!$pieza) {
    header("Location: ../HTML/piezas.php?error=pieza_no_existe");
    exit();
}

// Guardar datos antes de eliminar
$nombre_pieza = $pieza['nombre_pieza'];
$peso = $pieza['peso'];
$nombre_producto = $pieza['nombre_producto'];

$conexion->begin_transaction();

try {
    // Preparar y ejecutar DELETE
    $stmt = $conexion->prepare("DELETE FROM piezas WHERE id_pieza = ?");
    
    if ($stmt === false) {
        throw new Exception("Error en prepare(): " . $conexion->error);
    }

    $stmt->bind_param("i", $id_pieza);
    
    if (!$stmt->execute()) {
        throw new Exception("Error en execute(): " . $stmt->error);
    }

    // Verificar si se eliminó algo
    if ($stmt->affected_rows === 0) {
        throw new Exception("No se encontró la pieza con ID: $id_pieza");
    }

    $conexion->commit();
    $eliminada = true;
    echo "<script>alert('Pieza eliminada correctamente')</script>";
} catch (Exception $e) {
    $conexion->rollback();
    echo "Error al eliminar la pieza: " . $e->getMessage();
    echo "<br><button class='button' onclick=\"location.href='../HTML/piezas.php'\"> VOLVER A LA LISTA </button>";
    exit();
}
?>
