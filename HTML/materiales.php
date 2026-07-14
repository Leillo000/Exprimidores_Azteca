<?php
include("../controllers/PHP/log_in.php");
verificarLogIn();
include("../config/connection.php");
include("../assets/HTML/layout.php");
include("../controllers/PHP/control_paginas.php");

$pagina = isset($_GET['page']) ? intval($_GET['page']) : 1;
$controlPaginas = controlPaginas(
    $conexion,
    "SELECT id_stock AS no_registro,
    cantidad_kg AS cantidad, 
    fecha, tipo, descripcion FROM stock_aluminio 
    ORDER BY id_stock DESC LIMIT ? OFFSET ?",
    "SELECT COUNT(*) as total FROM stock_aluminio",
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
                        <td> <?php echo $row["no_registro"]; ?></td>
                        <td><?php echo $row["cantidad"]; ?></td>
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
                onclick="controlDePaginas(<?php echo $controlPaginas['paginaActual']; ?>, <?php echo $controlPaginas['totalPaginas']; ?>, 'anterior', 'materiales')">
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
                    onclick="controlDePaginas(<?php echo $controlPaginas['paginaActual']; ?>, <?php echo $controlPaginas['totalPaginas']; ?>, 'siguiente', 'materiales')">
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
</body>

<script src="../assets/JS/control_paginas.js"> </script>
<script>
    pintarNegritas(<?php echo $controlPaginas["totalPaginas"]; ?>, <?php echo $controlPaginas["paginaActual"]; ?>);
</script>