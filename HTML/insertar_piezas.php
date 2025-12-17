<?php
include("../config/connection.php");
$query_producto = $conexion->query('SELECT nombre_producto, id_producto FROM productos');
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
    <title> Agregar piezas </title>

</html>

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
                    <a href="pedidos.php"> Pedidos </a>
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
        <h1> Agregar pieza </h1>
        <br><br>

<!-- ACUERDATE LEO INGAO, QUE ES ACTION NO LOCATION-->
        <form method="post" action="../controllers/procesar_piezas.php">

<!-- Nombre de la pieza -->
            <label> Nombre de la pieza </label>
            <br>
            <input type="text" name="nombre_pieza" required>
            <br>

<!-- Producto correspondiente -->

            <label> Producto al que corresponde </label>
            <br>
            <select name="id_producto">
                <?php while($option = $query_producto->fetch_assoc()){ ?>
                <option value="<?php echo $option['id_producto']; ?>"> <?php echo $option['nombre_producto']; ?> </option>
                <?php } ?>
            </select>
            <br>

<!-- Peso de la pieza -->
            <label> Peso en gramos </label>
            <br>
            <input type="number" min="1" name="peso" required>
            <br> 
        <button class="button" type="submit"> Agregar </button>
        </form>

        <button class="button" onclick="location.href='menu.php'"> MENÚ </button>
    </div>
</body>

</html>