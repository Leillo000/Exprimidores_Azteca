<?php
include("../config/connection.php");
include("../helpers/utils.php");
include("../assets/HTML/layout.php");

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

<head>
    <title> Pedidos </title>
</head>

<body>
    <div class="container">
        <h1> Piezas </h1>
        <br>
        <form method="post" action="piezas.php">
            <!-- Este div lo que hace es poner en una sola línea (y centrados) el boton para buscar y el input que es la barra de busqueda -->
            <div class="search_container">
                <input name="nombre_pieza" type="text" placeholder="Buscar pieza por producto... ">
                <button class="button_search" type="submit">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="30" viewBox="0 0 24 24" fill="none"
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
</body>