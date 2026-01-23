<?php
include("../config/connection.php");
include('../assets/HTML/layout.php');
$query_producto = $conexion->query('SELECT nombre_producto, id_producto FROM productos');
?>

<!-- Esto se debe de hacer manualmente -->
<head>
    <title>Agregar entradas</title>
    <!-- Incrustar el archivo de JavaScript manualmente -->
    <script type="text/javascript" src="../assets/JS/sidebar.js" defer></script>
</head>
<!-- Contenedor que tiene todo adentro -->
<div class="container">
    <h1> Agregar aluminio </h1>
    <br><br>
    <div class="center_items">
        <form method="post" action="../controllers/PHP/agregar_aluminio.php">
            <!-- Nombre de la pieza -->
            <label> Cantidad </label>
            <input type="text" name="cantidad" min=1 required>
            <button class="button" type="submit"> Agregar </button>
        </form>
    </div>
</div>