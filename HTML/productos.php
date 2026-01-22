<?php
include("../config/connection.php");
include("../helpers/utils.php");

// ===== SE ASIGNA AUTOMÁTICAMENTE ESTE VALOR A QUERY POR DEFECTO SI ES QUE EL IF DE REQUEST_METHOD NO SE CUMPLE =====

$query = "SELECT id_producto, nombre_producto, precio_unitario, peso FROM productos ORDER BY nombre_producto";

// Son los registros por página que se van a mostrar y determina el número de página.

$perPage = 25;
$page = isset($_GET['page']) && is_numeric($_GET['page']) && $_GET['page'] > 0 ? intval($_GET['page']) : 1; // Página actual, por defecto 1

// Verifica No. de la página para mostrar los registros, página 1 del 1 al 25, página 2 del 26 al 50, y asi sucesivamente
$offset = ($page - 1) * $perPage;

// Si se cumple esta condición, va a llamar la función.

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST) && !empty($_POST['nombre_producto'])) {

    $nombre_producto = $_POST['nombre_producto'];
    $query = BuscarProducto(nombre_producto: $nombre_producto);
}

$stmt = $conexion->prepare($query . " ASC LIMIT ? OFFSET ?");
$stmt->bind_param("ii", $perPage, $offset);
$stmt->execute();
$res = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="http://localhost/Codigo_Exprimidores_Azteca/CSS/productos.css">
    <link rel="stylesheet" href="http://localhost/Codigo_Exprimidores_Azteca/assets/CSS/nav.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Alan+Sans:wght@300..900&family=Annapurna+SIL:wght@400;700&family=Arimo:ital,wght@0,400..700;1,400..700&family=Bricolage+Grotesque:opsz,wght@12..96,200..800&family=Epunda+Sans:ital,wght@0,300..900;1,300..900&family=Epunda+Slab:ital,wght@0,300..900;1,300..900&family=Montserrat:ital,wght@0,100..900;1,100..900&family=Open+Sans:ital,wght@0,300..800;1,300..800&family=Playfair+Display:ital,wght@0,400..900;1,400..900&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">
    <link href="../Images/logo_icon.ico" rel="icon" type="image/x-icon">
    <title> Productos </title>
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
                    <a href="materiales.php"> Materiales </a>
                </li>
                <li>
                    <a href="clientes.php"> Clientes </a>
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
    <div>
        <h1> Productos </h1>
        <br>
        <!-- Buscar producto -->
        <form method="post" action="productos.php">
            <!-- Se pone el nombre del producto dentro del input -->
            <span> <input name="nombre_producto" type="text" placeholder=" Buscar producto por nombre ... ">
                <!-- Boton de buscar responsivo-->
                <button class="button_search" type="submit" name="buscar">
                    <img src="../Images/lupa_buscar.png" alt="icono_buscar" class="icon_search">
                </button> </span>
        </form>
        <br> <br>
    </div>
    <div class="center">
        <table>
            <thead>
                <tr>
                    <th class="columnas"> No. </th>
                    <th class="columnas"> Nombre del producto </th>
                    <th class="columnas"> Precio Unitario </th>
                    <th class="columnas"> Peso en gramos </th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $res->fetch_assoc()): ?>
                    <tr>
                        <td> <?php echo htmlspecialchars($row['id_producto']); ?> </td>
                        <td> <?php echo htmlspecialchars($row['nombre_producto']); ?> </td>
                        <td> $<?php echo htmlspecialchars(number_format($row['precio_unitario'], 2)); ?> </td>
                        <td> <?php echo htmlspecialchars(number_format($row['peso'], 2)); ?> gr</td>
                    </tr>
                <?php endwhile; ?>

            </tbody>
        </table>
    </div>
    <br> <br>
    <button class="button" onclick="location.href='menu.php'"> MENÚ</button>
    </div>
</body>

</html>