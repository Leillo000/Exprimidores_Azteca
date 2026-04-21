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
        Dialog.showModal();   
    } else {
        alert('¡Algo salió mal!, revisa si el servidor está activo');
    }
}

