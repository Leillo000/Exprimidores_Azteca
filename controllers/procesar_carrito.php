<?php
// Se inicia una sesion con la URL del usuario
include("../config/connection.php");
include("../helpers/utils.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST)) {

    // ----- SE INICIALIZAN TODAS LAS VARIABLES QUE SE VAN A OCUPAR -----

    $accion = $_POST['accion'] ?? '';
    $producto = isset($_POST['id_producto']) ? intval($_POST['id_producto']) : 0;
    $cantidad_pedida = isset($_POST['cantidad']) ? intval($_POST['cantidad']) : 0;
    $cliente = isset($_POST['id_cliente']) ? intval($_POST['id_cliente']) : 0;
    $fecha = ObtenerFecha();
    $etapa = 'Fundición';
    $tipo_observacion = 'Ninguna';


    // ---- AGREGAR PRODUCTO ------

    if ($accion == 'agregar_producto') {

        if (!$cantidad_pedida || $cantidad_pedida == 0) {
            echo "<script>
            alert('Debes de seleccionar la cantidad.');
            window.location.href = '../HTML/tomar_pedido.php';
          </script>";
            exit();
        }

        // Verificar si existe un producto ya un producto agregado en el carrito para poder acumularlo
        $query_carrito = $conexion->prepare('SELECT id_producto, cantidad FROM carrito WHERE id_producto = ?');
        $query_carrito->bind_param('i', $producto);
        $query_carrito->execute();
        $result = $query_carrito->get_result();
        $carrito = $result->fetch_assoc();

        // SI EXISTE
        if ($carrito) {
            $nueva_cantidad = intval($carrito['cantidad'] + $cantidad_pedida);
            $update_carrito = $conexion->prepare('UPDATE carrito SET cantidad = ? WHERE id_producto = ?');
            $update_carrito->bind_param('ii', $nueva_cantidad, $producto);
            $update_carrito->execute();
        }
        // NO EXISTE
        else {
            $insert_carrito = $conexion->prepare('INSERT INTO carrito(id_producto, cantidad) 
    VALUES (?, ?)');
            $insert_carrito->bind_param('ii', $producto, $cantidad_pedida);
            $insert_carrito->execute();
        }
        echo "<script>
            alert('Producto agregado.');
            window.location.href = '../HTML/tomar_pedido.php';
          </script>";

    }

    //----- ELIMINAR DEL CARRITO -----

    if ($accion == 'eliminar') {
        $stmt_eliminar = $conexion->prepare("DELETE FROM carrito WHERE id_carrito= ?");
        $stmt_eliminar->bind_param('i', $_POST['id_carrito']);
        $stmt_eliminar->execute();
        header("Location: ../HTML/carrito.php");
    }

    //------ FINALIZAR PEDIDO ------

    if ($accion == 'finalizar') {

        $procesar_carrito = $conexion->query('SELECT * FROM carrito');
        $_carrito = $procesar_carrito->fetch_assoc();


        // ===== VERIFICAR SI HAY POR LO MENOS UN PRODUCTO EN EL PEDIDO =====

        if (!$_carrito) {
            echo "<script>
            alert('Debe de haber al menos 1 producto para finalizar el pedido.');
            window.location.href = '../HTML/tomar_pedido.php';
          </script>";
            exit();
        }

        //===== VERIFICAR SI HAY SUFICIENTE ALUMINIO =====

        // 1) Se obtiene el total de aluminio que se requiere para concretar el pedido
        // Itera n veces según los registros que tenga el carrito, es decir, sus distintos productos.

        $verificar_aluminio = $conexion->query('SELECT * FROM carrito');

        $total_aluminio_pedido_kg = 0;
        while ($_carrito_2 = $verificar_aluminio->fetch_assoc()) {

            $stmt = $conexion->prepare('SELECT peso FROM productos WHERE id_producto= ?');
            $stmt->bind_param('i', $_carrito_2['id_producto']);
            $stmt->execute();
            $res_peso = $stmt->get_result();
            $peso_u = $res_peso->fetch_assoc();
            $stmt->reset();
            // Obtiene el total de aluminio del pedido, multiplicando el peso unitario por la cantidad del producto pedido
            $total_aluminio_pedido_kg += (intval($peso_u['peso']) * intval($_carrito_2['cantidad'])) / 1000;
        }

        // Se considera la merma de aluminio
        $total_aluminio_pedido_kg += ($total_aluminio_pedido_kg * 0.1);

        // 2) Se obtiene el ultimo registro de aluminio, es decir, el aluminio actual

        $query_verificar = $conexion->query('SELECT cantidad_kg FROM stock_aluminio ORDER BY fecha DESC LIMIT 1');
        $stock_aluminio_actual = $query_verificar->fetch_assoc();

        // 3) VERIFICAR si hay stock necesario para poder realizar el pedido

        // ===== ASEGURARSE QUE EXISTA AL MENOS UN REGISTRO DE CUÁNTO ALUMINIO TIENE PARA PODER CONTINUAR =====

        if ($stock_aluminio_actual) {
            //Verifica que haya aluminio suficiente y que si el pedido supera el mínimo de stock. 
            // Se considera la merma
            $resultado_aluminio = $stock_aluminio_actual['cantidad_kg'] - $total_aluminio_pedido_kg ;

            if (
                $stock_aluminio_actual['cantidad_kg'] < $total_aluminio_pedido_kg
            ) {
                // Obtiene la diferencia entre el stock de aluminio con el que se cuenta y el pedido para mandar a fundicion
                $aluminio_requerido = $total_aluminio_pedido_kg - $stock_aluminio_actual['cantidad_kg'];
                echo "<script>
            alert('No se cuenta con suficiente aluminio. Se requieren " . $aluminio_requerido . " kg más para seguir con el pedido.');
            window.location.href = '../HTML/tomar_pedido.php';
          </script>";
                exit();
                // Verifica que la cantidad pedida no supere el minimo de stock
            } else if ($resultado_aluminio <= 200) {
                $aluminio_requerido = 201 - $resultado_aluminio;
                echo "<script>
            alert('¡ALERTA! No se cuenta con suficiente aluminio. El pedido supera el minimo de stock de aluminio. Se requieren " . $aluminio_requerido . " kg más para seguir con el pedido.');
            window.location.href = '../HTML/tomar_pedido.php';
          </script>";
                exit();
            }
        } else {
            echo "<script>
            alert('Debes de registrar primero cuanto aluminio tienes para poder seguir con el pedido.');
            window.location.href = '../HTML/tomar_pedido.php';
          </script>";
            ;
            exit();
        }


        // ===== SI EL PEDIDO CUMPLE CON LAS ANTERIORES CONDICIONES, ENTONCES EL PEDIDO PUEDE PROCEDER Y REALIZARSE =====


        // Se crea un nuevo pedido en la base de datos
        $stmt_2 = $conexion->prepare('INSERT INTO pedidos(id_cliente, fecha, etapa, tipo_observacion)
    VALUES (?, ?, ?, ?)');

        $stmt_2->bind_param('isss', $cliente, $fecha, $etapa, $tipo_observacion);
        $stmt_2->execute();

        // Se obtiene el ID del pedido para introducirlo en detalles_pedido
        $stmt_3 = $conexion->prepare('SELECT id_pedido FROM pedidos 
    WHERE id_cliente = ? AND fecha = ? ORDER BY fecha DESC LIMIT 1');

        $stmt_3->bind_param('is', $cliente, $fecha);
        $stmt_3->execute();

        $resultado = $stmt_3->get_result();
        $pedido_data = $resultado->fetch_assoc();

        // Verifica que pedido_data no sea null

        if ($pedido_data)
            $pedido = $pedido_data['id_pedido'];
        else {
            echo "<script>alert('No se introdujo el pedido con éxito.')</script>";
            exit();
        }
        // Se prepara la inserción a la base de datos de detalles pedido
        $stmt_4 = $conexion->prepare('INSERT INTO detalles_pedidos(id_pedido, id_producto, cantidad, subtotal) 
    VALUES (?, ?, ?, ?)');


        // Se crea una consulta para pasar los datos del carrito a detalles_pedido
        $procesar_carrito_1 = $conexion->query('SELECT id_producto, cantidad FROM carrito');

        while ($_carrito_1 = $procesar_carrito_1->fetch_assoc()) {

            // Se obtiene el precio unitario de cada pedido para saber el subtotal de cada producto
            $stmt_1 = $conexion->prepare('SELECT precio_unitario FROM productos WHERE id_producto = ?');
            $stmt_1->bind_param('i', $_carrito_1['id_producto']);
            $stmt_1->execute();

            $resultado_precio_u = $stmt_1->get_result();
            $precio_unitario = $resultado_precio_u->fetch_assoc();

            $subtotal = intval($_carrito_1['cantidad']) * floatval($precio_unitario['precio_unitario']);

            // Agregar los productos a detalles del producto
            $stmt_4->bind_param
            (
                'iiid',
                $pedido,
                $_carrito_1['id_producto'],
                $_carrito_1['cantidad'],
                $subtotal
            );
            $stmt_4->execute();

            // ESTA ES SUPER NECESARIA PARA PODER LIMPIAR LA SENTENCIA DE BIND_PARAM
            $stmt_4->reset();
        }

        // ===== RESTAR EL STOCK DE ALUMINIO =====
        $stmt_actualizar_aluminio = $conexion->prepare('INSERT INTO stock_aluminio(cantidad_kg, fecha) VALUES(?, ?)');
            $stmt_actualizar_aluminio->bind_param('ds', $resultado_aluminio, $fecha );
            $stmt_actualizar_aluminio->execute();
        // Se vacia la tabla de carrito
        $borrar_carrito = $conexion->query('DELETE FROM carrito');
        echo "<script>
    alert('Pedido realizado exitosamente.');
            window.location.href = '../HTML/menu.php';
            </script>";
        // Agregar un mensaje de finalizado que lo mande de nuevo a tomar_pedido.php que confirme este
        exit();
    }
} else {
    echo
        "<script>alert('Error en la validación de datos.');
        window.location.href = '../HTML/carrito.php';
        </script>";

}
?>