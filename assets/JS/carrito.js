// Abrir cuadro de dialogo

const btnOpenDialog = document.querySelector('#btnOpenDialog');
const Dialog = document.querySelector('Dialog');
const btnCloseDialog = document.querySelector('#btnCloseDialog');

// Agregamos un evento al darle click a esta parte del documento
btnOpenDialog.addEventListener('click', ()=>{
    Dialog.showModal();
})

btnCloseDialog.addEventListener('click', ()=>{
    Dialog.close();
})
