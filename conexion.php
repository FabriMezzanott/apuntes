<?php
session_start();
date_default_timezone_set("America/Argentina/Buenos_Aires");

$conexion = mysqli_connect("localhost", "root", "", "apuntes");
mysqli_set_charset($conexion, "utf8mb4");
?>
