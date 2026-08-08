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
        <form method="post" action="../controllers/PHP/procesar_producto.php">
            <div class="center_items">
                <label> Nombre</label>
                <input type="text" name="nombre" required>
                <label> Precio </label>
                <input type="number" name="precio_unitario" required>
                <button class="button" type="submit"> Agregar </button>
            </div>
        </form>
    </div>
</body>