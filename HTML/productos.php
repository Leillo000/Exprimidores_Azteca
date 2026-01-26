<?php
include("../config/connection.php");
include("../helpers/utils.php");
include("../assets/HTML/layout.php");
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

<head>
    <title> Productos </title>
</head>

<body>
    <div class="container">
        <h1> Productos </h1>
        <br>
        <!-- Buscar producto -->
        <form method="post" action="productos.php">
            <!-- Se pone el nombre del producto dentro del input -->
            <div class="search_container">
                <input name="nombre_producto" type="text" placeholder=" Buscar producto por nombre ... ">
                <!-- Boton de buscar responsivo-->
                <button class="button_search" type="submit" name="buscar">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="icon icon-tabler icons-tabler-outline icon-tabler-search">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M3 10a7 7 0 1 0 14 0a7 7 0 1 0 -14 0" />
                        <path d="M21 21l-6 -6" />
                    </svg>
                </button>
            </div>
        </form>
        <br>
        <div class="center_items">
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
</body>