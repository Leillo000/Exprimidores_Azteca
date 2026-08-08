const CloseDialog = document.getElementById('btnCloseDialog');
const Dialog = document.querySelector('Dialog');

btnCloseDialog.addEventListener('click', () => {
    Dialog.close();
})

function redirigir(accion, id_cliente) {
    // Se obtiene el no_pedido como valor númerico.
    cliente = parseInt(id_cliente);
    if (accion === 'eliminar') {
        window.location.href = '../controllers/PHP/eliminar_cliente.php?id_cliente=' + encodeURIComponent(id_cliente);
    }
}

// Mostrar datos en el modal
// Async es un tipo de función. Nos indica cómo se va a comportar la función. Con await estamos
// indicando que se pare la ejecución hasta que se obtenga la respuesta del servidor.
async function OpenModalEdit(id) {
    const respuesta = await fetch('../controllers/PHP/productos.php?id_producto=' + encodeURIComponent(id));

    if (respuesta.ok) {
        const data = await respuesta.json();
        document.getElementById('nombreProducto').value = data.nombre_producto;
        document.getElementById('precioUnitario').value = data.precio_unitario;
        document.getElementById('pesoProducto').value = data.peso;
        document.getElementById('productoId').value = data.id_producto;
        Dialog.showModal();
    } else {
        alert('¡Algo salió mal!, revisa si el servidor está activo');
    }
}

// Enviar formulario sin recargar
document.getElementById('formEditar').addEventListener('submit', async (e) => {
    // Previene que no se recargue la página al enviar un formulario
    e.preventDefault();
    // Se crea un nuevo objeto según el evento e, que en este caso nuestro evento es submit y
    // "le pasa" los datos en forma de un formulario
    const formData = new FormData((e).target);

    // Se manda petición a servidor, se especifica la acción y qué es lo que se envía
    await fetch('../controllers/PHP/productos.php', {
        method: 'POST',
        body: formData
    });

    alert('¡Actualizado con éxito!\nRecarga la página para ver los cambios');
    Dialog.close();
    location.reaload();
})

async function EliminarProducto() {

    const productoId = document.getElementById('productoId').value;
    if (!confirm("¿Estás seguro de eliminar este producto?")) return
    // Siempre especificar lo que vas a mandar
    const respuesta = await fetch('../controllers/PHP/productos.php?id_producto=' + encodeURIComponent(productoId), {
        method: 'DELETE'
    });
    const resultadoOperacion = await respuesta.json()
}