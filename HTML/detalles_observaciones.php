<?php
include("../config/connection.php");

// Verificar si el campo está vacío o no es un número, en caso de que esté vació su valor será 0
$id_pedido = isset($_GET['id_pedido']) ? intval($_GET['id_pedido']) : 0;
$observaciones = true;
// Verificación para que no existan numeros negativos ni que el ID del pedido sea 0
if ($id_pedido <= 0) {
    echo ("<script>alert('Datos inválidos.');
window.location.href = 'pedidos.php';</script>");
    exit();
}

// Aluminio que debe volver a fundir
$cantidad_total_observaciones = 0;

$PorPagina = 10;
$Pagina = isset($_GET['page']) && is_numeric($_GET['page']) ? intval($_GET['page']) : 1;
$offset = ($Pagina - 1) * $PorPagina; // Verifica numero de página para mostrar los registros, página 1 del 1 a 25, página 2 del 26 al 50 y así sucesivamente

$resultado = $conexion->query("SELECT COUNT(*) as total FROM detalles_observaciones");
$resultado_query = $resultado->fetch_assoc();
$total = intval($resultado_query["total"]);

$totalPaginas = max(1, ceil($total / $PorPagina)); // calcula en cuántas páginas mostrar los registros

$stmt_total = $conexion->prepare("SELECT pd.id_pedido,
pz.peso AS peso_unitario, 
dto.cantidad AS cantidad,
SUM(pz.peso * dto.cantidad) AS total
 FROM pedidos pd
 JOIN detalles_observaciones dto ON pd.id_pedido = dto.id_pedido
 JOIN piezas pz ON pz.id_pieza = dto.id_pieza
 WHERE pd.id_pedido = ?
 GROUP BY pd.id_pedido;
");

$stmt_total->bind_param("i", $id_pedido);
$stmt_total->execute();
$total = $stmt_total->get_result();
$total_aluminio = $total->fetch_assoc();

if (empty($total_aluminio)) {
    $observaciones = false;
}

// obtener 10 registros con paginación (añadimos placeholders para LIMIT y OFFSET)
$stmt = $conexion->prepare("SELECT  pd.id_pedido, 
po.nombre_producto, 
pz.nombre_pieza AS nombre_pieza, 
pz.peso AS peso_unitario, 
dto.cantidad AS cantidad,
dto.id_detalle_observacion
FROM pedidos pd
JOIN detalles_observaciones dto ON pd.id_pedido = dto.id_pedido
JOIN piezas pz ON pz.id_pieza = dto.id_pieza
JOIN productos AS po ON po.id_producto = pz.id_producto
WHERE pd.id_pedido = ? 
ORDER BY dto.id_pieza DESC
LIMIT ? OFFSET ?
");

$stmt->bind_param("iii", $id_pedido, $PorPagina, $offset);
$stmt->execute();
$res = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="http://localhost/Codigo_Exprimidores_Azteca/CSS/pedidos.css">
    <link rel="stylesheet" href="http://localhost/Codigo_Exprimidores_Azteca/assets/CSS/nav.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Alan+Sans:wght@300..900&family=Annapurna+SIL:wght@400;700&family=Arimo:ital,wght@0,400..700;1,400..700&family=Bricolage+Grotesque:opsz,wght@12..96,200..800&family=Epunda+Sans:ital,wght@0,300..900;1,300..900&family=Epunda+Slab:ital,wght@0,300..900;1,300..900&family=Montserrat:ital,wght@0,100..900;1,100..900&family=Open+Sans:ital,wght@0,300..800;1,300..800&family=Playfair+Display:ital,wght@0,400..900;1,400..900&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">
    <link href="../Images/logo_icon.ico" rel="icon" type="image/x-icon">
    <title> Detalles observaciones </title>

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
                 <li>
                    <a href="pedidos.php"> Pedidos </a>
                </li>
                <li>
                    <a href="carrito.php">
                        <!-- Icono de carrito -->
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="#2F6842" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="icon icon-tabler icons-tabler-outline icon-tabler-shopping-cart">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M6 19m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                            <path d="M17 19m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                            <path d="M17 17h-11v-14h-2" />
                            <path d="M6 5l14 1l-1 7h-13" />
                    </a>
                </li>
            </div>
        </ul>
    </nav>
    <br>
    <div class="center">
        <h1> Pedido No. <?php echo $id_pedido; ?> </h1>
    </div>
    <table>
        <tr>
            <th class="columnas"> Nombre del producto al que pertenece </th>
            <th class="columnas"> Nombre pieza </th>
            <th class="columnas"> Cantidad </th>
            <th class="columnas"> Peso unitario en gramos </th>
            <th class="columnas"> Subtotal en Kg </th>
            <th class="columnas"> Acción </th>
        </tr>

        <?php while ($row = $res->fetch_assoc()) {
            // Calcula el subtotal de aluminio a fundir considerando la merma 
            $subtotal = $row["cantidad"] * $row["peso_unitario"] / 1000;
            $subtotal += $subtotal * 0.1;
             ?>
            <tr>
                <td> <?php echo htmlspecialchars($row["nombre_producto"]); ?></td>
                <td> <?php echo htmlspecialchars($row["nombre_pieza"]); ?></td>
                <td> <?php echo htmlspecialchars($row["cantidad"]); ?></td>
                <td> <?php echo htmlspecialchars($row["peso_unitario"]) . ' gr'; ?></td>
                <td> <?php echo htmlspecialchars($subtotal . " Kg"); ?></td>
                <td>
                    <select class="button_table" onchange="redirigir(this.value, <?php echo $row['id_detalle_observacion']; ?>, <?php echo $row['id_pedido']; ?>)">
                        <option class="button_table" value="" disabled selected hidden> Seleccionar acción </option>
                     <!--  <option class="button_table" value="editar"> Editar </option> --> 
                        <option class="button_table" value="completar"> Completar </option>
                    </select>
                </td>
            </tr>
        <?php } ?>
    </table>
        <!-- Si existen observaciones del producto, mostrar las páginas. -->
        <!-- Considerando la merma pone el total de aluminio que se debe volver a fundir -->
    <?php if ($observaciones == true) { ?>
        <p>Mandar a fundición: <?php echo htmlspecialchars(($total_aluminio['total'] + ($total_aluminio['total'] * 0.1)) / 1000); ?> Kg de
                aluminio</p>
        <div>
            <?php if ($Pagina > 1) { ?>
                <a href="?page=1">
                    << Primero</a>
                        <a href="?page=<?php echo $Pagina - 1 ?>"> Anterior</a>
                    <?php } ?>
                    <div class="center">
                        <p> Página <?php echo $Pagina; ?> de <?php echo $totalPaginas; ?></p>
                    </div>
                    <?php if ($Pagina < $totalPaginas) {
                        ?>
                        <a href="?page=<?php echo $Pagina + 1; ?>"> Siguiente</a>
                        <a href="?page=<?php echo $totalPaginas; ?>">Última página >> </a>
                    <?php } ?>
            </a>
        </div>
                    <!-- Si no existen observaciones de ese pedido, nada se tendrá que mandar a fundición -->
    <?php } else { ?>
        <p><b>No hay nada que mandar a fundición.</b></p>
    <?php } ?>

    <button class="button" onclick="location.href='menu.php'"> MENÚ </button>

    <script src="../controllers/JS/detalles_observaciones.js"> </script>
</body>

</html>