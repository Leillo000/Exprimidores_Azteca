<?php
include("../../config/connection.php");

// Valida los datos que se envian desde la URL
$id_pedido = isset($_GET['id_pedido']) ? intval($_GET['id_pedido']) : 0;
$etapa = isset($_GET['etapa']) ? intval($_GET['etapa']) : 0;
$no_etapa = 0;
$array_etapas = ["Fundición", "Lijado", "Ensamblaje", "Pintado", "Completado"];

// Se sale del programa si los datos de los ID´s son incorrectos.
if ($id_pedido == 0 || $etapa == 0) {
    echo ("<script>alert('Datos inválidos.');
    window.location.href = ../../HTML/pedidos.php;
    </script>");
    exit();
}

// Se selecciona la etapa
$stmt = $conexion->prepare("SELECT etapa, tipo_observacion FROM pedidos WHERE id_pedido = ?");
$stmt->bind_param('i', $id_pedido);
$stmt->execute();
$resultado = $stmt->get_result();
$resultado_etapa = $resultado->fetch_assoc();

// Enumerar las etapas, y en cada caso se ponen las etapas.
switch ($resultado_etapa['etapa']) {
    case 'Fundición':
        $no_etapa = 0;
        break;
    case 'Lijado':
        $no_etapa = 1;
        break;
    case 'Ensamblaje':
        $no_etapa = 2;
        break;
    case 'Pintado':
        $no_etapa = 3;
        break;
    case 'Completado':
        $no_etapa = 4;
        break;
}

// Verificar que observaciones = Ninguna

if ($resultado_etapa['tipo_observacion'] != 'Ninguna') {
    echo ("<script>alert('No se puede pasar a la siguiente etapa hasta que completen las piezas de nuevo y no existan observaciones.');
    window.location.href = '../../HTML/pedidos.php';
    </script>");
    exit();
}

// Siguiente etapa.
if ($etapa === 1) {
    // Verifica si ya está en la última etapa
    if ($no_etapa === 4) {
        echo ("<script>alert('Esta es la última etapa.');
    window.location.href = '../../HTML/pedidos.php';
    </script>");
        exit();
    }
    // Se le asigna el valor de la siguiente etapa.
    $nueva_etapa = $array_etapas[$no_etapa + 1];
}
// Anterior etapa.
else {
    // Verificar que cuando le dé a anterior etapa y la etapa sea Fundición, no cause errores
    if ($no_etapa === 0) {
        echo ("<script>alert('Esta es la primera etapa. No se puede pasar a la anterior.');
    window.location.href = '../../HTML/pedidos.php';
    </script>");
        exit();
    }

    // Se le asigna el valor de la anterior etapa.
    $nueva_etapa = $array_etapas[$no_etapa - 1];
}

$stmt_actualizar_pedido = $conexion->prepare("UPDATE pedidos SET etapa = ? WHERE id_pedido = ?");
$stmt_actualizar_pedido->bind_param('si', $nueva_etapa, $id_pedido);
$stmt_actualizar_pedido->execute();

header("Location: ../../HTML/pedidos.php");

?>