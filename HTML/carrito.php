<?php
include("../config/connection.php");
$precio_total = 0;
$peso_total = 0;

// Crea una consulta de los artículos en el carrito
$query_carrito = $conexion->query('SELECT c.id_carrito, p.nombre_producto AS producto,p.precio_unitario AS precio, p.peso AS peso, c.cantidad AS cantidad 
FROM carrito AS c JOIN productos AS p 
ON p.id_producto = c.id_producto');

$query_clientes = $conexion->query("SELECT id_cliente, nombre FROM empresas WHERE activo = 1");

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
    <title> Carrito </title>
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
                    <a href="pedidos.php"> Pedidos </a>
                </li>
                <li>
                    <a href="productos.php"> Productos </a>
                </li>
                <li>
                    <a href="materiales.php"> Materiales </a>
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
    <h1> Carrito de pedido </h1>
    <table>
        <thead>
            <tr>
                <th class="columnas"> Producto </th>
                <th class="columnas"> Cantidad </th>
                <th class="columnas"> Peso subtotal (Kg) </th>
                <th class="columnas"> Precio subtotal </th>
                <th class="columnas"> Acción </th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $query_carrito->fetch_assoc()):
                // Se calcula el precio total del carrito junto con el peso total, es decir, cuánto aluminio deberá de requirir
                $peso_subtotal = ($row['peso'] * $row['cantidad']) / 1000;
                $precio_subtotal = $row['precio'] * $row['cantidad'];
                $peso_total += $peso_subtotal;
                $precio_total += $precio_subtotal;
                ?>
                <!-- Se agregan las filas -->
                <tr>
                    <form method="post" action="../controllers/procesar_carrito.php">
                        <td> <?php echo htmlspecialchars($row['producto']); ?> </td>
                        <td> <?php echo htmlspecialchars($row['cantidad']); ?> </td>
                        <td> <?php echo ($peso_subtotal + ($peso_subtotal * 0.1)); ?> </td>
                        <td> <?php echo '$' . htmlspecialchars($precio_subtotal); ?> </td>
                        <td> <button type="submit" class="button_table" name="accion" value="eliminar"> Eliminar del
                                carrito</button></td>
                        <input name="id_carrito" value="<?php echo $row['id_carrito']; ?>" type="hidden">
                    </form>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <p><b>Precio total:</b> $ <?php echo htmlspecialchars($precio_total) ?></p>
    <p><b>Peso total:</b> <?php echo htmlspecialchars($peso_total + ($peso_total * 0.1)) ?> Kg</p>
    <!-- Finalizar pedido -->


    <!-- Botón para abrir el cuadro de diálogo -->
    <button class="button" id="btnOpenDialog">
        Finalizar pedido
        </svg>
    </button>

    <!-- Cuadro de Dialogo para seleccionar el cliente -->
    <dialog id="Dialog" class="dialog">
        <div class="dialog_header">
            <button class="btnDialog" id="btnCloseDialog"> X </button>
        </div>
        <div class="dialog_body">
            <label> Selecciona el cliente </label>

            <!-- Formulario para enviar los datos al servidor para procesarlos -->
            <form method="post" action="../controllers/procesar_carrito.php">
                <select name="id_cliente">
                    <?php while ($row_empresas = $query_clientes->fetch_assoc()) { ?>
                        <option value="<?php echo $row_empresas['id_cliente']; ?>">
                            <?php echo $row_empresas['nombre']; ?>
                        </option>
                    <?php } ?>
                </select>
                <button class="button" type="submit" name="accion" value="finalizar">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="icon icon-tabler icons-tabler-outline icon-tabler-check">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M5 12l5 5l10 -10" />
                    </svg> </button>
            </form>
        </div>
    </dialog>

    <!-- Agregar más productos -->
    <button class="button" onclick="location.href='tomar_pedido.php'"> Agregar más productos </button>
    <!-- Redirigir al menú -->
    <button class="button" onclick="location.href='menu.php'"> MENÚ </button>
</body>

<script src="../assets/JS/carrito.js"></script>

</html>