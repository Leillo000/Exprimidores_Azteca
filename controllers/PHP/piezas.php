<?php
include('../../config/connection.php'); 

if($_SERVER['REQUEST_METHOD'] === 'GET' && !empty($_GET)){
    
// Se asegura que los parametros sean los correctos
$id_pieza = isset($_GET['id_pieza']) ? intval($_GET['id_pieza']) : 0;
// Se declara la sentencia
$stmt = $conexion->prepare('SELECT ps.id_pieza, po.nombre_producto, ps.nombre_pieza, ps.peso, po.id_producto 
FROM piezas AS ps 
JOIN productos AS po ON po.id_producto = ps.id_producto 
WHERE ps.id_pieza= ?');

// Se realiza la peticion
$stmt->bind_param('i', $id_pieza);

if($stmt->execute()){
    //Se obtiene como resultado
    $StmtResut = $stmt->get_result();
    //Se obtiene como array
    $Response = $StmtResut->fetch_assoc();
    // Se transforma en un json
    echo json_encode($Response);

} else {
    http_response_code(500);
    echo json_encode(['Status' => 'Error', 'Message' => 'Algo salió mal.']);
}

}

?>