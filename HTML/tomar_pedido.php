<?php
include("../config/connection.php");
include("../assets/HTML/layout.php");
$query_producto = $conexion->query("SELECT * FROM productos WHERE activo = 1");
?>
<head>
    <title> Nuevo pedido </title>
</head>
<body>
    <div class="container">
        <h1> Nuevo pedido </h1>
        <br>
        <form method="post" action="../controllers/procesar_carrito.php">
            <div class="center_items"> <label> Producto </label>
                <!-- Obtiene los nombres de los productos de la base de datos -->
                <select name="id_producto" required>
                    <?php while ($resultado_producto = $query_producto->fetch_assoc()) { ?>
                        <option value="<?php echo $resultado_producto["id_producto"]; ?>">
                            <?php echo $resultado_producto['nombre_producto']; ?>
                        </option>
                    <?php } ?>
                </select>
                <label> Cantidad </label>
                <input type="number" name="cantidad" min=1>
                <!-- Seleccionar la acción -->
                <button class="button" type="submit" name="accion" value="agregar_producto"> Agregar </button>
            </div>
        </form>
    </div>
</body>