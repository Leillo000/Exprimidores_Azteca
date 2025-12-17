
// Función para recorrer la tabla y aplicar estilos
function marcarFaltantes() {

    // Selecciona todas las filas de la tabla
    // Se crea un nuevo array con las filas que existen en la página
    // querySelectorAll va a seleccionar la lista de filas que existan.
    // Se seleccionan todas para que pueda agarrar el número de filas que existan y pueda iterar.

    const filas = document.querySelectorAll("table tr");

    //filas.forEach(fila=>{ Codigo de la funcion }) Es una forma simplificada cuando queremos iterar por cierto elemento
    // que ente caso es filas, que podría ser lo mismo que filas.forEach(Funcion(filas)=>{ Codigo de la funcion})
    // Y también lo podríamos escribir como filas.forEach(Funcion)

    // forEach es basicamente un for con el numero de elementos que tenga una lista, ya sean objetos, string, etc.
    // fila, también conocido como item en programación es el número de elementos que tiene nuestra lista a iterar.

    filas.forEach(fila => {
        // Busca la celda que corresponde a la columna "Etapa"
        // Selecciona 
        const celdas = fila.querySelectorAll("td");

        celdas.forEach(celda => {
            if (celda.textContent.trim() === "Faltan piezas") {
                // Pintar SOLO la celda en rojo
                celda.style.backgroundColor = "#c1571eff";
                celda.style.color = "white";
            }
            if (celda.textContent.trim() === "Completado") {
                // Pintar SOLO la celda en rojo
                celda.style.backgroundColor = "#96cf4bff";
                celda.style.color = "white";
            }
        });
    });
}

// Ejecutar la función cuando cargue la página
document.addEventListener("DOMContentLoaded", marcarFaltantes);

function redirigir(accion, no_pedido) {
    // Se obtiene el no_pedido como valor númerico.
    pedido = parseInt(no_pedido);
    if (accion === 'detalles') {
        window.location.href = 'detalles_observaciones.php?id_pedido=' + encodeURIComponent(pedido);
    } else if (accion === 'agregar_observaciones') {
        // Esta URL ya se protege mediante encodeURIComponent, ya que cuida que no se hagan consultas maliciosas.
        window.location.href = 'agregar_observaciones.php?id_pedido=' + encodeURIComponent(pedido);
    } else if (accion === 'siguiente_etapa' || accion === 'anterior_etapa') {
        // Si accion es igual a siguiente etapa, etapa = 1, si no etapa = 2.
        const etapa = accion === 'siguiente_etapa' ? 1 : 2;
        // ...existing code...
        window.location.href = '../controllers/PHP/control_etapas.php?id_pedido=' + pedido + '&etapa=' + etapa;
    }
}