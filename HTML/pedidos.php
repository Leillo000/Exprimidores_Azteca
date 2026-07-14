<?php
include("../controllers/PHP/log_in.php");
verificarLogIn();
include("../config/connection.php");
include("../assets/HTML/layout.php");
include("../controllers/PHP/control_paginas.php");

$pagina = isset($_GET['page']) ? intval($_GET['page']) : 1;

$controlPaginas = controlPaginas(
    $conexion,
    "SELECT pd.id_pedido AS no_pedido, e.nombre AS nombre_cliente, 
    pd.etapa AS tipo_etapa, pd.tipo_observacion AS tipo_observ, pd.fecha AS fecha
    FROM pedidos pd 
    JOIN empresas e ON e.id_cliente = pd.id_cliente
    ORDER BY pd.fecha DESC LIMIT ? OFFSET ?",
    "SELECT COUNT(*) as total FROM pedidos",
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
        <div class="table_scroll">
            <table>
                <thead>
                    <tr>
                        <th class="columnas"> No. Pedido </th>
                        <th class="columnas"> Cliente </th>
                        <th class="columnas"> Etapa </th>
                        <th class="columnas" id="observaciones"> Observación </th>
                        <th class="columnas"> Fecha </th>
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
                            <td>
                                <!-- Después de cada onchange, se invoca la función de Javascript junto a lo que se quiera hacer -->
                                <select class="button_table"
                                    onchange="redirigir(this.value, <?php echo $row['no_pedido']; ?>)">
                                    <option class="button_table" value="" disabled selected hidden> Seleccionar acción
                                    </option>
                                    <option class="button_table" value="detalles"> Ver detalles </option>
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
                onclick="controlDePaginas(<?php echo $controlPaginas['paginaActual']; ?>, <?php echo $controlPaginas['totalPaginas']; ?>, 'anterior', 'pedidos')">
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
                    onclick="controlDePaginas(<?php echo $controlPaginas['paginaActual']; ?>, <?php echo $controlPaginas['totalPaginas']; ?>, 'siguiente', 'pedidos')">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M7 7l5 5l-5 5" />
                    <path d="M13 7l5 5l-5 5" />
                </svg>
            </div>
        </div>

    </div>
    <script src="../assets/JS/pedidos.js"> </script>
    <script src="../assets/JS/control_paginas.js"> </script>
    <script>
        pintarNegritas(<?php echo $controlPaginas["totalPaginas"]; ?>, <?php echo $controlPaginas["paginaActual"]; ?>);
    </script>
</body>