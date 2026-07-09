<?php

function controlPaginas($conexion, $query, $queryCount, $types,$pagina){
    $porPagina = 10;
    $offset = ($pagina - 1) * $porPagina; // Verifica numero de página para mostrar los registros, página 1 del 1 al 25, página 2 del 26 al 50 y así sucesivamente
    $resultado = $conexion->query($queryCount);
    $resultadoQuery = $resultado->fetch_assoc();
    $total = intval($resultadoQuery["total"]);
    $totalPaginas = max(1, ceil($total / $porPagina)); // calcula en cuántas páginas mostrar los registros
    $stmt = $conexion -> prepare($query);

    $stmt->bind_param($types, $porPagina, $offset);
    $stmt->execute();
    $res = $stmt->get_result();

    return [
             "datos" => $res->fetch_all(MYSQLI_ASSOC),
             "total" => $total, 
             "totalPaginas" => $totalPaginas, 
             "paginaActual" => $pagina
              ];
}
?>