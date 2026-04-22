const CloseDialog = document.getElementById('btnCloseDialog');
const Dialog = document.querySelector('Dialog');

btnCloseDialog.addEventListener('click', ()=>{
    Dialog.close();
})

function redirigir(accion, id_cliente ) {
    // Se obtiene el no_pedido como valor númerico.
    cliente = parseInt(id_cliente);
    if (accion === 'eliminar') {
        window.location.href = '../controllers/PHP/eliminar_cliente.php?id_cliente=' + encodeURIComponent(id_cliente);
    } else if (accion === 'editar') {   
        // Esta URL ya se protege mediante encodeURIComponent, ya que cuida que no se hagan consultas maliciosas.
        window.location.href = '../HTML/editar_cliente.php?id_cliente=' + encodeURIComponent(id_cliente);
    } 
}

// Mostrar datos en el modal
// Async es un tipo de función. Nos indica cómo se va a comportar la función. Con await estamos
// indicando que se pare la ejecución hasta que se obtenga la respuesta del servidor.
async function OpenModalEdit(id){
    // Solicita petición al servidor según el id_cliente para obtener sus datos
    const respuesta = await fetch ('../controllers/PHP/GetClientes?id_cliente=' + encodeURIComponent(id));
    // Es para imprimir en consola, no es print, literalmente es imprimir bruh
    // console.log();

    if (respuesta.ok){
        const data = await respuesta.json();
        document.getElementById('ClienteNombre').value = data.nombre;
        document.getElementById('ClienteRFC').value = data.rfc;
        document.getElementById('ClienteCorreo').value = data.correo;
        document.getElementById('ClienteNumero').value = data.telefono;
        document.getElementById('ClienteId').value = data.id_cliente;
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
await fetch('../controllers/PHP/GetClientes', {
    method: 'POST',
    body: formData
});

console.log(formData);
alert('¡Actualizado con éxito!');
Dialog.close();
location.reaload();
})

