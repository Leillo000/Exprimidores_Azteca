<?php
include("../config/connection.php");
include("../assets/HTML/layout.php");
// Refactorizar esta parte en el futuro, mediante un array clave-valor
/*
 return [ "datos" => $res->fetch_all(MYSQLI_ASSOC), "total" => $total, "totalPaginas" => $totalPaginas, "paginaActual" => $pagina ];
 
 $resultado = paginacion(datos, "datos", datos.1)

 echo $resultado["totalPaginas"];
 
 output = 23 resultados.
 */

$PorPagina = 10;
$Pagina = isset($_GET['page']) && is_numeric($_GET['page']) ? intval($_GET['page']) : 1;
$offset = ($Pagina - 1) * $PorPagina; // Verifica numero de página para mostrar los registros, página 1 del 1 al 25, página 2 del 26 al 50 y así sucesivamente
$resultado = $conexion->query("SELECT COUNT(*) as total FROM clientes");
$resultado_query = $resultado->fetch_assoc();
$total = intval($resultado_query["total"]);

$totalPaginas = max(1, ceil($total / $PorPagina)); // calcula en cuántas páginas mostrar los registros

$stmt = $conexion->prepare(
    "SELECT 
e.nombre, e.rfc, e.correo, e.telefono, c.fecha_registro, c.id_cliente
FROM clientes AS c
JOIN empresas AS e ON e.id_cliente = c.id_cliente
WHERE activo = 1
ORDER BY nombre DESC LIMIT ? OFFSET ?"
);
$stmt->bind_param("ii", $PorPagina, $offset);
$stmt->execute();
$res = $stmt->get_result();

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
                    <?php while ($row = $res->fetch_assoc()): ?>
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
                                <button class="TableButtonSvg" id="OpenModal" onclick="OpenModalEdit(<?php echo $row['id_cliente'] ?>)">
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
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

        <div class="center_items">
            <?php if ($Pagina > 1) { ?>
                <a href="?page=1">
                    <b>
                        << Primero</b></a>
                <a href="?page=<?php echo $Pagina - 1 ?>"><b>
                        < Anterior</b></a>
            <?php } else if ($Pagina = 1) { ?>
                    <!-- En caso de estar en la primera página se "desactivan" los links para ir a la siguiente pagina -->
                    <p>
                        << Primero</p>
                            <p>
                                < Anterior</p>
                            <?php } ?>
                            <p> Página <?php echo $Pagina; ?> de <?php echo $totalPaginas; ?></p>
                            <?php
                            // En caso de que la pagina actual sea la ultima, los links se "desactivan", pero solo se pone un parrafo a su vez
                            if ($Pagina == $totalPaginas) { ?>
                                <p>Siguiente</p>
                                <p>Última página >></p>
                            <?php } else if ($Pagina < $totalPaginas) {
                                ?>
                                    <a href="?page=<?php echo $Pagina + 1; ?>"><b> Siguiente</b></a>
                                    <a href="?page=<?php echo $totalPaginas; ?>"><b>Última página >></b> </a>
                            <?php } ?>

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
            </div>
        </div>
    </dialog>
</body>
<script src="../assets/JS/clientes.js"></script>