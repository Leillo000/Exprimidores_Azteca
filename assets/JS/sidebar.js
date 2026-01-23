const toggleButton = document.getElementById('toggle_btn');
const sidebar = document.getElementById('sidebar');

function toggleSidebar() {

    // toggle "concatena" lo que se le pase de argumento si es que no lo tiene,
    //  y si lo tiene, lo elimina
    sidebar.classList.toggle('close');
    toggleButton.classList.toggle('rotate');


    /* ¿Qué es lo que hace?
    
    1.- Selecciona todos los elementos con la clase show (Los submenus abiertos)

    2.- Por cada elemento de nuestro array, le quita a este la propiedad "show" y "rotate"

    3.- Esto hace que la flechita se vuelva a su estado original, y la lista de elementos se deje de mostrar
    
    */
    CloseSubMenus();
}

function toggleSubMenu(button) {
    // Accede a la lista de clases del siguiente hermano directo de 
    // button y cambia la clase a show
    // El toggle lo que hace es como un switch, cuando es presionado, se agrega la clase show
    // y cuando es presionado de nuevo, esta clase se cambia

    // Esta condicion es necesaria para que se puede volver a su estado original el boton al darle click de nuevo
    // Ya que, si lo dejamos asi, va eliminar la clase show y en la siguiente linea lo va a volver a agregar
    // haciendo un bucle en que no se pueda cerrar un submenu debido a que este se cierra y abre al mismo tiempo.
    if (button.nextElementSibling.classList.contains('show') == false) {
        CloseSubMenus();
    }
    button.nextElementSibling.classList.toggle('show');
    button.classList.toggle('rotate');

    if (sidebar.classList.contains('close')) {
        // Elimina estas clases cuando el sidebar está cerrado y damos click a un submenu
        sidebar.classList.toggle('close');
        toggleButton.classList.toggle('rotate');
    }
}

function CloseSubMenus() {
    Array.from(sidebar.getElementsByClassName('show')).forEach(ul => {
        ul.classList.remove('show');
        ul.previousElementSibling.classList.remove('rotate');
    });
}
