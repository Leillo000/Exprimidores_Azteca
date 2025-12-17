<?php
$server = "localhost"; 
$user = "root"; 
$pwd = ""; 
$bd = "exprimidores_azteca"; 

$conexion = new mysqli($server, $user, $pwd, $bd);

if ($conexion->connect_error) {
   die("Error en la conexión: " . $conexion->connect_error);
}
?>