<?php
include("../config/connection.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST)) {

    // Recibir id de la pieza (desde la tabla en piezas.php)
    $id_pieza = isset($_POST['id_pieza']) ? intval($_POST['id_pieza']) : 0;

    $query_pieza = $conexion->prepare(
        'SELECT pz.id_pieza, pz.nombre_pieza, pz.peso, pd.id_producto, pd.nombre_producto
        FROM piezas pz
        JOIN productos pd ON pz.id_producto = pd.id_producto
        WHERE pz.id_pieza = ?
        LIMIT 1'
    );

    if ($query_pieza === false) {
        die("Prepare failed: " . $conexion->error);
    }

    $query_pieza->bind_param('i', $id_pieza);
    $query_pieza->execute();
    $res_pieza = $query_pieza->get_result();
    $pieza = $res_pieza->fetch_assoc();

    if (!$pieza) {
        header("Location: piezas.php");
        exit();
    }

} else {
    header("Location: piezas.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="http://localhost/Codigo_Exprimidores_Azteca/CSS/registrar_datos.css">
    <link rel="stylesheet" href="http://localhost/Codigo_Exprimidores_Azteca/assets/CSS/nav.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Alan+Sans:wght@300..900&family=Annapurna+SIL:wght@400;700&family=Arimo:ital,wght@0,400..700;1,400..700&family=Bricolage+Grotesque:opsz,wght@12..96,200..800&family=Epunda+Sans:ital,wght@0,300..900;1,300..900&family=Epunda+Slab:ital,wght@0,300..900;1,300..900&family=Montserrat:ital,wght@0,100..900;1,100..900&family=Open+Sans:ital,wght@0,300..800;1,300..800&family=Playfair+Display:ital,wght@0,400..900;1,400..900&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">
    <link href="../Images/logo_icon.ico" rel="icon" type="image/x-icon">
    <title> Editar piezas </title>

</html>

<body>
    <div>   
        <h1> Piezas </h1>
        <br>
<!-- Muestra el nombre del producto al que pertenece la pieza -->
        <h2> <?php echo htmlspecialchars($pieza['nombre_producto']); ?> </h2>

<!-- Los datos se envían a procesar_piezas.php -->
        
    </div>
</body>

</html>