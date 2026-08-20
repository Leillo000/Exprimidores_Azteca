<?php
include("../helpers/utils.php");
include("../assets/HTML/layout.php");
include("../controllers/PHP/control_paginas.php");
if ($_SERVER['REQUEST_METHOD'] === "GET" && !empty($_GET)) {
    $id_pedido = isset($_GET["id_pedido"]) ? intval($_GET["id_pedido"]) : 0;

    if ($id_pedido <= 0) {
        echo "<script>alert('Número de pedido inválido');
        window.location.href = 'retorno.php'</script>";
        exit();
    }

    $pesajeTotal = $db->doQuery("SELECT pesaje_total FROM pedidos WHERE id_pedido=?", [$id_pedido]);
}

?>

<title>Agregar aluminio de fundición</title>

<body>
    <div class="container">
        <form action="fundicion.php">
            <div class="center_items">
                <h2>Agregar aluminio a fundición</h2>
                <br>
                <h2>Pedido No.
                    <?php echo $id_pedido; ?>
                </h2>
                <br>
                <label for="peso_supuesto">Peso supuesto</label>
                <input id="peso_supuesto" type="number" value="<?php echo $pesajeTotal[0]['pesaje_total'];?>" readonly>
                <label for="peso_retorno">Peso de retorno de alumino en Kg.</label>
                <input id="peso_retorno" type="number" min="0.001" max="<?php echo $pesajeTotal[0]['pesaje_total']; ?>">
                <button class="button" type=""> Agregar </button>
            </div>
        </form>
    </div>
</body>