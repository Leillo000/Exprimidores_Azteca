function controlDePaginas(paginaActual, totalPaginas, accion, categoria, filtro, desde, hasta) {
    if (busqueda != "" && busqueda != null) {
        busqueda = "&filtro=" + filtro
    }
    if ((desde != "") && (desde != null)) {
        busqueda += "&desde=" + encodeURIComponent(desde) + "&hasta=" + encodeURIComponent(hasta)
    }
    switch (accion) {
        case "anterior":
            if (paginaActual > 1) {
                window.location.href = categoria + ".php?page=" + paginaActual - 1 + busqueda
            }
            break
        case "siguiente":
            if (paginaActual < totalPaginas) {
                window.location.href = categoria + ".php?page=" + paginaActual + 1 + busqueda
            }
            break
    }
}

function pintarNegritas(totalPaginas, paginaActual) {
    const span_anterior = document.getElementById("control_anterior");
    const span_siguiente = document.getElementById("control_siguiente");
    const flecha_anterior = document.getElementById("left_row");
    const flecha_siguiente = document.getElementById("right_row");
    if (paginaActual > 1) {
        span_anterior.style.fontWeight = "bold"
        flecha_anterior.style.cursor = "pointer"
        span_anterior.style.cursor = "pointer"
    }
    if (paginaActual < totalPaginas) {
        span_siguiente.style.fontWeight = "bold"
        flecha_siguiente.style.cursor = "pointer"
        span_siguiente.style.cursor = "pointer"
    }
}