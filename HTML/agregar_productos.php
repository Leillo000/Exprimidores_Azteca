<?php
include("../config/connection.php");
include("../assets/HTML/layout.php");
?>

<head>
    <title> Agregar productos </title>

<body>
    <div class="container">
        <h1> Agregar producto </h1>
        <br><br>

        <!-- ACUERDATE LEO INGAO, QUE ES ACTION NO LOCATION-->
        <form method="post" action="../controllers/PHP/procesar_producto.php">
            <div class="center_items">
                <!-- Producto correspondiente -->
                <label> Nombre</label>
                <input type="text" name="nombre" required>
                <!-- Precio del producto -->
                <label> Precio </label>
                <input type="number" name="precio_unitario" required>
                <!-- Peso del producto -->
                <label> Peso </label>
                <input type="number" name="peso" required>
                <button class="button" type="submit"> Agregar </button>
            </div>
        </form>
    </div>
</body>