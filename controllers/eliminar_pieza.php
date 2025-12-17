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

} catch (Exception $e) {
    $conexion->rollback();
    echo "Error al eliminar la pieza: " . $e->getMessage();
    echo "<br><button class='button' onclick=\"location.href='../HTML/piezas.php'\"> VOLVER A LA LISTA </button>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="http://localhost/Codigo_Exprimidores_Azteca/CSS/tabla_accion.css">
    <link rel="stylesheet" href="http://localhost/Codigo_Exprimidores_Azteca/assets/CSS/nav.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Alan+Sans:wght@300..900&family=Annapurna+SIL:wght@400;700&family=Arimo:ital,wght@0,400..700;1,400..700&family=Bricolage+Grotesque:opsz,wght@12..96,200..800&family=Epunda+Sans:ital,wght@0,300..900;1,300..900&family=Epunda+Slab:ital,wght@0,300..900;1,300..900&family=Montserrat:ital,wght@0,100..900;1,100..900&family=Open+Sans:ital,wght@0,300..800;1,300..800&family=Playfair+Display:ital,wght@0,400..900;1,400..900&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">
    <link href="../Images/logo_icon.ico" rel="icon" type="image/x-icon">
    <title>Resultado de la baja</title>
</head>
<body>
    <div>
        <h1>Operación completada</h1>
        <ul>
            <li><strong>Pieza:</strong> <?php echo htmlspecialchars($nombre_pieza, ENT_QUOTES, 'UTF-8'); ?></li>
            <li><strong>Peso:</strong> <?php echo htmlspecialchars($peso, ENT_QUOTES, 'UTF-8'); ?> gr</li>
            <li><strong>Producto:</strong> <?php echo htmlspecialchars($nombre_producto, ENT_QUOTES, 'UTF-8'); ?></li>
        </ul>
        <br>
        <button class="button" onclick="location.href='../HTML/piezas.php'"> VOLVER A LA LISTA </button>
        <br>
        <button class="button" onclick="location.href='../HTML/menu.php'"> MENÚ </button>
    </div>
</body>
</html>