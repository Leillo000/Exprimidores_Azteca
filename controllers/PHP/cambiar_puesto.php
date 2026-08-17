<?php
include("../../helpers/singleton_connection.php");

$db = Database::getDatabase();
if ($_SERVER['REQUEST_METHOD'] === "POST" && !empty($_POST)) {
    $user_id = isset($_POST["user_id"]) ? intval($_POST["user_id"]) : 0;
    $rol = isset($_POST["rol"]) ? intval($_POST["rol"]) : 0;

    if ($user_id <= 0 || ($rol != 1 && $rol != 2)) {
        echo json_encode(["RESULT" => "invalid_parameters"]);
        exit();
    }
    
    $db->doQuery("UPDATE usuarios SET rol = ? WHERE user_id = ?", [$rol, $user_id]);
    echo json_encode(["RESULT" => "success"]);
    exit();
}
?>