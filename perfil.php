<?php
include "conexion.php";

if (!isset($_SESSION["id"])) {
    header("Location: login.php");
    exit;
}

$yo = $_SESSION["id"];

$mis_apuntes = mysqli_query($conexion, "SELECT * FROM apuntes WHERE usuario_id = $yo");

$mis_inscripciones = mysqli_query($conexion, "SELECT * FROM inscripciones WHERE usuario_id = $yo");
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Apuntes de la escuela</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>

<header>
  <a class="logo" href="index.php">ApunTec5</a>
  <nav>
    <a href="index.php">Apuntes</a>
    <a href="grupos.php">Grupos</a>
    <a href="ranking.php">Ranking</a>
    <?php if (isset($_SESSION["id"])) { ?>
      <a href="subir.php">Subir apunte</a>
      <a href="perfil.php">Mi perfil</a>
      <a href="logout.php">Salir</a>
    <?php } else { ?>
      <a href="login.php">Ingresar</a>
      <a href="registro.php">Registrarme</a>
    <?php } ?>
  </nav>
</header>

<main>

<h1>Hola, <?php echo htmlspecialchars($_SESSION["nombre"]); ?></h1>

<h2>Mis apuntes</h2>
<ul>
<?php while ($a = mysqli_fetch_assoc($mis_apuntes)) { ?>
  <li><?php echo htmlspecialchars($a["titulo"]); ?> - <?php echo $a["votos"]; ?> votos</li>
<?php } ?>
</ul>
<?php if (mysqli_num_rows($mis_apuntes) == 0) { ?>
  <p>Todavia no subiste apuntes.</p>
<?php } ?>

<h2>Mis grupos de estudio</h2>
<ul>
<?php while ($i = mysqli_fetch_assoc($mis_inscripciones)) {

    // Busco los datos del grupo en el que estoy anotado
    $grupo_id = $i["grupo_id"];
    $res = mysqli_query($conexion, "SELECT * FROM grupos WHERE id = $grupo_id");
    $g = mysqli_fetch_assoc($res);
?>
  <li>
    <?php echo htmlspecialchars($g["tema"]); ?> -
    <?php echo date("d/m/Y", strtotime($g["fecha"])); ?>
    <?php echo substr($g["hora"], 0, 5); ?> en
    <?php echo htmlspecialchars($g["lugar"]); ?>
  </li>
<?php } ?>
</ul>
<?php if (mysqli_num_rows($mis_inscripciones) == 0) { ?>
  <p>No estas anotado en ningun grupo.</p>
<?php } ?>

</main>

<script src="script.js"></script>
</body>
</html>
