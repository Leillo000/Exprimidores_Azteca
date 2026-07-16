<?php
include("../controllers/PHP/log_in.php");
verificarLogIn();
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
    pro.nombre_producto, 
    dtp.subtotal AS precio_subtotal, 
    dtp.cantidad, 
    (pro.peso * dtp.cantidad) / 1000 AS peso_subtotal
    FROM pedidos AS pd
    JOIN detalles_pedidos AS dtp ON pd.id_pedido = dtp.id_pedido
    JOIN productos AS pro ON dtp.id_producto = pro.id_producto
    WHERE pd.id_pedido = " . (string) $id_pedido . "
    ORDER BY dtp.id_pedido DESC
    LIMIT ? OFFSET ?",
    "SELECT COUNT(*) as total FROM detalles_pedidos",
    "ii",
    $pagina
);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles - Pedido No. <?php echo $id_pedido; ?> </title>
</head>

<body>
    <div class="container">
        <h1> Pedido No. <?php echo $id_pedido; ?> </h1>

        <table>
            <tr>
                <th class="columnas"> Nombre del producto </th>
                <th class="columnas"> Cantidad </th>
                <th class="columnas"> Precio subtotal</th>
                <th class="columnas"> Peso subtotal en gramos </th>
            </tr>
            <?php foreach ($controlPaginas["datos"] as $row) {
                // Calcula el subtotal de aluminio a fundir considerando la merma 
                $subtotal = $row["peso_subtotal"];
                $subtotal += $subtotal * 0.1;
                ?>
                <tr>
                    <td>
                        <?php echo htmlspecialchars($row["nombre_producto"]); ?>
                    </td>
                    <td>
                        <?php echo htmlspecialchars($row["cantidad"]); ?>
                    </td>
                    <td>
                        <?php echo htmlspecialchars($row["precio_subtotal"]); ?>
                    </td>
                    <td>
                        <?php echo htmlspecialchars($subtotal) . ' kg'; ?>
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
            <p>
                El pesaje subtotal de cada producto está dado considerando la merma de un <b>10%</b>. Es decir, será el subtotal multiplicado por 1.10 o el subtotal más el 10% del mismo.
            </p>
        </div>
    </div>
</body>

</html>