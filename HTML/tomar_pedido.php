<?php
include("../config/connection.php");
include("../assets/HTML/layout.php");

$query_producto = $conexion->query("SELECT nombre_producto AS nombre, id_producto FROM productos");
$query_cliente = $conexion->query("SELECT e.nombre AS nombre, c.id_cliente FROM clientes AS c
JOIN empresas as e ON e.id_cliente=c.id_cliente WHERE activo = 1");

// Se obtiene la cantidad de aluminio para verificar si es que se puede completar el pedido
$query_aluminio = $conexion->query("SELECT cantidad_kg FROM stock_aluminio ORDER BY fecha DESC LIMIT 1");

?>


<head>
    <title> Nuevo pedido </title>
</head>

<body>
    <div class="container">
        <h1> Nuevo pedido </h1>
        <br> <br>
        <form method="post" action="../controllers/procesar_carrito.php">
            <div class="center_items"> <label> Producto </label>
                <!-- Obtiene los nombres de los productos de la base de datos -->
                <select name="id_producto" required>
                    <?php while ($resultado_producto = $query_producto->fetch_assoc()) { ?>
                        <option value="<?php echo $resultado_producto["id_producto"]; ?>">
                            <?php echo $resultado_producto['nombre']; ?>
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