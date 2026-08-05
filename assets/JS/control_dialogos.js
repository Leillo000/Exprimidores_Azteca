const abrirFiltros = document.getElementById('abrirFiltros');
const dialogFilters = document.getElementById('dialogFilters');
const closeFilters = document.getElementById('closeFilters');
const desde = document.getElementById('desde')
const hasta = document.getElementById('hasta')
const fechaDesde = document.getElementById('fechaDesde')
const fechaHasta = document.getElementById('fechaHasta')

if (closeFilters != null) {
    closeFilters.addEventListener('click', () => {
        dialogFilters.close()
        fechaDesde.value = desde.value
        fechaHasta.value = hasta.value
    })
}

if (abrirFiltros != null) {
    abrirFiltros.addEventListener('click', () => {
        dialogFilters.showModal()
    })
}
