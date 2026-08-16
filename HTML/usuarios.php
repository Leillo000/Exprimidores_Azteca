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

    $query = $query_dct["query"] . " AND rol != 1 ";
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
                            <td><input class="checkbox" type="checkbox" name="access" value="<?php echo $row["access"] ?>" onchange="cambiarAcceso(<?php echo $row['user_id'];?>, <?php echo $row['access'];?>)"></td>
                            <td><?php echo htmlspecialchars($row["rol"]) ?></td>
                        </tr>
                    </tbody>
                <?php } ?>
            </table>
        </div>
    </body>

    <script src="../assets/JS/usuarios.js"> </script>
<?php } ?>