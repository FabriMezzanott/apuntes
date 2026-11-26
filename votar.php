<?php
include "conexion.php";

if (!isset($_SESSION["id"])) {
    header("Location: login.php");
    exit;
}

$apunte_id = (int)$_POST["id"];
$yo = $_SESSION["id"];

// Veo si ya voto este apunte
$res = mysqli_query($conexion, "SELECT * FROM votos WHERE apunte_id = $apunte_id AND usuario_id = $yo");

if (mysqli_num_rows($res) == 0) {
    // 1) Guardo el voto
    mysqli_query($conexion, "INSERT INTO votos (apunte_id, usuario_id) VALUES ($apunte_id, $yo)");

    // 2) Le sumo 1 voto al apunte
    mysqli_query($conexion, "UPDATE apuntes SET votos = votos + 1 WHERE id = $apunte_id");

    // 3) Busco quien subio el apunte y le sumo 1 punto
    $res = mysqli_query($conexion, "SELECT * FROM apuntes WHERE id = $apunte_id");
    $apunte = mysqli_fetch_assoc($res);
    $autor_id = $apunte["usuario_id"];
    mysqli_query($conexion, "UPDATE usuarios SET votos_recibidos = votos_recibidos + 1, puntos = puntos + 1 WHERE id = $autor_id");
}

header("Location: index.php");
?>
