<?php
include("../config/connection.php");

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
    <title> Pedidos </title>
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
        <h1> Pedidos </h1>
        <br>
        <!--
        <span> <input type="text" placeholder=" Buscar pedido... ">
            <a href="" alt="buscar" target="_blank">
                <img src="../Images/lupa_buscar.png" alt="icono_buscar" class="icon_search">
            </a> </span> -->
    </div>
    <div>
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
    <div id="control_pages">
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
    <button class="button" onclick="location.href='menu.php'"> MENÚ </button>

    <script src="../assets/JS/pedidos.js"> </script>
</body>

</html>