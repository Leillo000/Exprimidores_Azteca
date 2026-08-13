<?php
include("../config/connection.php");
include("../assets/HTML/layout.php");
include("../controllers/PHP/control_paginas.php");

$id_pedido = isset($_GET['id_pedido']) ? intval($_GET['id_pedido']) : 0;
$observaciones = true;

if ($id_pedido <= 0) {
    echo ("<script>alert('Datos inválidos.');
window.location.href = 'pedidos.php';</script>");
    exit();
}

$pagina = isset($_GET['page']) ? intval($_GET['page']) : 1;
$controlPaginas = controlPaginas(
    $conexion,
    "SELECT  pd.id_pedido, 
    po.nombre_producto, 
    pz.nombre_pieza AS nombre_pieza, 
    pz.peso AS peso_unitario, 
    pz.id_pieza,
    dto.cantidad AS cantidad,
    dto.id_detalle_observacion
    FROM pedidos pd
    JOIN detalles_observaciones dto ON pd.id_pedido = dto.id_pedido
    JOIN piezas pz ON pz.id_pieza = dto.id_pieza
    JOIN productos AS po ON po.id_producto = pz.id_producto
    WHERE pd.id_pedido = " . (string) $id_pedido . "
    ORDER BY dto.id_pieza DESC
    LIMIT ? OFFSET ?",
    "SELECT COUNT(*) as total FROM detalles_observaciones",
    "ii",
    $pagina
);

$stmt_total = $conexion->prepare("SELECT pd.id_pedido,
pz.peso AS peso_unitario, 
dto.cantidad AS cantidad,
SUM(pz.peso * dto.cantidad) AS total
 FROM pedidos pd
 JOIN detalles_observaciones dto ON pd.id_pedido = dto.id_pedido
 JOIN piezas pz ON pz.id_pieza = dto.id_pieza
 WHERE pd.id_pedido = ?
 GROUP BY pd.id_pedido;
");

$stmt_total->bind_param("i", $id_pedido);
$stmt_total->execute();
$total = $stmt_total->get_result();
$total_aluminio = $total->fetch_assoc();

if (empty($total_aluminio)) {
    $observaciones = false;
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Alan+Sans:wght@300..900&family=Annapurna+SIL:wght@400;700&family=Arimo:ital,wght@0,400..700;1,400..700&family=Bricolage+Grotesque:opsz,wght@12..96,200..800&family=Epunda+Sans:ital,wght@0,300..900;1,300..900&family=Epunda+Slab:ital,wght@0,300..900;1,300..900&family=Montserrat:ital,wght@0,100..900;1,100..900&family=Open+Sans:ital,wght@0,300..800;1,300..800&family=Playfair+Display:ital,wght@0,400..900;1,400..900&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">
    <link href="../Images/logo_icon.ico" rel="icon" type="image/x-icon">
    <title> Detalles observaciones </title>

<body>
    <div class="container">
        <h1> Pedido No. <?php echo $id_pedido; ?> </h1>
        <!-- Si existen observaciones del producto, mostrar las páginas. -->
        <!-- Considerando la merma pone el total de aluminio que se debe volver a fundir -->
        <?php if ($observaciones == true) { ?>
            <table>
                <tr>
                    <th class="columnas"> Nombre del producto al que pertenece </th>
                    <th class="columnas"> Nombre pieza </th>
                    <th class="columnas"> Cantidad </th>
                    <th class="columnas"> Peso unitario en gramos </th>
                    <th class="columnas"> Subtotal en Kg </th>
                    <th class="columnas"> Acción </th>
                </tr>

                <?php foreach ($controlPaginas["datos"] as $row) {
                    // Calcula el subtotal de aluminio a fundir considerando la merma 
                    $subtotal = $row["cantidad"] * $row["peso_unitario"] / 1000;
                    $subtotal += $subtotal * 0.1;
                    ?>
                    <tr>
                        <td>
                            <?php echo htmlspecialchars($row["nombre_producto"]); ?>
                        </td>
                        <td>
                            <?php echo htmlspecialchars($row["nombre_pieza"]); ?>
                        </td>
                        <td>
                            <?php echo htmlspecialchars($row["cantidad"]); ?>
                        </td>
                        <td>
                            <?php echo htmlspecialchars($row["peso_unitario"]) . ' gr'; ?>
                        </td>
                        <td>
                            <?php echo htmlspecialchars($subtotal . " Kg"); ?>
                        </td>
                        <td>
                            <button class="TableButtonSvg" id="abrirDialogo"
                                onclick="verificarCantidad(<?php echo $row['id_detalle_observacion']; ?> )">
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
            </table>
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
            <br>
            <div class="center_items">
                <p>Mandar a fundición:
                    <b><?php echo htmlspecialchars(($total_aluminio['total'] + ($total_aluminio['total'] * 0.1)) / 1000); ?>
                        Kg de aluminio </b>
                </p>
            </div>
        <?php } else { ?>
            <br>
            <div class="center_items">
                <p><b>No hay nada que mandar a fundición.</b></p>
            </div>
        <?php } ?>
    </div>

    <dialog id="dialogo" class="dialog">
        <div class="dialog_header">
            <div class="dialog_header">
                <button class="btnDialog" id="cerrarDialogo"> <svg xmlns="http://www.w3.org/2000/svg" width="24"
                        height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round"
                        class="icon icon-tabler icons-tabler-outline icon-tabler-x">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M18 6l-12 12" />
                        <path d="M6 6l12 12" />
                    </svg> </button>
            </div>
        </div>
        <div class="DialogCenterItems">
            <div class="dialog_body">
                <form id="formLiberarPiezas" method="post">
                    <div class="center_items">
                        <br><br>
                        <h2>Liberar piezas</h2>
                        <label for="cantidad">Número de piezas a liberar</label>
                        <input type="number" id="cantidad" name="cantidad" min="1" required>
                        <input type="hidden" id="id_detalle_observacion" name="id_detalle_observacion" required>
                        <input type="hidden" id="id_pedido" name="id_pedido" value="<?php echo $id_pedido; ?>">
                        <input type="hidden" id="cantidad_max" name="cantidad_max" required>
                        <input type="hidden" id="peso" name="peso">
                        <input type="hidden" id="nombre_producto" name="nombre_producto">
                        <input type="hidden" id="nombre_pieza" name="nombre_pieza">
                        <button class="button" type="submit" id="">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-check">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M5 12l5 5l10 -10" />
                            </svg>
                        </button>
                    </div>
                </form>
                <br>
            </div>
        </div>
    </dialog>

</body>

<script src="../assets/JS/detalles_observaciones.js"> </script>
<script src="../assets/JS/control_paginas.js"></script>
<script src="../assets/JS/control_dialogos_v2.js"></script>
<script src="../assets/JS/detalles_observaciones.js"></script>
<script>
    pintarNegritas(<?php echo $controlPaginas["totalPaginas"]; ?>, <?php echo $controlPaginas["paginaActual"]; ?>);
</script>

</html>