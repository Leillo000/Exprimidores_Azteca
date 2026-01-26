<?php
include("../config/connection.php");
include("../assets/HTML/layout.php");
$PorPagina = 10;
$Pagina = isset($_GET['page']) && is_numeric($_GET['page']) ? intval($_GET['page']) : 1;
$offset = ($Pagina - 1) * $PorPagina; // Verifica numero de página para mostrar los registros, página 1 del 1 al 25, página 2 del 26 al 50 y así sucesivamente

$resultado = $conexion->query("SELECT COUNT(*) as total FROM pedidos");
$resultado_query = $resultado->fetch_assoc();
$total = intval($resultado_query["total"]);

$totalPaginas = max(1, ceil($total / $PorPagina)); // calcula en cuántas páginas mostrar los registros

$stmt = $conexion->prepare("SELECT pd.id_pedido AS no_pedido, e.nombre AS nombre_cliente, pd.etapa AS tipo_etapa, pd.tipo_observacion AS tipo_observ, pd.fecha AS fecha
FROM pedidos pd 
JOIN empresas e ON e.id_cliente = pd.id_cliente
ORDER BY pd.fecha DESC LIMIT ? OFFSET ?");
$stmt->bind_param("ii", $PorPagina, $offset);
$stmt->execute();
$res = $stmt->get_result();
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
                <?php while ($row = $res->fetch_assoc()): ?>
                    <tr>
                        <td> <?php echo htmlspecialchars($row['no_pedido']); ?> </td>
                        <td> <?php echo htmlspecialchars($row['nombre_cliente']); ?> </td>
                        <td> <?php echo htmlspecialchars($row['tipo_etapa']); ?> </td>
                        <td> <?php echo htmlspecialchars($row['tipo_observ']); ?> </td>
                        <td> <?php echo htmlspecialchars($row['fecha']); ?> </td>
                        <td>
                            <!-- Después de cada onchange, se invoca la función de Javascript junto a lo que se quiera hacer -->
                            <select class="button_table" onchange="redirigir(this.value, <?php echo $row['no_pedido']; ?>)">
                                <option class="button_table" value="" disabled selected hidden> Seleccionar acción </option>
                                <option class="button_table" value="detalles"> Ver detalles </option>
                                <option class="button_table" value="agregar_observaciones"> Agregar observaciones </option>
                                <option class="button_table" value="siguiente_etapa"> Pasar a la siguiente etapa </option>
                                <option class="button_table" value="anterior_etapa"> Pasar a la anterior etapa </option>
                            </select>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
    <div class="center_items">
        <?php if ($Pagina > 1) { ?>
            <a href="?page=1">
                <b><< Primero</b></a>
                    <a href="?page=<?php echo $Pagina - 1 ?>"><b>< Anterior</b></a>
                <?php } 
                else if ($Pagina = 1){?>
                <!-- En caso de estar en la primera página se "desactivan" los links para ir a la siguiente pagina -->
                <p><< Primero</p>
                <p>< Anterior</p>
                <?php } ?>
                <p> Página <?php echo $Pagina; ?> de <?php echo $totalPaginas; ?></p>
                <?php
                // En caso de que la pagina actual sea la ultima, los links se "desactivan", pero solo se pone un parrafo a su vez
                if($Pagina == $totalPaginas){?>
                    <p>Siguiente</p>
                    <p>Última página >></p>
               <?php }
                 else if ($Pagina < $totalPaginas) {
                    ?>
                    <a href="?page=<?php echo $Pagina + 1; ?>"><b> Siguiente</b></a>
                    <a href="?page=<?php echo $totalPaginas; ?>"><b>Última página >></b> </a>
                <?php } ?>

    </div>
    </div>
    <script src="../assets/JS/pedidos.js"> </script>
</body>