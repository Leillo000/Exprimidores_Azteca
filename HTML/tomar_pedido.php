<?php
include("../config/connection.php");

$query_producto = $conexion->query("SELECT nombre_producto AS nombre, id_producto FROM productos");
$query_cliente = $conexion->query("SELECT e.nombre AS nombre, c.id_cliente FROM clientes AS c
JOIN empresas as e ON e.id_cliente=c.id_cliente WHERE activo = 1");

// Se obtiene la cantidad de aluminio para verificar si es que se puede completar el pedido
$query_aluminio = $conexion->query("SELECT cantidad_kg FROM stock_aluminio ORDER BY fecha DESC LIMIT 1");

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="http://localhost/Codigo_Exprimidores_Azteca/CSS/registrar_datos.css">
    <link rel="stylesheet" href="http://localhost/Codigo_Exprimidores_Azteca/assets/CSS/nav.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Alan+Sans:wght@300..900&family=Annapurna+SIL:wght@400;700&family=Arimo:ital,wght@0,400..700;1,400..700&family=Bricolage+Grotesque:opsz,wght@12..96,200..800&family=Epunda+Sans:ital,wght@0,300..900;1,300..900&family=Epunda+Slab:ital,wght@0,300..900;1,300..900&family=Montserrat:ital,wght@0,100..900;1,100..900&family=Open+Sans:ital,wght@0,300..800;1,300..800&family=Playfair+Display:ital,wght@0,400..900;1,400..900&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">
    <link href="../Images/logo_icon.ico" rel="icon" type="image/x-icon">
    </svg>
    <title> Nuevo pedido </title>
</head>

<body>
    <!-- Barra de navegación -->
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
        <h1> Nuevo pedido </h1>
        <br> <br>
        <form method="post" action="../controllers/procesar_carrito.php">
            <br>
            <label> Producto </label>
            <br>
            <!-- Obtiene los nombres de los productos de la base de datos -->
            <select name="id_producto" required>
                <?php while ($resultado_producto = $query_producto->fetch_assoc()) { ?>
                    <option value="<?php echo $resultado_producto["id_producto"]; ?>">
                        <?php echo $resultado_producto['nombre']; ?>
                    </option>
                <?php } ?>
            </select>
            <br> <br>
            <label> Cantidad </label>
            <br>
            <input type="number" name="cantidad" min=1>
            <br> <br>
                <!-- Seleccionar la acción -->
            <button class="button" type="submit" name="accion" value="agregar_producto"> Agregar producto </button>
            <button class="button" type="submit" name="accion" value="finalizar"> Finalizar Pedido </button>
        </form>
        <button class="button" onclick="location.href='menu.php'"> MENÚ </button>
</body>

</html>