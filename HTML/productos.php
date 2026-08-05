<?php
include("../config/connection.php");
include("../helpers/singleton_connection.php");
include("../helpers/utils.php");
include("../assets/HTML/layout.php");
include("../controllers/PHP/control_paginas.php");

$db = Database::getDatabase();

$pagina = isset($_GET['page']) ? intval($_GET['page']) : 1;
$palabraABuscar = isset($_GET['filtro']) ? $_GET['filtro'] : "";
if (empty($palabraABuscar) && empty($fechaDesde) && empty($fechaHasta)) {
    $query = "SELECT * FROM productos ";
    $query_count = "SELECT COUNT(*) as total FROM productos";
} else {
    $query_dct = $db->doSearch("SELECT * FROM productos", "SELECT COUNT(*) AS total FROM productos", $palabraABuscar, "", "", ["nombre_producto", "precio_unitario", "peso"]);
    $query = $query_dct['query'];
    $query_count = $query_dct['query_count'];
}
$controlPaginas = controlPaginas(
    $conexion,
    $query . " ORDER BY nombre_producto DESC LIMIT ? OFFSET ?",
    $query_count,
    "ii",
    $pagina
);

?>

<head>
    <title> Productos </title>
</head>

<body>
    <div class="container">
        <h1> Productos </h1>
        <br>
        <!-- Buscar producto -->
        <form method="get" action="productos.php">
            <!-- Se pone el nombre del producto dentro del input -->
            <div class="search_container">
                <input name="filtro" id="campoBusqueda" type="text" placeholder="Buscar producto... ">
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
                    <?php foreach ($controlPaginas['datos'] as $row) { ?>
                        <tr>
                            <td> <?php echo htmlspecialchars($row['id_producto']); ?> </td>
                            <td> <?php echo htmlspecialchars($row['nombre_producto']); ?> </td>
                            <td> $<?php echo htmlspecialchars(number_format($row['precio_unitario'], 2)); ?> </td>
                            <td> <?php echo htmlspecialchars(number_format($row['peso'], 2)); ?> gr</td>
                        </tr>
                    <?php } ?>

                </tbody>
            </table>
        </div>
        <br>
        <!-- Barra para control de paginas -->
        <div class="control_pages_bar">
            <div class="center_text_pagesbar"
                onclick="controlDePaginas(<?php echo $controlPaginas['paginaActual']; ?>, <?php echo $controlPaginas['totalPaginas']; ?>, 'anterior', 'productos', '<?php echo $palabraABuscar; ?>', '', '')">
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
                    Página
                    <?php echo $controlPaginas["paginaActual"]; ?> de
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
                    onclick="controlDePaginas(<?php echo $controlPaginas['paginaActual']; ?>, <?php echo $controlPaginas['totalPaginas']; ?>, 'siguiente', 'productos', '<?php echo $palabraABuscar; ?>', '', '')">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M7 7l5 5l-5 5" />
                    <path d="M13 7l5 5l-5 5" />
            </div>
        </div>

    </div>
</body>