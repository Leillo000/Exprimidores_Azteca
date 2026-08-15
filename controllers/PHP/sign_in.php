<?php
include('../../config/connection.php');
include("../../helpers/singleton_connection.php");
$db = Database :: getDatabase();
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST)) {
    $email = isset($_POST['email']) ? $_POST['email'] : "";
    $contrasena = isset($_POST['_password']) ? $_POST['_password'] : "";

    $dominios = [
        "@gmail.com",
        "@hotmail.com",
        "@outlook.com",
        "@yahoo.com",
        "@icloud.com",
        "@protonmail.com",
        "@live.com",
        "@aol.com",
        "@zoho.com",
        "@gmx.com"
    ];


    $dominioCorrecto = false;

    foreach ($dominios as $dominio) {
        if (str_ends_with($email, $dominio)) {
            $dominioCorrecto = true;
        }
        if ($dominioCorrecto) {
            break;
        }
    }

    if (!$dominioCorrecto) {
        echo json_encode(["RESULT" => "invalid_domain"]);
        exit();
    }

    if (mb_strlen($contrasena) <= 10) {
        echo json_encode(["RESULT" => "weak_password"]);
        exit();
    }

    $verificarEmail = $db ->doQuery("SELECT email FROM usuarios WHERE email = ?", [$email]);

    if ($verificarEmail[0]["email"]){
        echo json_encode(["RESULT" => "email_in_use"]);
        exit();
    }

    $stmt = $conexion->prepare("INSERT INTO usuarios (email, _password) VALUES (?, ?)");
    $contrasena = password_hash($contrasena, PASSWORD_DEFAULT);
    $stmt->bind_param("ss", $email, $contrasena);
    if ($stmt->execute()) {
        echo json_encode(["RESULT" => "success_sign_in"]);
        exit();
    } else {
        echo http_response_code(405);
    }
}
?>