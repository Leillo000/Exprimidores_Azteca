<?php
include("../controllers/PHP/log_in.php");
verificarLogIn();
include("../config/connection.php");
include("../helpers/utils.php");
include("../assets/HTML/layout.php");
include("../controllers/PHP/control_paginas.php");

// Obtener datos de las piezas, como su nombre, peso, etc.

$pagina = isset($_GET['page']) ? intval($_GET['page']) : 1;
$controlPaginas = controlPaginas(
    $conexion,
    "SELECT ps.id_pieza, po.nombre_producto, ps.nombre_pieza, ps.peso, po.id_producto FROM piezas AS ps 
    JOIN productos AS po ON po.id_producto = ps.id_producto 
    ORDER BY po.nombre_producto DESC LIMIT ? OFFSET ?",
    "SELECT COUNT(*) as total FROM piezas",
    "ii",
    $pagina
);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST) && !empty($_POST['nombre_pieza'])) {
    $nombre_pieza = $_POST['nombre_pieza'];
    $query = BuscarPieza($nombre_pieza);
}

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
                onclick="controlDePaginas(<?php echo $controlPaginas['paginaActual']; ?>, <?php echo $controlPaginas['totalPaginas']; ?>, 'anterior', 'piezas')">
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
                    onclick="controlDePaginas(<?php echo $controlPaginas['paginaActual']; ?>, <?php echo $controlPaginas['totalPaginas']; ?>, 'siguiente', 'piezas')">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M7 7l5 5l-5 5" />
                    <path d="M13 7l5 5l-5 5" />
                </svg>
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
                                        stroke-linejoin="round"
                                        class="icon icon-tabler icons-tabler-outline icon-tabler-check">
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
<script src="../assets/JS/control_paginas.js"> </script>
<script>
    pintarNegritas(<?php echo $controlPaginas["totalPaginas"]; ?>, <?php echo $controlPaginas["paginaActual"]; ?>);
</script>