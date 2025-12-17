<?php
// Desarrollo: mostrar errores

include('../config/connection.php');

// Verificación de que el ID sea válido
$id_detalle_observacion = isset($_GET['id_detalle_observacion']) ? intval($_GET['id_detalle_observacion']) : 0;
$id_pedido = isset($_GET['id_pedido']) ? intval($_GET['id_pedido']) : 0;

if ($id_detalle_observacion <= 0) {
    header("Location : '../HTML/detalles_observaciones.php'");
}

// Se da de baja la observacion
$stmt_baja = $conexion->prepare('DELETE FROM detalles_observaciones WHERE id_detalle_observacion = ?');
$stmt_baja->bind_param('i', $id_detalle_observacion);
$stmt_baja->execute();

// Se verifica que cuando ya no haya detalles, los observaciones cambien a 'Ninguna'
$stmt_verificar = $conexion->prepare('SELECT id_pedido FROM detalles_observaciones WHERE id_pedido = ?');
$stmt_verificar->bind_param('i', $id_pedido);
$stmt_verificar->execute();
$res = $stmt_verificar->get_result();
$verificar_detalles = $res->fetch_assoc();

// Actualiza las columnas si ya no hay detalles del pedido
if (empty($verificar_detalles) || $verificar_detalles === null ) {
    // Se actualizan los registros del pedido si es que ya no tiene observaciones
    $stmt_actualizar = $conexion->prepare("UPDATE pedidos SET tipo_observacion = 'Ninguna' WHERE id_pedido = ?");
    $stmt_actualizar->bind_param('i', $id_pedido);
    $stmt_actualizar->execute();
}

header("Location: ../HTML/detalles_observaciones.php?id_pedido= + $id_pedido");

// error_reporting(E_ALL);
// ini_set('display_errors', 1);

// require_once __DIR__ . '/../config/connection.php';

// // Permitir llamada por enlace GET convirtiéndola a POST
// if ($_SERVER['REQUEST_METHOD'] !== 'POST' && isset($_GET['accion']) && $_GET['accion'] === 'eliminar' && isset($_GET['id'])) {
//     $_POST['id_pedido'] = intval($_GET['id']);
//     $_POST['confirm'] = '1';
//     $_SERVER['REQUEST_METHOD'] = 'POST';
// }

// // Si no es POST, volver a la lista
// if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
//     header('Location: ../HTML/detalles_observaciones.php');
//     exit;
// }

// // Validar id_pedido y confirmación
// if (!isset($_POST['id_pedido']) || !is_numeric($_POST['id_pedido'])) {
//     echo "ID de pedido inválido. <button class='button' onclick='location.href='../HTML/detalles_observaciones.php''> VOLVER A LA LISTA </button>";
//     exit;
// }
// $id_pedido = intval($_POST['id_pedido']);
// $confirm = isset($_POST['confirm']) && $_POST['confirm'] === '1';
// if (!$confirm) {
//     echo "Operación no confirmada. <button class='button' onclick='location.href='../HTML/detalles_observaciones.php''> VOLVER A LA LISTA </button>";
//     exit;
// }

// // Inicializar contadores
// $deleted_detalles = 0;
// $deleted_pedidos = 0;

// $conexion->begin_transaction();
// try {
//     // Eliminar detalles_observaciones asociados al pedido
//     $stmt = $conexion->prepare("DELETE FROM detalles_observaciones WHERE id_pedido = ?");
//     if ($stmt === false) {
//         throw new Exception("Error en prepare() (detalles_observaciones): " . $conexion->error);
//     }
//     $stmt->bind_param("i", $id_pedido);
//     $stmt->execute();
//     $deleted_detalles = $stmt->affected_rows;
//     $stmt->close();

//     // Eliminar el pedido
//     $stmt2 = $conexion->prepare("DELETE FROM detalles_observaciones WHERE id_detalle_observacion = ?");
//     if ($stmt2 === false) {
//         throw new Exception("Error en prepare() (pedidos): " . $conexion->error);
//     }
//     $stmt2->bind_param("i", $id_pedido);
//     $stmt2->execute();
//     $deleted_pedidos = $stmt2->affected_rows;
//     $stmt2->close();

//     $conexion->commit();

// } catch (Exception $e) {
//     $conexion->rollback();
//     http_response_code(500);
//     echo "Error: No se pudo completar la operación. Detalles: " . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8') . "<br>";
//     echo "<button class='button' onclick='location.href='../HTML/detalles_observaciones.php''> VOLVER A LA LISTA </button>";
//     exit;
// }
// Mostrar resumen
?>

<!--
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
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
    <div >
        <h1>Operación completada</h1>
        <ul>
            <li>Detalles eliminados: <?php // echo htmlspecialchars($deleted_detalles, ENT_QUOTES, 'UTF-8'); ?></li>
            <li>Pedidos eliminados: <?php // echo htmlspecialchars($deleted_pedidos, ENT_QUOTES, 'UTF-8'); ?></li>
        </ul>
        <button class="button" onclick="location.href='../HTML/detalles_observaciones.php'"> Volver </button>
        <br>
        <button class="button" onclick="location.href='../HTML/menu.php'"> MENÚ </button>
    </div>
</body>
</html>
-->