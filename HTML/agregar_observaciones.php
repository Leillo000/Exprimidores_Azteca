<?php
include("../config/connection.php");

// Se obtiene el ID de la URL
// Condicion ternaria (condicion) ? Valor en el que sea true : Valor en el que sea false.
// Se crean estas condicionales para que no se destruya la URL.
$id_pedido = isset($_GET['id_pedido']) ? intval($_GET['id_pedido']) : 0;
$id_producto = isset($_GET['id_producto']) ? intval($_GET['id_producto']) : 0;
$nombre_producto = 'Seleccionar producto';
if ($id_pedido <= 0) {
    header('Location: pedidos.php?error=id_invalido');
    exit();
}

// Esto se ejecuta solo si se cumplen con los requisitos de que no sea 0, nunca se va a ejecutar hasta que se seleccione un producto
if ($id_producto > 0) {
    $stmtPieza = $conexion->prepare("SELECT ps.nombre_pieza, ps.id_pieza, po.nombre_producto FROM productos AS po 
    JOIN piezas AS ps ON ps.id_producto = po.id_producto
     WHERE po.id_producto = ? ");
    $stmtPieza->bind_param('i', $id_producto);
    $stmtPieza->execute();
    $resultadoPiezas = $stmtPieza->get_result();

    $stmtNombreProducto = $conexion->prepare('SELECT nombre_producto FROM productos WHERE id_producto = ?');
    $stmtNombreProducto->bind_param('i', $id_producto);
    $stmtNombreProducto->execute();
    $resultadoNombreProducto = $stmtNombreProducto->get_result();
    $producto = $resultadoNombreProducto->fetch_assoc();
    $nombre_producto = $producto['nombre_producto'];
}

// ===== VERIFICA QUE EL ID INCRUSTADO EN LA URL SEA VALIDO =====

$stmtProducto = $conexion->prepare("SELECT DISTINCT po.id_producto , po.nombre_producto, p.id_pedido
FROM pedidos AS p 
JOIN detalles_pedidos AS dp ON dp.id_pedido = p.id_pedido 
JOIN productos AS po ON po.id_producto = dp.id_producto
WHERE p.id_pedido = ?");
$stmtProducto->bind_param("i", $id_pedido);
$stmtProducto->execute();
$resultadoProducto = $stmtProducto->get_result();

// ===== VERIFICA QUE EL PEDIDO EXISTA SI PONE UN ID VALIDO PERO NO EXISTE EL PEDIDO =====

if ($resultadoProducto->num_rows === 0) {
    header("Location : detalles_observaciones.php?error=producto_no_encontrado");
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="http://localhost/Codigo_Exprimidores_Azteca/CSS/registrar_datos.css">
    <link rel="stylesheet" href="http://localhost/Codigo_Exprimidores_Azteca/assets/CSS/nav.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Alan+Sans:wght@300..900&family=Annapurna+SIL:wght@400;700&family=Arimo:ital,wght@0,400..700;1,400..700&family=Bricolage+Grotesque:opsz,wght@12..96,200..800&family=Epunda+Sans:ital,wght@0,300..900;1,300..900&family=Epunda+Slab:ital,wght@0,300..900;1,300..900&family=Montserrat:ital,wght@0,100..900;1,100..900&family=Open+Sans:ital,wght@0,300..800;1,300..800&family=Playfair+Display:ital,wght@0,400..900;1,400..900&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">
    <link href="../Images/logo_icon.ico" rel="icon" type="image/x-icon">
    <title> Agregar observaciones del pedido</title>
</head>

<body>
    <nav>
        <img src="../Images/logo_menu.jpg" alt="logo_exprimidores_azteca">
        <ul>
            <div id="separate_link">
                <li>
                    <a href="piezas.php"> Piezas </a>
                </li>
                <li>
                    <a href="materiales.php"> Materiales </a>
                </li>
                <li>
                    <a href="productos.php"> Productos </a>
                </li>
            </div>
        </ul>
    </nav>
    <br>
    <div class="center">
        <h1> Agregar observaciones </h1>
        <label> Producto </label>
        <br>
        <!-- Este es el form que manda los datos para poder agregar observaciones -->
        <form method="post" action="../controllers/procesar_observaciones.php">

            <!-- Si se selecciona un producto, se muestran las piezas correspondientes a ese producto -->
            <select name="id_producto" onchange="ElegirPieza(this.value, <?php echo intval($id_pedido); ?>)">

<!-- Seleccionar el producto -->
                <option value="<?php echo $id_producto; ?>">
                    <?php echo $nombre_producto; ?> </option>

                <?php while ($row = $resultadoProducto->fetch_assoc()): ?>
                    <option value="<?php echo $row['id_producto']; ?>"><?php echo $row['nombre_producto']; ?></option>
                    <?php $productos[] = $row['nombre_producto']; ?>
                <?php endwhile; ?>

            </select>
            <br>
            <label> Pieza </label>
            <br>

                <!-- Seleccionar la pieza del producto -->
            <select name="id_pieza">
                <option value="" disabled selected hidden> Seleccionar pieza del producto</option>
                <?php
                // Se ejecuta solo si el id_producto es mayor a 0 o no es null
                if ($id_producto > 0) {
                    while ($row = $resultadoPiezas->fetch_assoc()) { ?>
                        <option value="<?php echo $row['id_pieza']; ?>"> <?php echo $row["nombre_pieza"]; ?> </option>
                    <?php }
                } ?>
            </select>
            <br>
            <!-- Seleccionar la cantidad de piezas faltantes de ese pedido -->
            <label> Cantidad </label>
            <br>
            <input type="number" name="cantidad" min="1" max="1000" required>
            <input type="hidden" name="id_pedido" value="<?php echo intval($id_pedido); ?>">
            <input type="hidden" name="accion" value="agregar">
        </div>
    <button type="submit" class="button" name="accion" value="agregar"> Agregar </button>
    </form>

    <button class="button" onclick="location.href='menu.php'"> MENÚ </button>
    <script>
        // Se seleccionan las piezas relacionadas con ese producto, para que haya más coherencia con lo que selecciona
        function ElegirPieza(id_producto, id_pedido) {
            producto = parseInt(id_producto);
            pedido = parseInt(id_pedido);
            window.location.href = 'agregar_observaciones.php?id_pedido=' + encodeURIComponent(pedido)
                + '&id_producto=' + encodeURIComponent(producto);
        }
    </script>

</body>

</html>