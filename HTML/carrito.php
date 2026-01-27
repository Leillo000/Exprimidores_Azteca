<?php
include("../config/connection.php");
include("../assets/HTML/layout.php");
$precio_total = 0;
$peso_total = 0;

// Crea una consulta de los artículos en el carrito
$query_carrito = $conexion->query('SELECT c.id_carrito, p.nombre_producto AS producto,p.precio_unitario AS precio, p.peso AS peso, c.cantidad AS cantidad 
FROM carrito AS c JOIN productos AS p 
ON p.id_producto = c.id_producto');

$query_clientes = $conexion->query("SELECT id_cliente, nombre FROM empresas WHERE activo = 1");

?>

<head>
    <title> Carrito </title>
</head>

<body>
    <div class="container">
        <h1> Carrito de pedido </h1>
        <br>
        <div class="center_items">
            <table>
                <thead>
                    <tr>
                        <th class="columnas"> Producto </th>
                        <th class="columnas"> Cantidad </th>
                        <th class="columnas"> Peso subtotal (Kg) </th>
                        <th class="columnas"> Precio subtotal </th>
                        <th class="columnas"> Acción </th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $query_carrito->fetch_assoc()):
                        // Se calcula el precio total del carrito junto con el peso total, es decir, cuánto aluminio deberá de requirir
                        $peso_subtotal = ($row['peso'] * $row['cantidad']) / 1000;
                        $precio_subtotal = $row['precio'] * $row['cantidad'];
                        $peso_total += $peso_subtotal;
                        $precio_total += $precio_subtotal;
                        ?>
                        <!-- Se agregan las filas -->
                        <tr>
                            <form method="post" action="../controllers/procesar_carrito.php">
                                <td>
                                    <?php echo htmlspecialchars($row['producto']); ?>
                                </td>
                                <td>
                                    <?php echo htmlspecialchars($row['cantidad']); ?>
                                </td>
                                <td>
                                    <?php echo ($peso_subtotal + ($peso_subtotal * 0.1)); ?>
                                </td>
                                <td>
                                    <?php echo '$' . htmlspecialchars($precio_subtotal); ?>
                                </td>
                                <td> <button type="submit" class="button_table" name="accion" value="eliminar"> Eliminar
                                    </button>
                                </td>
                                <input name="id_carrito" value="<?php echo $row['id_carrito']; ?>" type="hidden">
                            </form>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>

            <br><br>

            <p><b>Precio total:</b> $
                <?php echo htmlspecialchars($precio_total) ?>
            </p>
            <p><b>Peso total:</b>
                <?php echo htmlspecialchars($peso_total + ($peso_total * 0.1)) ?> Kg
            </p>
            <!-- Finalizar pedido -->


            <!-- Botón para abrir el cuadro de diálogo -->
            <button class="button" id="btnOpenDialog">
                Finalizar
            </button>
        </div>

    </div>
    <!-- Cuadro de Dialogo para seleccionar el cliente -->
    <dialog id="Dialog" class="dialog">
        <div class="dialog_header">
            <button class="btnDialog" id="btnCloseDialog"> X </button>
        </div>
        <div class="dialog_body">
            <label> Selecciona el cliente </label>

            <!-- Formulario para enviar los datos al servidor para procesarlos -->
            <form method="post" action="../controllers/procesar_carrito.php">
                <select name="id_cliente">
                    <?php while ($row_empresas = $query_clientes->fetch_assoc()) { ?>
                        <option value="<?php echo $row_empresas['id_cliente']; ?>">
                            <?php echo $row_empresas['nombre']; ?>
                        </option>
                    <?php } ?>
                </select>
                <button class="button" type="submit" name="accion" value="finalizar">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="icon icon-tabler icons-tabler-outline icon-tabler-check">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M5 12l5 5l10 -10" />
                    </svg> </button>
            </form>
        </div>
    </dialog>
</body>

<script src="../assets/JS/carrito.js"></script>