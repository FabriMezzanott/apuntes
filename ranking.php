<?php
include "conexion.php";

// Los puntos ya estan guardados en cada usuario: solo pido los 5 con mas puntos
$ranking = mysqli_query($conexion, "SELECT * FROM usuarios WHERE puntos > 0 ORDER BY puntos DESC LIMIT 5");
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

<h1>Top 5 de colaboradores</h1>
<p>Cada apunte subido suma 2 puntos y cada voto recibido suma 1.</p>

<ol>
<?php while ($r = mysqli_fetch_assoc($ranking)) { ?>
  <li>
    <strong><?php echo htmlspecialchars($r["nombre"] . " " . $r["apellido"]); ?></strong>
    (<?php echo htmlspecialchars($r["curso"]); ?>) -
    <?php echo $r["apuntes_subidos"]; ?> apuntes,
    <?php echo $r["votos_recibidos"]; ?> votos,
    <strong><?php echo $r["puntos"]; ?> puntos</strong>
  </li>
<?php } ?>
</ol>

<?php if (mysqli_num_rows($ranking) == 0) { ?>
  <p>Todavia nadie subio apuntes.</p>
<?php } ?>

</main>

<script src="script.js"></script>
</body>
</html>
