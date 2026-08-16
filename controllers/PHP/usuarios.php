<?php
include("../../helpers/singleton_connection.php");

$db = Database :: getDatabase();
if ($_SERVER['REQUEST_METHOD'] === "POST" && !empty($_POST)) {
$user_id = isset($_POST["user_id"]) ? intval($_POST["user_id"]) : 0;
$access = isset($_POST["access"]) ? intval($_POST["access"]) : 0;

if($user_id <= 0 || ($access != 1 && $access != 0)){
    echo json_encode(["RESULT" => "invalid_parameters"]);
    exit();
}
$access = ($access == 1) ? 0 : 1;
$db->doQuery("UPDATE usuarios SET access = ? WHERE user_id = ?", [$access, $user_id]);
echo json_encode(["RESULT" => "success"]);
exit();
}
?>