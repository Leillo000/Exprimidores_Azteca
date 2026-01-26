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
                        <th class="columnas"> Correo </th>
                        <th class="columnas"> Teléfono </th>
                        <th class="columnas"> Fecha registro </th>
                        <th class="columnas"> Fecha </th>
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
                                <!-- Después de cada onchange, se invoca la función de Javascript junto a lo que se quiera hacer -->
                                <select class="button_table" onchange="redirigir(this.value, <?php echo $row["id_cliente"]; ?>)">
                                    <option class="button_table" value="" disabled selected hidden> Seleccionar acción
                                    </option>
                                    <option class="button_table" value="eliminar"> Eliminar </option>
                                    <option class="button_table" value="editar"> Editar </option>
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
    <script src="../assets/JS/clientes.js"></script>