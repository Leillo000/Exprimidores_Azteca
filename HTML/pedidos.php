<?php
include("../config/connection.php");
include("../helpers/utils.php");
include("../assets/HTML/layout.php");
include("../controllers/PHP/control_paginas.php");

$fechaActual = substr(ObtenerFecha(), 0, 10);

$pagina = isset($_GET['page']) ? intval($_GET['page']) : 1;
$palabraABuscar = isset($_GET['filtro']) ? $_GET['filtro'] : "";

$fechaDesde = isset($_GET['desde']) ? $_GET['desde'] : "";
if (!empty($_GET['hasta']) && $_GET['hasta'] != "") {
    $fechaHasta = $_GET['hasta'];
} else {
    $fechaHasta = $fechaActual;
}

if (empty($palabraABuscar) && empty($fechaDesde) && empty($fechaHasta)) {
    $query = "SELECT pd.id_pedido AS no_pedido, e.nombre AS nombre_cliente, 
    pd.etapa AS tipo_etapa, pd.tipo_observacion AS tipo_observ, pd.fecha AS fecha,
    pd.pesaje_total AS pesaje
    FROM pedidos pd 
    JOIN empresas e ON e.id_cliente = pd.id_cliente";
    $query_count = "SELECT COUNT(*) as total FROM pedidos";
} else {
    $query_dct = $db->doSearch("SELECT pd.id_pedido AS no_pedido, e.nombre AS nombre_cliente, 
    pd.etapa AS tipo_etapa, pd.tipo_observacion AS tipo_observ, pd.fecha AS fecha,
    pd.pesaje_total AS pesaje
    FROM pedidos pd 
    JOIN empresas e ON e.id_cliente = pd.id_cliente",
        "SELECT COUNT(*) AS total FROM pedidos pd 
    JOIN empresas e ON e.id_cliente = pd.id_cliente",
        $palabraABuscar,
        $fechaDesde,
        $fechaHasta,
        ["e.nombre","pd.etapa", "pd.tipo_observacion", "pd.pesaje_total"]
    );

    $query = $query_dct['query'];
    $query_count = $query_dct['query_count'];
}

$controlPaginas = controlPaginas(
    $conexion,
    $query . " ORDER BY id_pedido DESC LIMIT ? OFFSET ?",
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
        <h1> Pedidos </h1>
        <br>
        <form method="get" action="pedidos.php">
            <!-- Este div lo que hace es poner en una sola línea (y centrados) el boton para buscar y el input que es la barra de busqueda -->
            <div class="search_container">
                <input id="campoBusqueda" name="filtro" type="text" placeholder="Buscar movimientos o clientes... ">
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
        <div class="table_scroll">
            <table>
                <thead>
                    <tr>
                        <th class="columnas"> No. Pedido </th>
                        <th class="columnas"> Cliente </th>
                        <th class="columnas"> Etapa </th>
                        <th class="columnas" id="observaciones"> Observación </th>
                        <th class="columnas"> Fecha </th>
                        <th class="columnas"> Pesaje total en Kg.</th>
                        <th class="columnas"> Acción </th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($controlPaginas["datos"] as $row) { ?>
                        <tr>
                            <td> <?php echo htmlspecialchars($row['no_pedido']); ?> </td>
                            <td> <?php echo htmlspecialchars($row['nombre_cliente']); ?> </td>
                            <td> <?php echo htmlspecialchars($row['tipo_etapa']); ?> </td>
                            <td> <?php echo htmlspecialchars($row['tipo_observ']); ?> </td>
                            <td> <?php echo htmlspecialchars($row['fecha']); ?> </td>
                            <td> <?php echo htmlspecialchars($row['pesaje']); ?> </td>
                            <td>
                                <!-- Después de cada onchange, se invoca la función de Javascript junto a lo que se quiera hacer -->
                                <select class="button_table"
                                    onchange="redirigir(this.value, <?php echo $row['no_pedido']; ?>)">
                                    <option class="button_table" value="" disabled selected hidden> Seleccionar acción
                                    </option>
                                    <option class="button_table" value="detalles_observaciones"> Ver observaciones </option>
                                    <option class="button_table" value="detalles_pedido"> Ver detalles del pedido</option>
                                    <option class="button_table" value="agregar_observaciones"> Agregar observaciones
                                    </option>
                                    <option class="button_table" value="siguiente_etapa"> Pasar a la siguiente etapa
                                    </option>
                                    <option class="button_table" value="anterior_etapa"> Pasar a la anterior etapa </option>
                                </select>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>

        <!-- Barra para control de paginas -->
        <div class="control_pages_bar">
            <div class="center_text_pagesbar"
                onclick="controlDePaginas(<?php echo $controlPaginas['paginaActual']; ?>, <?php echo $controlPaginas['totalPaginas']; ?>, 'anterior', 'pedidos', '<?php echo $palabraABuscar; ?>', '<?php echo $fechaDesde ?>', '<?php echo $fechaHasta ?>')">
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
                    onclick="controlDePaginas(<?php echo $controlPaginas['paginaActual']; ?>, <?php echo $controlPaginas['totalPaginas']; ?>, 'siguiente', 'pedidos', '<?php echo $palabraABuscar; ?>', '<?php echo $fechaDesde ?>', '<?php echo $fechaHasta ?>')">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M7 7l5 5l-5 5" />
                    <path d="M13 7l5 5l-5 5" />
                </svg>
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
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-check">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M5 12l5 5l10 -10" />
                            </svg>
                        </button>
                    </div>
                    <br>
                </div>
            </div>
        </dialog>
    </div>
    <script src="../assets/JS/pedidos.js"> </script>
    <script src="../assets/JS/control_paginas.js"> </script>
    <script src="../assets/JS/control_dialogos.js"> </script>
    <script>
        pintarNegritas(<?php echo $controlPaginas["totalPaginas"]; ?>, <?php echo $controlPaginas["paginaActual"]; ?>);
    </script>
</body>