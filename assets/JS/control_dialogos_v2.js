const abrirDialogo = document.getElementById('abrirDialogo');
const dialogo = document.getElementById('dialogo');
const cerrarDialogo = document.getElementById('cerrarDialogo');

if (cerrarDialogo != null) {
    cerrarDialogo.addEventListener('click', () => {
        dialogo.close()
    })
}

if (abrirDialogo != null) {
    abrirDialogo.addEventListener('click', () => {
        dialogo.showModal()
    })
}
