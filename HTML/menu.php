<!-- menu.php  
    Solo se incluyen botones y link, no incluye consultas SQL -->
<?php /* include ("../Connection/connection.php");
$sql = "INSERT INTO empaquetado(nombre_empaquetado, dimensiones) VALUES (bolsa, '20x20x20') "; */ ?>

<!DOCTYPE html>
<html lang="es">

<head>
    <title>Menú</title>
    <link meta="UTF-8">
    <link name="viewport">
    <link rel="stylesheet" href="http://localhost/Codigo_Exprimidores_Azteca/CSS/menu.css">
    <link rel="stylesheet" href="http://localhost/Codigo_Exprimidores_Azteca/assets/CSS/nav.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Alan+Sans:wght@300..900&family=Annapurna+SIL:wght@400;700&family=Arimo:ital,wght@0,400..700;1,400..700&family=Bricolage+Grotesque:opsz,wght@12..96,200..800&family=Epunda+Sans:ital,wght@0,300..900;1,300..900&family=Epunda+Slab:ital,wght@0,300..900;1,300..900&family=Montserrat:ital,wght@0,100..900;1,100..900&family=Open+Sans:ital,wght@0,300..800;1,300..800&family=Playfair+Display:ital,wght@0,400..900;1,400..900&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">
    <link href="../Images/logo_icon.ico" rel="icon" type="image/x-icon">
</head>

<body>
    <nav>
        <div>
            <img src=" ../Images/logo_menu.jpg" alt="logo_exprimidores_azteca">
        </div>
        <ul>
            <div id="separate_link">
                <li>
                    <a href="productos.php">Productos</a>
                </li>
                <li>
                    <a href="piezas.php">Piezas</a>
                </li>
                <li>
                    <a href="materiales.php">Materiales</a>
                </li>
                <li>
                    <a href="pedidos.php"> Pedidos </a>
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
    <div id="organize_menu">
        <div id="organize_text">
            <div id="line_with_tittles">
                <img src="../Images/line.png" id="line">
                <div id="tittles_fit">
                    <br>
                    <h2>Exprimidores</h2>
                    <h1>Azteca</h1>
                </div>
            </div>
            <br>
            <p>Creamos un espacio amigable donde el usuario crezca en un ambiente de innovación y crecimiento
                empresarial. Con el fin de mejorar a futuro y crear un camino hacia el cambio y la actualidad.
            </p>
            <br>
            <div>
                <button class="button" onclick="location.href='tomar_pedido.php'"> NUEVO PEDIDO </button> 
            </div>
        </div>
        <div id="move_image">
            <br>
            <br>
            <br>
            <img src="../Images/orange.png" id="image_orange">
        </div>
    </div>
</body>

</html>