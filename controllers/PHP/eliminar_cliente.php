<?php
include("../../config/connection.php");

// Valida la entrada de los datos de el servidor
if ($_SERVER['REQUEST_METHOD'] !== 'GET' || !isset($_GET['id_cliente'])) {
    header("Location: ../HTML/clientes.php?error=sin_id");
    exit();
}

// Validación del id
$id_cliente = isset($_GET['id_cliente']) ? intval($_GET['id_cliente']) : 0;

if ($id_cliente <= 0) {
    header("Location: ../HTML/clientes.php?error=id_invalido");
    exit();
}

// Comienza la transacción por si algo ocurre mal durante la actualización datos.
// No se borra los registros de esos usuarios, solo se cambia su estado a inactivo.

$conexion->begin_transaction();

try {
    // Preparar y ejecutar DELETE
    $stmt = $conexion->prepare("UPDATE empresas SET activo = 0 WHERE id_cliente = ?");
    
    if ($stmt === false) {
        throw new Exception("Error en prepare(): " . $conexion->error);
    }

    $stmt->bind_param("i", $id_cliente);
    
    if (!$stmt->execute()) {
        throw new Exception("Error en execute(): " . $stmt->error);
    }

    // Verificar si se eliminó algo
    if ($stmt->affected_rows === 0) {
        // throw es una forma de lanzar una excepción en caso de que algo salga mal DURANTE la ejecución del código,
        // porque cuando esto sucede automáticamente el código se va dentro del bloque de catch.
        throw new Exception("No se encontró la cliente con ID: $id_cliente");
    }
    
    // Esto confirma la transacción
    $conexion->commit();
    header("location: ../../HTML/clientes.php");
    exit();
} catch (Exception $e) {

    // Un rollback es básicamente deshacer los cambios que se iban a hacer si se ejecutaba la transacción
    $conexion->rollback();
    echo "Error al eliminar la cliente: " . $e->getMessage();
    echo "<br><button class='button' onclick=\"location.href='../../HTML/clientes.php'\"> VOLVER A LA LISTA </button>";
    exit();
}
?>