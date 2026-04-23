<?php
include("../config/connection.php");
include("../helpers/utils.php");
include("../assets/HTML/layout.php");

// Obtener datos de las piezas, como su nombre, peso, etc.

$query = "SELECT ps.id_pieza, po.nombre_producto, ps.nombre_pieza, ps.peso, po.id_producto FROM piezas AS ps 
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


// Obtener datos de los productos existentes para que se puedan mostrar en el cuadro de diálogo 
$query_producto = $conexion->query('SELECT nombre_producto, id_producto FROM productos');

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
                            <button onclick="GetPieza(<?php echo $row['id_pieza']; ?>)" class="button_table">Editar</button>
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
    <!-- Cuadro de Dialogo para seleccionar el cliente -->
    <dialog id="Dialog" class="dialog">
        <div class="dialog_header">
            <button class="btnDialog" id="btnCloseDialog"> <svg xmlns="http://www.w3.org/2000/svg" width="24"
                    height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                    stroke-linecap="round" stroke-linejoin="round"
                    class="icon icon-tabler icons-tabler-outline icon-tabler-x">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M18 6l-12 12" />
                    <path d="M6 6l12 12" />
                </svg> </button>
        </div>

        <!-- Cuadro de diálogo para poder editar las piezas-->
        <div class="DialogCenterItems">
            <div class="dialog_body">
                <!-- Formulario para enviar los datos al servidor para procesarlos -->
                <form method="post" id="FormEditar" action="">
                    <div class="center_items">
                        <!-- Editar nombre de la pieza -->
                        <label> Nombre de la pieza </label>
                        <input type="text" id="nombre_pieza" name="nombre_pieza" required>
                        <!-- Editar peso de la pieza -->
                        <label> Peso en gramos </label>
                        <input type="text" id="peso" name="peso" required>
                        <!-- No se puede editar el producto al que pertenece (Después agregamos esa función donde esté bien integrado el UI) -->
                        <label> Producto al que pertenece </label>
                        <input type="text" id="nombre_producto" name="peso" readonly>
                        <input type="hidden" id="id_pieza" name="id_pieza">
                        <!-- Botón para finalizar -->
                        <button class="button" type="submit" id="">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-check">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M5 12l5 5l10 -10" />
                            </svg>
                        </button>
                    </div>
                </form>
                <div class="center_items">
                    <button class="button" onclick="">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="currentColor" class="icon icon-tabler icons-tabler-filled icon-tabler-trash">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path
                                d="M20 6a1 1 0 0 1 .117 1.993l-.117 .007h-.081l-.919 11a3 3 0 0 1 -2.824 2.995l-.176 .005h-8c-1.598 0 -2.904 -1.249 -2.992 -2.75l-.005 -.167l-.923 -11.083h-.08a1 1 0 0 1 -.117 -1.993l.117 -.007zm-10 4a1 1 0 0 0 -1 1v6a1 1 0 0 0 2 0v-6a1 1 0 0 0 -1 -1m4 0a1 1 0 0 0 -1 1v6a1 1 0 0 0 2 0v-6a1 1 0 0 0 -1 -1" />
                            <path
                                d="M14 2a2 2 0 0 1 2 2a1 1 0 0 1 -1.993 .117l-.007 -.117h-4l-.007 .117a1 1 0 0 1 -1.993 -.117a2 2 0 0 1 1.85 -1.995l.15 -.005z" />
                        </svg>
                    </button>
                </div>
                <br>
            </div>
        </div>
    </dialog>
</body>
<script src="../assets/JS/piezas.js"></script>