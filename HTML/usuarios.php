<?php
include("../assets/HTML/layout.php");
include("../config/connection.php");
include("../controllers/PHP/control_paginas.php");
if ($isAdmin[0]["rol"] != 1) {
    ?>

    <head>
        <title> Sin acceso </title>
    </head>
    <div class="container">
        <div class="center_items">
            <h2>No tienes permisos para entrar a esta página</h2>
            <br>
            <p><b>Consulta con el administrador o el personal de soporte para ayudarte.</b></p>
        </div>
    </div>
<?php } else {

    $pagina = isset($_GET['page']) ? intval($_GET['page']) : 1;
    $palabraABuscar = isset($_GET['filtro']) ? $_GET['filtro'] : "";
    $query_dct = $db->doSearch(
        "SELECT * FROM usuarios",
        "SELECT COUNT(*) AS total FROM usuarios",
        $palabraABuscar,
        "",
        "",
        ["email", "access"]
    );

    $query = $query_dct["query"];
    $query_count = $query_dct["query_count"] . " AND rol != 1 ";

    $controlPaginas = controlPaginas(
        $conexion,
        $query . " ORDER BY email DESC LIMIT ? OFFSET ?",
        $query_count,
        "ii",
        $pagina
    );

    ?>

    <head>
        <title> Usuarios </title>
    </head>

    <body>
        <div class="container">
            <!-- Buscar Usuario -->
            <div class="center_items">
                <h2>Usuarios</h2>
            </div>
            <br>
            <form method="get" action="usuarios.php">
                <div class="search_container">
                    <input name="filtro" id="campoBusqueda" type="text" placeholder="Buscar usuario... ">
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
            <table>
                <thead>
                    <tr>
                        <th class="columnas">Email</th>
                        <th class="columnas">Verificar</th>
                        <th class="columnas">Puesto</th>
                    </tr>
                </thead>
                <?php foreach ($controlPaginas["datos"] as $row) { ?>
                    <tbody>
                        <tr>
                            <td><?php echo htmlspecialchars($row["email"]) ?></td>
                            <td><input class="checkbox" type="checkbox" name="access" value="<?php echo $row["access"] ?>"
                                    onchange="cambiarAcceso(<?php echo $row['user_id']; ?>, <?php echo $row['access']; ?>)">
                            </td>
                            <td>
                                <select class="button_table" name="rol" id="" onchange="cambiarPuesto(<?php echo $row['user_id']; ?>, this.value)">
                                    <option value="1" <?php if ($row["rol"] == 1) { echo "selected"; } ?>>Administrador</option>
                                    <option value="2" <?php if ($row["rol"] == 2) { echo "selected"; }?>>Trabajador</option>
                                </select>
                            </td>
                        </tr>
                    </tbody>
                <?php } ?>
            </table>

            <!-- Barra para control de paginas -->
            <div class="control_pages_bar">
                <div class="center_text_pagesbar"
                    onclick="controlDePaginas(<?php echo $controlPaginas['paginaActual']; ?>, <?php echo $controlPaginas['totalPaginas']; ?>, 'anterior', 'usuarios', '<?php echo $palabraABuscar; ?>', '', '')">
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
                        onclick="controlDePaginas(<?php echo $controlPaginas['paginaActual']; ?>, <?php echo $controlPaginas['totalPaginas']; ?>, 'siguiente', 'usuarios', '<?php echo $palabraABuscar; ?>', '', '')">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M7 7l5 5l-5 5" />
                        <path d="M13 7l5 5l-5 5" />
                </div>
            </div>
        </div>
    </body>
    <script src="../assets/JS/control_paginas.js"></script>
    <script>
        pintarNegritas(<?php echo $controlPaginas["totalPaginas"]; ?>, <?php echo $controlPaginas["paginaActual"]; ?>);
    </script>
    <script src="../assets/JS/usuarios.js"> </script>
<?php } ?>