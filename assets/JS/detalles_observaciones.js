const dialogo = document.getElementById('dialogo');
const cerrarDialogo = document.getElementById('cerrarDialogo');

if (cerrarDialogo != null) {
    cerrarDialogo.addEventListener('click', () => {
        dialogo.close()
    })
}

async function verificarCantidad(id) {
    const url = "../controllers/PHP/detalles_observaciones?id_detalle_observacion=" + encodeURIComponent(id);
    const response = await fetch(url, {
        method: "GET"
    })
    const result = await response.json()
    document.getElementById('cantidad').max = result.cantidad
    document.getElementById('cantidad_max').value = result.cantidad
    document.getElementById('id_detalle_observacion').value = result.id
    document.getElementById('peso').value = result.peso
    document.getElementById('nombre_pieza').value = result.nombre_pieza
    document.getElementById('nombre_producto').value = result.nombre_producto
    document.getElementById('dialogo').showModal();
}

document.getElementById('formLiberarPiezas').addEventListener('submit', async (e) => {
    e.preventDefault();
    const formData = new FormData(e.target);
    const response = await fetch('../controllers/PHP/detalles_observaciones', {
        method: 'POST',
        body: formData
    });
    const result = await response.json();
    document.getElementById('dialogo').close();
    if (result.message){
        alert(result.message)
    }
    location.reload();
})