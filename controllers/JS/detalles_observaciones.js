
        function redirigir(accion, id_detalle_observacion, id_pedido) {
            if (accion === 'completar') {
                if (!confirm('¿Completar y eliminar esta observación?')) return;
                // Ruta relativa correcta y parámetro accion=eliminar para que el controlador lo procese
                window.location.href = '../controllers/baja_detalles_observaciones.php?accion=eliminar&id_detalle_observacion=' + encodeURIComponent(id_detalle_observacion) + "&id_pedido=" + encodeURIComponent(id_pedido);
            } else if (accion === 'editar') {
                window.location.href = 'editar_detalles_observaciones.php?id_detalle_observacion=' + encodeURIComponent(id_detalle_observacion) + "&id_pedido=" + encodeURIComponent(id_pedido);
            }
        }