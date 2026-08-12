<?php
include("../config/connection.php");
include("../helpers/singleton_connection.php");
include("../helpers/utils.php");
include("../assets/HTML/layout.php");
include("../controllers/PHP/control_paginas.php");

// Obtener datos de las piezas, como su nombre, peso, etc.

$db = Database::getDatabase();

$pagina = isset($_GET['page']) ? intval($_GET['page']) : 1;
$palabraABuscar = isset($_GET['filtro']) ? $_GET['filtro'] : "";

$select = "SELECT ps.id_pieza, ps.activo, po.nombre_producto, ps.nombre_pieza, ps.peso, po.id_producto, po.activo FROM piezas AS ps 
    JOIN productos AS po ON po.id_producto = ps.id_producto ";
$select_count = "SELECT COUNT(*) AS total
FROM piezas AS ps
JOIN productos AS po 
    ON po.id_producto = ps.id_producto";

if (empty($palabraABuscar)) {
    $query = $select. " WHERE po.activo = 1 AND ps.activo = 1";
    $query_count = "SELECT COUNT(*) as total FROM piezas";
} else {
    $query_dct = $db->doSearch($select, $select_count, $palabraABuscar, "", "", ["po.nombre_producto", "ps.nombre_pieza", "ps.peso"]);
    $query = $query_dct['query']. " AND po.activo = 1 AND ps.activo = 1";
    $query_count = $query_dct['query_count']. " AND po.activo = 1 AND ps.activo = 1";
}

$controlPaginas = controlPaginas(
    $conexion,
    $query . " ORDER BY po.nombre_producto DESC LIMIT ? OFFSET ?",
    $query_count,
    "ii",
    $pagina
);

?>

<head>
    <title> Pedidos </title>
</head>

<body>
    <div class="container">
        <h1> Piezas </h1>
        <br>
        <form method="get" action="piezas.php">
            <!-- Este div lo que hace es poner en una sola línea (y centrados) el boton para buscar y el input que es la barra de busqueda -->
            <div class="search_container">
                <input id="campoBusqueda" name="filtro" type="text" placeholder="Buscar pieza por producto... ">
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
                <?php foreach ($controlPaginas["datos"] as $row) { ?>

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
                <?php } ?>
            </tbody>
        </table>

                <!-- Barra para control de paginas -->
        <div class="control_pages_bar">
            <div class="center_text_pagesbar"
                onclick="controlDePaginas(<?php echo $controlPaginas['paginaActual']; ?>, <?php echo $controlPaginas['totalPaginas']; ?>, 'anterior', 'piezas', '<?php echo $palabraABuscar; ?>', '', '')">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="#2F6842" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="icon icon-tabler icons-tabler-outline icon-tabler-chevrons-right" id="left_row">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M7 7l5 5l-5 5" />
                    <path d="M13 7l5 5l-5 5" />
                </svg>
                <span id="control_anterior">
                    Anterior
                </span>
            </div>

            <div class="center_text_pagesbar">
                <span>
                    Página <?php echo $controlPaginas["paginaActual"]; ?> de
                    <?php echo $controlPaginas["totalPaginas"]; ?>
                </span>
            </div>

            <div class="center_text_pagesbar">
                <span id="control_siguiente">
                    Siguiente
                </span>
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="#2F6842" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="icon icon-tabler icons-tabler-outline icon-tabler-chevrons-right" id="right_row"
                    onclick="controlDePaginas(<?php echo $controlPaginas['paginaActual']; ?>, <?php echo $controlPaginas['totalPaginas']; ?>, 'siguiente', 'piezas', '<?php echo $palabraABuscar; ?>', '', '')">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M7 7l5 5l-5 5" />
                    <path d="M13 7l5 5l-5 5" />
            </div>
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
                        </svg>
                    </button>
                </div>

                <!-- Cuadro de diálogo para poder editar las piezas-->
                <div class="DialogCenterItems">
                    <div class="dialog_body">
                        <form method="post" id="formEditar" action="">
                            <div class="center_items">
                                <h2>Editar la pieza</h2>
                                <label> Nombre de la pieza </label>
                                <input type="text" id="nombre_pieza" name="nombre_pieza" required>
                                <label> Peso en gramos </label>
                                <input type="text" id="peso" name="peso" required>
                                <label> Producto al que pertenece </label>
                                <input type="text" id="nombre_producto" readonly>
                                <input type="hidden" id="id_pieza" name="id_pieza">
                                <input type="hidden" id="id_producto" name="id_producto">
                                <button class="button" type="submit" id="">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round"
                                        class="icon icon-tabler icons-tabler-outline icon-tabler-check">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M5 12l5 5l10 -10" />
                                    </svg>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </dialog>
</body>
<script src="../assets/JS/piezas.js"></script>
<script src="../assets/JS/control_paginas.js"> </script>
<script src="../assets/JS/control_dialogos.js"> </script>
<script>
    pintarNegritas(<?php echo $controlPaginas["totalPaginas"]; ?>, <?php echo $controlPaginas["paginaActual"]; ?>);
</script>