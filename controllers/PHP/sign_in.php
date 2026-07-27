<?php
include('../../config/connection.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST)) {
    $email = isset($_POST['email']) ? $_POST['email'] : "";
    $contrasena = isset($_POST['_password']) ? $_POST['_password'] : "";

    $stmt = $conexion->prepare("INSERT INTO usuarios (email, _password) VALUES (?, ?)");
    $contrasena = password_hash($contrasena, PASSWORD_DEFAULT);
    $stmt->bind_param("ss", $email, $contrasena);
    if ($stmt->execute()) {
        echo "<script> alert('Te has registrado correctamente')</script>";
        echo json_encode(["RESULT" => "success_sign_in"]);
        exit();
    } else {
        echo http_response_code(405);
    }
}
?>