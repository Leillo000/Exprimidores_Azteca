<?php
include("../../controllers/PHP/log_in.php");
// Se obtiene una nueva instancia
$session = logIn::getInstance();
include('../../config/connection.php');
if ($_SERVER['REQUEST_METHOD'] === 'GET' && !empty($_GET)) {
    $email = isset($_GET['email']) ? $_GET['email'] : "";
    $contrasena = isset($_GET['_password']) ? $_GET['_password'] : "";
    $stmt = $conexion->prepare("SELECT * FROM usuarios WHERE email=?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $res = $stmt->get_result();

    $usuario_data = $res->fetch_assoc();

    if ($usuario_data != null) {
        if ($email == $usuario_data['email'] && password_verify($contrasena, $usuario_data["_password"])) {
            $session->setUser(["email" => $email]);
            // Codigo al iniciar sesion
        } else {
            // Codigo que muestra cuando la contraseña es incorrecta
        }
    } else {
        // Codigo al no encontrarse el usuario
    }

} else if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST)) {
    $email = isset($_POST['email']) ? $_POST['email'] : "";
    $contrasena = isset($_POST['_password']) ? $_POST['_password'] : "";
    $stmt = $conexion->prepare("INSERT INTO usuarios (email, _password) VALUES (?, ?)");
    $contrasena = password_hash($contrasena, PASSWORD_DEFAULT);
    $stmt->bind_param("ss", $email, $contrasena);
    if ($stmt->execute()) {
        // Codigo si se ejecuta correctamente
    }
}
?>