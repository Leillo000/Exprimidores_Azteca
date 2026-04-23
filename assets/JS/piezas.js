const Dialog = document.getElementById('Dialog');
const CloseDialog = document.getElementById('btnCloseDialog');

CloseDialog.addEventListener('click', ()=> {
    Dialog.close();
});

async function GetPieza(id){
    // Le pido al servidor los datos mediante metodo GET para que me devuelva el cliente con el id segun los parametros
    const respuesta = await fetch('../controllers/PHP/piezas.php?id_pieza=' + encodeURIComponent(id));
    if(respuesta.ok){
        const DataResponse = await respuesta.json();
        document.getElementById('nombre_pieza').value = DataResponse.nombre_pieza;
        document.getElementById('peso').value = DataResponse.peso;
        document.getElementById('nombre_producto').value = DataResponse.nombre_producto;
        Dialog.showModal();
    } else {
        alert('¡Algo salió mal!, revisa si el servidor está activo');
    }
        
}