<?php
include("../config/connection.php");
include("../assets/HTML/layout.php");

$PorPagina = 25;
$Pagina = isset($_GET['page']) && is_numeric($_GET['page']) ? intval($_GET['page']) : 1;
$offset = ($Pagina - 1) * $PorPagina; // Verifica numero de página para mostrar los registros, página 1 del 1 al 25, página 2 del 26 al 50 y así sucesivamente

$resultado = $conexion->query("SELECT COUNT(*) as total FROM stock_aluminio");
$resultado_query = $resultado->fetch_assoc();
$total = intval($resultado_query["total"]);

$totalPaginas = max(1, ceil($total / $PorPagina)); // calcula en cuántas páginas mostrar los registros

// obtener 25 registros ordenados alfabéticamente
$stmt = $conexion->prepare("SELECT id_stock AS no_registro,
 cantidad_kg AS cantidad, 
 fecha FROM stock_aluminio 
 ORDER BY id_stock DESC LIMIT ? OFFSET ?");
// En SQL, OFFSET se utiliza para saltar un número específico de filas en una consulta, 
// generalmente en combinación con LIMIT o FETCH, cuando estás paginando resultados.
$stmt->bind_param("ii", $PorPagina, $offset);
$stmt->execute();
$res = $stmt->get_result();

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
                    <th class="columnas">Cantidad total de aluminio en Kg</th>
                    <th class="columnas">Fecha y hora de registro</th>
                </tr>

                <?php while ($row = $res->fetch_assoc()) { ?>
                    <tr>
                        <td> <?php echo $row["no_registro"]; ?></td>
                        <td><?php echo $row["cantidad"]; ?></td>
                        <td><?php echo $row["fecha"]; ?></td>
                    </tr>
                <?php } ?>
            </table>
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
</body>