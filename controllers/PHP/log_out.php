<?php
include("../../controllers/PHP/log_in.php");
$session = logIn::getInstance();
if ($_SERVER['REQUEST_METHOD'] === 'GET' && !empty($_GET)) {
    $accion = isset($_GET['accion']) ? $_GET['accion'] : "";
    if ($accion == "cerrar_sesion"){
        $session->logOut();
        header("Location:../../HTML/index.php");
        exit();
    }
    }
?>