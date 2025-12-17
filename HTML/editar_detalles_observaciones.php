<?php
include("../config/connection.php");

// Obtener id_pedido desde GET
$id_pedido = 0;
if (!empty($_GET['id_detalle_observacion'])) {
    $id_detalle_observacion = intval($_GET['id_detalle_observacion']);
}
if ($id_pedido <= 0) {
    die("ID de pedido inválido. <a href='detalles_observaciones.php'>Volver</a>");
}

$perPage = 25; // Guardamos en una variable el número de registros a mostrar
$page = isset($_GET['page']) && is_numeric($_GET['page']) &&  $_GET['page'] > 0 ? intval($_GET['page']) :1; // Página actual, por defecto 1
$offset = ($page - 1) * $perPage; // Verifica No. de la página para mostrar los registros, página 1 del 1 al 25, página 2 del 26 al 50, y asi sucesivamente

// Consulta preparada con LIMIT ? OFFSET ? (placeholders correctos)
$stmt = $conexion->prepare("SELECT dto.id_detalle_observacion, pz.nombre_pieza, dto.cantidad, pz.peso
FROM detalles_observaciones dto
JOIN piezas pz ON pz.id_pieza = dto.id_pieza
WHERE dto.id_pedido = ?
ORDER BY pz.peso DESC
LIMIT ? OFFSET ?");

if ($stmt === false) {
    die("Error en prepare(): " . $conexion->error);
}

$stmt->bind_param("iii", $id_pedido, $perPage, $offset);
$stmt->execute();
$res = $stmt->get_result();

// Contar total de registros para paginación
$countStmt = $conexion->prepare("SELECT COUNT(*) as total FROM detalles_observaciones WHERE id_pedido = ?");
$countStmt->bind_param("i", $id_pedido);
$countStmt->execute();
$countRes = $countStmt->get_result();
$countRow = $countRes->fetch_assoc();
$total = intval($countRow['total']);
$totalPaginas = max(1, ceil($total / $perPage));
$countStmt->close();
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
    <title> Editar detalles de observaciones </title>
</head>
<body>
    <nav>
        <img src="../Images/logo_menu.jpg" alt="logo_exprimidores_azteca">
        <ul>
            <div id="separate_link">
                <li>
                    <a href="productos.php">Productos</a>
                </li>
                <li>
                    <a href="pedidos.php">Pedidos</a>
                </li>
                <li>
                    <a href="materiales.php">Materiales</a>
                </li>
            </div>
        </ul>
    </nav>
    <br>

    <h1>Editar Detalles del Pedido #<?php echo htmlspecialchars($id_pedido, ENT_QUOTES, 'UTF-8'); ?></h1>
    <br><br>

    <!-- Tabla con detalles y edición -->
    <table>
        <thead>
            <tr>
                <th class="columnas">Nombre pieza</th>
                <th class="columnas">Cantidad</th>
                <th class="columnas">Peso unitario</th>
                <th class="columnas">Subtotal</th>
                <th class="columnas">Acción</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $res->fetch_assoc()): 
                $id_detalle = intval($row['id_detalle_observacion']);
                $cantidad = intval($row['cantidad']);
                $peso = floatval($row['peso']);
                $subtotal = $cantidad * $peso;
            ?>
            <tr>
                <td> <?php echo htmlspecialchars($row['nombre_pieza'], ENT_QUOTES, 'UTF-8'); ?></td>
                <td id="cantidad_<?php echo $id_detalle; ?>"> <?php echo $cantidad; ?> </td>
                <td> <?php echo htmlspecialchars($peso, ENT_QUOTES, 'UTF-8'); ?> gr </td>
                <td id="subtotal_<?php echo $id_detalle; ?>"> <?php echo number_format($subtotal); ?> gr </td>
                <td>
                    <button type="button" class="button_table" onclick="abrirEdicion(<?php echo intval($id_detalle); ?>, <?php echo intval($cantidad); ?>, <?php echo floatval($peso); ?>)"> Editar </button>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <!-- Modal de edición -->
    <div id="modal-edicion" style="display:none; position:fixed; top:50%; left:50%; transform:translate(-50%,-50%); background:#fff; padding:30px; border-radius:8px; box-shadow:0 4px 20px rgba(0,0,0,0.2); z-index:1000; min-width:350px;">
        <h2>Editar Cantidad</h2>
        <form id="form-edicion" method="post" action="../controllers/actualizar_detalle.php">
            <input type="hidden" id="id_detalle_input" name="id_detalle" value="">
            <input type="hidden" id="id_pedido_input" name="id_pedido" value="<?php echo $id_pedido; ?>">
            
            <label>Cantidad:</label>
            <input type="number" id="cantidad_input" name="cantidad" min="1" style="width:100%; padding:8px; margin:10px 0; border-radius:4px; border:1px solid #ccc;">
            
            <label>Peso unitario (gr):</label>
            <input type="text" id="peso_display" readonly style="width:100%; padding:8px; margin:10px 0; border-radius:4px; border:1px solid #ccc; background:#f5f5f5;">
            
            <label>Subtotal (gr):</label>
            <input type="text" id="subtotal_input" readonly style="width:100%; padding:8px; margin:10px 0; border-radius:4px; border:1px solid #ccc; background:#f5f5f5;">
            
            <div style="text-align:center; margin-top:20px;">
                <button type="submit" class="button_table">Guardar</button>
                <button type="button" class="button_table" onclick="cerrarEdicion()">Cancelar</button>
            </div>
        </form>
    </div>

    <!-- Overlay para modal -->
    <div id="modal-overlay" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:999;" onclick="cerrarEdicion()"></div>

    <!-- Paginación -->
    <div class="center">
        <?php if ($page > 1): ?>
            <a href="?id=<?php echo $id_pedido; ?>&page=1">&laquo; Primero</a>
            <a href="?id=<?php echo $id_pedido; ?>&page=<?php echo $page - 1 ?>">Anterior</a>
        <?php endif; ?>

        <span class="">Página <?php echo $page; ?> de <?php echo $totalPaginas; ?></span>

        <?php if ($page < $totalPaginas): ?>
            <a href="?id=<?php echo $id_pedido; ?>&page=<?php echo $page + 1 ?>">Siguiente</a>
            <a href="?id=<?php echo $id_pedido; ?>&page=<?php echo $totalPaginas; ?>">Última página &raquo;</a>
        <?php endif; ?>
    </div>

    <button class="button" onclick="location.href='detalles_observaciones.php'"> VOLVER </button>
    <button class="button" onclick="location.href='menu.php'">MENÚ</button>

    <!-- JavaScript para modal y cálculo 
    <script>
