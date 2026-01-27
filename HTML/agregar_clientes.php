<?php
include("../config/connection.php");
include("../assets/HTML/layout.php");
?>

<head>
    <title> Agregar clientes </title>

<body>
    <div class="container">
        <h1> Agregar cliente </h1>
        <br><br>

        <!-- ACUERDATE LEO INGAO, QUE ES ACTION NO LOCATION-->
        <form method="post" action="../controllers/PHP/procesar_cliente.php">
            <div class="center_items">
                <!-- Producto correspondiente -->

                <label> Nombre</label>
                <input type="text" name="nombre" required>
                <!-- Peso de la pieza -->
                <label> RFC </label>
                <input type="text" name="rfc" required>
                <!-- RFC del cliente -->
                <label> Correo </label>
                <input type="text" name="correo" required>
                <label> Teléfono </label>
                <input type="text" name="telefono" required>
                <button class="button" type="submit"> Agregar </button>
            </div>
        </form>
    </div>
</body>