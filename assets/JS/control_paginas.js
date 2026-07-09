function controlDePaginas(paginaActual, totalPaginas,accion,categoria) {
    switch (accion) {
        case "anterior":
            if (paginaActual > 1) {
                window.location.href = categoria + ".php?page=" + encodeURIComponent(paginaActual - 1)
            }
                console.log(paginaActual)
                console.log("anterior")
            break
        case "siguiente":
            if (paginaActual < totalPaginas) {
                window.location.href = categoria + ".php?page=" + encodeURIComponent(paginaActual + 1)
            }
                console.log("siguiente")
                console.log(paginaActual < totalPaginas)
                console.log(totalPaginas)
                console.log(paginaActual)
            break
    }
}