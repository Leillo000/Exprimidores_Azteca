<?php
include("../config/connection.php");
include("../helpers/utils.php");

$query = "SELECT ps.id_pieza, po.nombre_producto, ps.nombre_pieza, ps.peso FROM piezas AS ps 
JOIN productos AS po ON po.id_producto = ps.id_producto 
ORDER BY po.nombre_producto";

$perPage = 25; // Guardamos en una variable el número de registros a mostrar
$page = isset($_GET['page']) && is_numeric($_GET['page']) && $_GET['page'] > 0 ? intval($_GET['page']) : 1; // Página actual, por defecto 1
$offset = ($page - 1) * $perPage; // Verifica No. de la página para mostrar los registros, página 1 del 1 al 25, página 2 del 26 al 50, y asi sucesivamente

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST) && !empty($_POST['nombre_pieza'])) {
    $nombre_pieza = $_POST['nombre_pieza'];
    $query = BuscarPieza($nombre_pieza);
}

$stmt = $conexion->prepare($query . " DESC LIMIT ? OFFSET ?");
$stmt->bind_param("ii", $perPage, $offset);
$stmt->execute();
$res = $stmt->get_result();
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
    <title> Pedidos </title>
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
                    <a href="productos.php"> Productos </a>
                </li>
                <li>
                    <a href="pedidos.php"> Pedidos </a>
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
    <div>
        <h1> Piezas </h1>
        <br>
        <form method="post" action="piezas.php">
            <span> <input name="nombre_pieza" type="text" placeholder="Buscar pieza por producto... ">
                <button class="button_search" href="" alt="buscar" type="submit">
                    <img src="../Images/lupa_buscar.png" alt="icono_buscar" class="icon_search">
                </button> </span>
        </form>
        <br>
    </div>
    <div>
        <table>
            <thead>
                <tr>
                    <th class="columnas"> Producto al que pertenece </th>
                    <th class="columnas"> Nombre de la pieza </th>
                    <th class="columnas"> Peso en gramos </th>
                    <th class="columnas"> Acción </th>
                    <th class="columnas"> Acción </th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $res->fetch_assoc()): ?>

                    <tr>
                        <td> <?php echo htmlspecialchars($row['nombre_producto']); ?> </td>
                        <td> <?php echo htmlspecialchars($row['nombre_pieza']); ?> </td>
                        <td> <?php echo htmlspecialchars($row['peso']); ?> gr</td>
                        <td>
                            <!-- MODIFICAR PIEZA -->
                            <form method="post" action="editar_piezas.php">
                                <button onclick="editar_piezas.php" class="button_table" type="submit">Editar</button>
                                <input type="hidden" name="id_pieza" value="<?php echo $row['id_pieza']; ?>">
                            </form>
                        </td>
                        <td>
                            <!-- ELIMINAR PIEZA -->
                            <form method="post" action="../controllers/eliminar_pieza.php">
                                <button class="button_table" type="submit">Eliminar</button>
                                <input type="hidden" name="id_pieza" value="<?php echo $row['id_pieza']; ?>">
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
    <!-- INSERTAR PIEZA -->
    <button class="button" onclick="location.href='insertar_piezas.php'">Agregar una nueva pieza</button>
    <button class="button" onclick="location.href='menu.php'"> MENÚ </button>
</body>

</html>