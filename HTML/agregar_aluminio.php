<?php
include("../config/connection.php");
include('../assets/HTML/layout.php');
$query_producto = $conexion->query('SELECT nombre_producto, id_producto FROM productos');
?>

<!-- Esto se debe de hacer manualmente -->

<head>
    <title>Agregar entradas</title>
    <!-- Incrustar el archivo de JavaScript manualmente -->
</head>
<!-- Contenedor que tiene todo adentro -->
<div class="container">
    <h1> Agregar aluminio </h1>
    <br><br>
    <form method="post" action="../controllers/PHP/agregar_aluminio.php">
        <div class="center_items">
            <!-- Nombre de la pieza -->
            <label> Cantidad </label>
            <input type="text" name="cantidad" min=1 required>
            <button class="button" type="submit"> Agregar </button>
        </div>
    </form>
</div>