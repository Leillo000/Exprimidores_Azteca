<?php
include("../assets/HTML/layout.php");
if ($isAdmin[0]["rol"] != 1) {
    ?>

    <head>
        <title> Sin acceso </title>
    </head>
    <div class="container">
        <div class="center_items">
            <h2>No tienes permisos para entrar a esta página</h2>
            <br>
            <p><b>Consulta con el administrador o el personal de soporte para ayudarte.</b></p>
        </div>
    </div>
<?php } else {
    
    
    ?>

    <head>
        <title> Usuarios </title>
    </head>
    <div class="container">
        <div class="center_items">

        </div>
    </div>
<?php } ?>