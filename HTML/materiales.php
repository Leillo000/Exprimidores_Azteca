<?php
include("../config/connection.php");
include("../helpers/utils.php");
include("../assets/HTML/layout.php");
include("../controllers/PHP/control_paginas.php");

$fechaActual = substr(ObtenerFecha(), 0, 10);

$pagina = isset($_GET['page']) ? intval($_GET['page']) : 1;
$palabraABuscar = isset($_GET['filtro']) ? $_GET['filtro'] : "";

$fechaDesde = isset($_GET['desde']) ? $_GET['desde'] : "";
if (!empty($_GET['hasta']) && $_GET['hasta']  != ""){
    $fechaHasta = $_GET['hasta'];
} else {
    $fechaHasta = $fechaActual;
}

if (empty($palabraABuscar) && empty($fechaDesde) && empty($fechaHasta)) {
    $query = "SELECT * FROM stock_aluminio ";
    $query_count = "SELECT COUNT(*) as total FROM stock_aluminio";
} else {
    $query_dct = $db->doSearch("SELECT * FROM stock_aluminio","SELECT COUNT(*) AS total FROM stock_aluminio", $palabraABuscar, $fechaDesde, $fechaHasta, ["id_stock", "cantidad_kg", "tipo", "descripcion"]);
    $query = $query_dct['query'];
    $query_count = $query_dct['query_count'];
}
$controlPaginas = controlPaginas(
    $conexion,
    $query . " ORDER BY id_stock DESC LIMIT ? OFFSET ?",
    $query_count,
    "ii",
    $pagina
);
?>

<head>
    <title> Materiales </title>
</head>

<body>

    <div class="container">
        <h1> Materiales </h1>
        <br>
        <form method="get" action="materiales.php">
            <!-- Este div lo que hace es poner en una sola línea (y centrados) el boton para buscar y el input que es la barra de busqueda -->
            <div class="search_container">
                <input id="campoBusqueda" name="filtro" type="text"
                    placeholder="Buscar movimientos o clientes... ">
                <button class="button_search" type="submit">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="30" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="icon icon-tabler icons-tabler-outline icon-tabler-search">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M3 10a7 7 0 1 0 14 0a7 7 0 1 0 -14 0" />
                        <path d="M21 21l-6 -6" />
                    </svg>
                </button>

                <!-- Cambiar el value de estos inputs desde JavaScript-->
                <input type="hidden" value="" name="desde" id="fechaDesde">
                <input type="hidden" value="" name="hasta" id="fechaHasta">
                <button class="button_search" type="button" id="abrirFiltros">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="icon icon-tabler icons-tabler-outline icon-tabler-filter-2">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M4 6h16" />
                        <path d="M6 12h12" />
                        <path d="M9 18h6" />
                    </svg>
                </button>
            </div>
        </form>
        <br>
        <div class="center_items">
            <table>
                <tr>
                    <th class="columnas">No. de Registro</th>
                    <th class="columnas">Total de aluminio en Kg. </th>
                    <th class="columnas">Fecha y hora de registro</th>
                    <th class="columnas">Tipo</th>
                    <th class="columnas">Descripción</th>
                </tr>

                <?php foreach ($controlPaginas["datos"] as $row) { ?>
                    <tr>
                        <td> <?php echo $row["id_stock"]; ?></td>
                        <td><?php echo $row["cantidad_kg"]; ?></td>
                        <td><?php echo $row["fecha"]; ?></td>
                        <td><?php echo $row["tipo"]; ?></td>
                        <td><?php echo $row["descripcion"]; ?></td>
                    </tr>
                <?php } ?>
            </table>
        </div>

        <!-- Barra para control de paginas -->
        <div class="control_pages_bar">
            <div class="center_text_pagesbar"
                onclick="controlDePaginas(<?php echo $controlPaginas['paginaActual']; ?>, <?php echo $controlPaginas['totalPaginas']; ?>, 'anterior', 'materiales', '<?php echo $palabraABuscar; ?>', '<?php echo $fechaDesde ?>', '<?php echo $fechaHasta ?>')">
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
                    onclick="controlDePaginas(<?php echo $controlPaginas['paginaActual']; ?>, <?php echo $controlPaginas['totalPaginas']; ?>, 'siguiente', 'materiales', '<?php echo $palabraABuscar; ?>', '<?php echo $fechaDesde ?>', '<?php echo $fechaHasta ?>')">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M7 7l5 5l-5 5" />
                    <path d="M13 7l5 5l-5 5" />
            </div>
        </div>
        <br>
        <div class="center_items">
            <p>Las cantidades de aluminio se ordenan de la más reciente a la más antigua, <b>en orden descendiente</b>.
            </p>
        </div>
    </div>

    <dialog id="dialogFilters" class="dialog">
        <div class="dialog_header">
        </div>
        <div class="DialogCenterItems">
            <div class="dialog_body">
                <div class="center_items">
                    <h2>Fecha desde: </h2>
                    <input type="date" id="desde">
                    <h2>Fecha hasta: </h2>
                    <input type="date" id="hasta">
                    <button class="button" onclick="" id="closeFilters">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="icon icon-tabler icons-tabler-outline icon-tabler-check">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M5 12l5 5l10 -10" />
                        </svg>
                    </button>
                </div>
                <br>
            </div>
        </div>
    </dialog>
</body>
<script src="../assets/JS/control_paginas.js"> </script>
<script src="../assets/JS/control_dialogos.js"> </script>
<script>
    pintarNegritas(<?php echo $controlPaginas["totalPaginas"]; ?>, <?php echo $controlPaginas["paginaActual"]; ?>);
</script>