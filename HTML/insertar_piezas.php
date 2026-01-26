<?php
include("../config/connection.php");
include('../assets/HTML/layout.php');
$query_producto = $conexion->query('SELECT nombre_producto, id_producto FROM productos');
?>

<head>
    <title>Agregar pieza</title>
</head>

<div class="container">
    <h1> Agregar pieza </h1>
    <br><br>

    <!-- ACUERDATE LEO INGAO, QUE ES ACTION NO LOCATION-->
    <form method="post" action="../controllers/procesar_piezas.php">
        <div class="center_items">
            <!-- Nombre de la pieza -->
            <label> Nombre de la pieza </label>
            <input type="text" name="nombre_pieza" required>

            <!-- Producto correspondiente -->

            <label> Producto al que corresponde </label>
            <select name="id_producto">
                <?php while ($option = $query_producto->fetch_assoc()) { ?>
                    <option value="<?php echo $option['id_producto']; ?>">
                        <?php echo $option['nombre_producto']; ?>
                    </option>
                <?php } ?>
            </select>
            <!-- Peso de la pieza -->
            <label> Peso en gramos </label>
            <input type="number" min="1" name="peso" required>
            <button class="button" type="submit"> Agregar </button>
        </div>

    </form>
</div>