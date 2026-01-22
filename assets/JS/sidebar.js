const toggleButton = document.getElementById('toggle_btn');
const sidebar = document.getElementById('sidebar');

function toggleSidebar(){
    sidebar.classList.toggle('close');
    toggleButton.classList.toggle('rotate');
}

function toggleSubMenu(button){
// Accede a la lista de clases del siguiente hermano directo de 
// button y cambia la clase a show
// El toggle lo que hace es como un switch, cuando es presionado, se agrega la clase show
// y cuando es presionado de nuevo, esta clase se cambia
button.nextElementSibling.classList.toggle('show');
button.classList.toggle('rotate');
}
