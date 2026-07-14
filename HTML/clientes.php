<?php
include("../controllers/PHP/log_in.php");
verificarLogIn();
include("../config/connection.php");
include("../assets/HTML/layout.php");
include("../controllers/PHP/control_paginas.php");

$pagina = isset($_GET['page']) ? intval($_GET['page']) : 1;
$controlPaginas = controlPaginas(
    $conexion,
    "SELECT 
    e.nombre, e.rfc, e.correo, e.telefono, c.fecha_registro, c.id_cliente
    FROM clientes AS c
    JOIN empresas AS e ON e.id_cliente = c.id_cliente
    WHERE activo = 1
    ORDER BY nombre DESC LIMIT ? OFFSET ?",
    "SELECT COUNT(*) as total FROM clientes",
    "ii",
    $pagina
);

?>

<head>
    <title> Clientes </title>
</head>

<body>
    <div class="container">
        <h1> Clientes </h1>
        <br>
        <div class="table_scroll">
            <table>
                <thead>
                    <tr>
                        <th class="columnas"> Nombre </th>
                        <th class="columnas"> RFC </th>
                        <th class="columnas"> Correo </th>
                        <th class="columnas"> Teléfono </th>
                        <th class="columnas"> Fecha de registro </th>
                        <th class="columnas"> Acción </th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($controlPaginas["datos"] as $row) { ?>
                        <tr>
                            <td>
                                <?php echo htmlspecialchars($row['nombre']); ?>
                            </td>
                            <td>
                                <?php echo htmlspecialchars($row['rfc']); ?>
                            </td>
                            <td>
                                <?php echo htmlspecialchars($row['correo']); ?>
                            </td>
                            <td>
                                <?php echo htmlspecialchars($row['telefono']); ?>
                            </td>
                            <td>
                                <?php echo htmlspecialchars($row['fecha_registro']); ?>
                            </td>
                            <td>

                                <!-- Se envía una petición al servidor para poder mostrarlos en el Cuadro de Diálogo mediante JavaScript -->
                                <button class="TableButtonSvg" id="OpenModal"
                                    onclick="OpenModalEdit(<?php echo $row['id_cliente'] ?>)">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                        fill="currentColor" class="icon icon-tabler icons-tabler-filled icon-tabler-edit">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path
                                            d="M8 7a1 1 0 0 1 -1 1h-1a1 1 0 0 0 -1 1v9a1 1 0 0 0 1 1h9a1 1 0 0 0 1 -1v-1a1 1 0 0 1 2 0v1a3 3 0 0 1 -3 3h-9a3 3 0 0 1 -3 -3v-9a3 3 0 0 1 3 -3h1a1 1 0 0 1 1 1" />
                                        <path
                                            d="M14.596 5.011l4.392 4.392l-6.28 6.303a1 1 0 0 1 -.708 .294h-3a1 1 0 0 1 -1 -1v-3a1 1 0 0 1 .294 -.708zm6.496 -2.103a3.097 3.097 0 0 1 .165 4.203l-.164 .18l-.693 .694l-4.387 -4.387l.695 -.69a3.1 3.1 0 0 1 4.384 0" />
                                    </svg>
                                </button>

                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>

        <!-- Barra para control de paginas -->
        <div class="control_pages_bar">
            <div class="center_text_pagesbar"
                onclick="controlDePaginas(<?php echo $controlPaginas['paginaActual']; ?>, <?php echo $controlPaginas['totalPaginas']; ?>, 'anterior', 'clientes')">
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
                    onclick="controlDePaginas(<?php echo $controlPaginas['paginaActual']; ?>, <?php echo $controlPaginas['totalPaginas']; ?>, 'siguiente', 'clientes')">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M7 7l5 5l-5 5" />
                    <path d="M13 7l5 5l-5 5" />
                </svg>
            </div>
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
                </svg> </button>
        </div>
        <div class="DialogCenterItems">
            <div class="dialog_body">
                <!-- Formulario para enviar los datos al servidor para procesarlos -->
                <!-- SIEMPRE CERRAR CONTENEDORES APROPIADAMENTE COMO DIVS -->
                <form method="post" action="" id="formEditar">
                    <div class="center_items">
                        <!-- Nombre del cliente -->
                        <label> Nombre</label>
                        <input type="text" name="nombre" id="ClienteNombre" required>
                        <!-- Peso de la pieza -->
                        <label> RFC </label>
                        <input type="text" name="rfc" id='ClienteRFC' required>
                        <!-- RFC del cliente -->
                        <label> Correo </label>
                        <input type="text" name="correo" id='ClienteCorreo' required>
                        <label> Teléfono </label>
                        <input type="text" name="telefono" id='ClienteNumero' required>
                        <input type="hidden" name='id_cliente' id="ClienteId">
                        <button class="button" type="submit" name="accion" value="finalizar">
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
                    <button class="button" onclick="EliminarCliente()">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="currentColor" class="icon icon-tabler icons-tabler-filled icon-tabler-trash">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path
                                d="M20 6a1 1 0 0 1 .117 1.993l-.117 .007h-.081l-.919 11a3 3 0 0 1 -2.824 2.995l-.176 .005h-8c-1.598 0 -2.904 -1.249 -2.992 -2.75l-.005 -.167l-.923 -11.083h-.08a1 1 0 0 1 -.117 -1.993l.117 -.007zm-10 4a1 1 0 0 0 -1 1v6a1 1 0 0 0 2 0v-6a1 1 0 0 0 -1 -1m4 0a1 1 0 0 0 -1 1v6a1 1 0 0 0 2 0v-6a1 1 0 0 0 -1 -1" />
                            <path
                                d="M14 2a2 2 0 0 1 2 2a1 1 0 0 1 -1.993 .117l-.007 -.117h-4l-.007 .117a1 1 0 0 1 -1.993 -.117a2 2 0 0 1 1.85 -1.995l.15 -.005z" />
                        </svg>
                    </button>
                    <br>
                </div>
            </div>
        </div>
    </dialog>


</body>
<script src="../assets/JS/clientes.js"></script>
<script src="../assets/JS/control_paginas.js"></script>
<script>
    pintarNegritas(<?php echo $controlPaginas["totalPaginas"]; ?>, <?php echo $controlPaginas["paginaActual"]; ?>);
</script>