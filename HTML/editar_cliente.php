<?php
include("../config/connection.php");

if ($_SERVER['REQUEST_METHOD'] === 'GET' && !empty($_GET)) {

    // Recibir id de la cliente (desde la tabla en clientes.php)
    $id_cliente = isset($_GET['id_cliente']) ? intval($_GET['id_cliente']) : 0;

    $query_cliente = $conexion->prepare(
        'SELECT 
        e.nombre, e.rfc, e.correo, e.telefono, c.id_cliente
        FROM clientes AS c
        JOIN empresas AS e ON e.id_cliente = c.id_cliente
        WHERE c.id_cliente = ?'
    );

    if ($query_cliente === false) {
        die("Prepare failed: " . $conexion->error);
    }

    $query_cliente->bind_param('i', $id_cliente);
    $query_cliente->execute();
    $res_cliente = $query_cliente->get_result();
    $cliente = $res_cliente->fetch_assoc();

    if (!$cliente) {
        header("Location: clientes.php");
        exit();
    }

} else {
    header("Location: clientes.php");
    exit();
}
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
    <title> Editar clientes </title>

</html>

<body>
    <nav>
        <img src="../Images/logo_menu.jpg" alt="logo_exprimidores_azteca">
        <ul>
            <div id="separate_link">
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
                    <a href="clientes.php"> Clientes </a>
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
        <!-- Muestra el nombre del producto al que pertenece la cliente -->
        <h2> <?php echo htmlspecialchars($cliente['nombre']); ?> </h2>

        <!-- Los datos se envían a procesar_clientes.php -->
        <form method="post" action="../controllers/PHP/actualizar_cliente.php">

            <!-- Editar nombre de la cliente -->
            <label> Nombre </label>
            <br>
            <input type="text" name="nombre_cliente" required value="<?php echo $cliente['nombre']; ?>">
            <br>

            <!-- Id oculto del cliente -->
            <input type="hidden" name="id_cliente" value="<?php echo $cliente['id_cliente']; ?>">

            <!-- Editar RFC del cliente -->
            <label> RFC </label>
            <br>
            <input type="text" name="rfc" required value="<?php echo $cliente['rfc']; ?>">
            <br>
            <!-- Editar correo del cliente -->
            <label> Correo </label>
            <br>
            <input type="text" name="correo" required value="<?php echo $cliente['correo']; ?>">
            <br>
            <!-- Editar RFC del cliente -->
            <label> Teléfono </label>
            <br>
            <input type="text" name="telefono" required value="<?php echo $cliente['telefono']; ?>">
            <br>
            <button class="button" type="submit"> ACTUALIZAR </button>
        </form>
        <button class="button" onclick="location.href='menu.php'"> MENÚ </button>
    </div>
</body>

</html>