<?php
include("../config/connection.php");

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
ORDER BY nombre DESC LIMIT ? OFFSET ?");
$stmt->bind_param("ii", $PorPagina, $offset);
$stmt->execute();
$res = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="http://localhost/Codigo_Exprimidores_Azteca/CSS/pedidos.css">
    <link rel="stylesheet" href="http://localhost/Codigo_Exprimidores_Azteca/assets/CSS/nav.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Alan+Sans:wght@300..900&family=Annapurna+SIL:wght@400;700&family=Arimo:ital,wght@0,400..700;1,400..700&family=Bricolage+Grotesque:opsz,wght@12..96,200..800&family=Epunda+Sans:ital,wght@0,300..900;1,300..900&family=Epunda+Slab:ital,wght@0,300..900;1,300..900&family=Montserrat:ital,wght@0,100..900;1,100..900&family=Open+Sans:ital,wght@0,300..800;1,300..800&family=Playfair+Display:ital,wght@0,400..900;1,400..900&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">
    <link href="../Images/logo_icon.ico" rel="icon" type="image/x-icon">
    <title> Clientes </title>
</head>

<body>
    <nav>
        <img src="../Images/logo_menu.jpg" alt="logo_exprimidores_azteca">
        <ul>
            <div id="separate_link">
                <li>
                    <a href="piezas.php"> Piezas </a>
                </li>
                <li>
                    <a href="pedidos.php"> Pedidos </a>
                </li>
                <li>
                    <a href="productos.php"> Productos </a>
                </li>
                <li>
                    <a href="materiales.php"> Materiales </a>
                </li>
                <li>
                    <a href="carrito.php">
                        <!-- Icono de carrito -->
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="#2F6842" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="icon icon-tabler icons-tabler-outline icon-tabler-shopping-cart">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M6 19m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                            <path d="M17 19m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                            <path d="M17 17h-11v-14h-2" />
                            <path d="M6 5l14 1l-1 7h-13" />
                    </a>
                </li>
            </div>
        </ul>
    </nav>
    <br>
    <div>
        <h1> Clientes </h1>
        <br>
    </div>
    <div>
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
                        <td> <?php echo htmlspecialchars($row['nombre']); ?> </td>
                        <td> <?php echo htmlspecialchars($row['rfc']); ?> </td>
                        <td> <?php echo htmlspecialchars($row['correo']); ?> </td>
                        <td> <?php echo htmlspecialchars($row['telefono']); ?> </td>
                        <td> <?php echo htmlspecialchars($row['fecha_registro']); ?> </td>
                        <td>
                            <!-- Después de cada onchange, se invoca la función de Javascript junto a lo que se quiera hacer -->
                            <select class="button_table" onchange="redirigir(this.value, <?php echo $row["id_cliente"]; ?>)">
                                <option class="button_table" value="" disabled selected hidden> Seleccionar acción </option>
                                <option class="button_table" value="eliminar"> Eliminar </option>
                                <option class="button_table" value="editar"> Editar </option>
                            </select>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
    <div id="control_pages">
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
    <button class="button" onclick="location.href='agregar_clientes.php'"> Agregar </button>
    <button class="button" onclick="location.href='menu.php'"> MENÚ </button>
    <script src="../assets/JS/clientes.js"></script>
</body>

</html>