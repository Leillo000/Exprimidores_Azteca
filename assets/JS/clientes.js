function redirigir(accion, id_cliente ) {
    // Se obtiene el no_pedido como valor númerico.
    cliente = parseInt(id_cliente);
    if (accion === 'eliminar') {
        window.location.href = '../controllers/PHP/eliminar_cliente.php?id_cliente=' + encodeURIComponent(id_cliente);
    } else if (accion === 'editar') {   
        // Esta URL ya se protege mediante encodeURIComponent, ya que cuida que no se hagan consultas maliciosas.
        window.location.href = '../HTML/editar_cliente.php?id_cliente=' + encodeURIComponent(id_cliente);
    } 
}