<?php
include("../../helpers/singleton_connection.php");
include("../../controllers/PHP/log_in.php");
// Se obtiene una nueva instancia
$session = logIn::getInstance();
include('../../config/connection.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST)) {
    $email = isset($_POST['email']) ? $_POST['email'] : "";
    $contrasena = isset($_POST['_password']) ? $_POST['_password'] : "";
    $stmt = $conexion->prepare("SELECT * FROM usuarios WHERE email=?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $res = $stmt->get_result();

    $usuario_data = $res->fetch_assoc();

    if ($usuario_data != null) {
        // Clausulas guardia
        if ($email != $usuario_data['email'] && !password_verify($contrasena, $usuario_data["_password"])) {
            echo json_encode(["RESULT" => "invalid_password"]);
            exit();
        }

        if ($usuario_data["access"] != 1){
            echo json_encode(["RESULT" => "access_denied"]);
            exit();
        }

        $idSesion = session_id();
        $db = Database::getDatabase();
        $db->doQuery("INSERT INTO sessions (session_id, user_id) VALUES (?, ?)", [$idSesion, $usuario_data["user_id"]]);
        $logIn = logIn::getInstance();
        $logIn->setUser(session_id());
        echo json_encode(["RESULT" => "success_login"]);
        exit();
    } else {
        echo json_encode(["RESULT" => "user_not_found"]);
        exit();
    }
}
?>