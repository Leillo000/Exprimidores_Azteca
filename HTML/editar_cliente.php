<?php
include("../config/connection.php");
include("../assets/HTML/layout.php");

if ($_SERVER['REQUEST_METHOD'] === 'GET' && !empty($_GET)) {

    // Recibir id de la cliente (desde la tabla en clientes.php)
    $id_cliente = isset($_GET['id_cliente']) ? intval($_GET['id_cliente']) : 0;

    $query_cliente = $conexion->prepare(
        'SELECT 
        e.nombre, e.rfc, e.correo, e.telefono, c.id_cliente
        FROM clientes AS c
        JOIN empresas AS e ON e.id_cliente = c.id_cliente
        WHERE c.id_cliente = ?'
    );

    if ($query_cliente === false) {
        die("Prepare failed: " . $conexion->error);
    }

    $query_cliente->bind_param('i', $id_cliente);
    $query_cliente->execute();
    $res_cliente = $query_cliente->get_result();
    $cliente = $res_cliente->fetch_assoc();

    if (!$cliente) {
        header("Location: clientes.php");
        exit();
    }

} else {
    header("Location: clientes.php");
    exit();
}
?>

<body>
    <div class="container">
        <h1> Clientes </h1>
        <br>
        <!-- Dirección para procesar datos-->
        <form method="post" action="../controllers/PHP/actualizar_cliente.php">
            <div class="center_items">
                <h2> <?php echo htmlspecialchars($cliente['nombre']); ?> </h2>
                <br>
                <!-- Editar datos del cliente -->
                <label> Nombre </label>
                <input type="text" name="nombre_cliente" required value="<?php echo $cliente['nombre']; ?>">
                <input type="hidden" name="id_cliente" value="<?php echo $cliente['id_cliente']; ?>">
                <label> RFC </label>
                <input type="text" name="rfc" required value="<?php echo $cliente['rfc']; ?>">
                <label> Correo </label>
                <input type="text" name="correo" required value="<?php echo $cliente['correo']; ?>">
                <label> Teléfono </label>
                <input type="text" name="telefono" required value="<?php echo $cliente['telefono']; ?>">
                <button class="button" type="submit"> Actualizar </button>
            </div>
        </form>
    </div>
</body>

</html>