document.addEventListener('DOMContentLoaded', function () {
    var pesoActual = 0;

    window.abrirEdicion = function (id_detalle, cantidad) {
        pesoActual = parseFloat(peso) || 0;
        var idInp = document.getElementById('id_detalle_input');
        var cantInp = document.getElementById('cantidad_input');
        var pesoDisp = document.getElementById('peso_display');
        var subInp = document.getElementById('subtotal_input');
        if (!idInp || !cantInp || !pesoDisp || !subInp) {
            console.error('Elementos del modal no encontrados');
            return;
        }
        idInp.value = id_detalle;
        cantInp.value = cantidad;
        pesoDisp.value = pesoActual.toFixed(2);
        actualizarSubtotal();
        document.getElementById('modal-edicion').style.display = 'block';
        document.getElementById('modal-overlay').style.display = 'block';
    };

    window.cerrarEdicion = function () {
        var modal = document.getElementById('modal-edicion');
        var overlay = document.getElementById('modal-overlay');
        if (modal) modal.style.display = 'none';
        if (overlay) overlay.style.display = 'none';
    };

    function actualizarSubtotal() {
        var cantInp = document.getElementById('cantidad_input');
        var subInp = document.getElementById('subtotal_input');
        if (!cantInp || !subInp) return;
        var cantidad = parseFloat(cantInp.value) || 0;
        var subtotal = cantidad * (pesoActual || 0);
        subInp.value = subtotal.toFixed(2);
    }

    var cantidadInput = document.getElementById('cantidad_input');
    if (cantidadInput) {
        cantidadInput.addEventListener('input', actualizarSubtotal);
    }
});
    </script>
        -->
</body>
</html>