<?php
include "conexion.php";

$apuntes = mysqli_query($conexion, "SELECT * FROM apuntes ORDER BY id DESC");
$materias = mysqli_query($conexion, "SELECT * FROM materias");
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

<h1>Apuntes</h1>

<div class="filtros">
  <input type="text" id="buscador" placeholder="Buscar por titulo...">
  <select id="filtro_materia">
    <option value="0">Todas las materias</option>
    <?php while ($m = mysqli_fetch_assoc($materias)) { ?>
      <option value="<?php echo $m["id"]; ?>"><?php echo $m["nombre"]; ?></option>
    <?php } ?>
  </select>
</div>

<p id="sin_resultados" style="display:none;">No hay apuntes con ese filtro.</p>

<div class="grid">
<?php while ($a = mysqli_fetch_assoc($apuntes)) {

    // Busco el nombre de la materia de este apunte
    $materia_id = $a["materia_id"];
    $res = mysqli_query($conexion, "SELECT * FROM materias WHERE id = $materia_id");
    $materia = mysqli_fetch_assoc($res);

    // Busco el nombre de quien lo subio
    $autor_id = $a["usuario_id"];
    $res = mysqli_query($conexion, "SELECT * FROM usuarios WHERE id = $autor_id");
    $autor = mysqli_fetch_assoc($res);

    // Veo si el usuario que esta mirando ya voto este apunte
    $ya_voto = false;
    if (isset($_SESSION["id"])) {
        $yo = $_SESSION["id"];
        $apunte_id = $a["id"];
        $res = mysqli_query($conexion, "SELECT * FROM votos WHERE apunte_id = $apunte_id AND usuario_id = $yo");
        if (mysqli_num_rows($res) > 0) {
            $ya_voto = true;
        }
    }
?>
  <div class="card tarjeta" data-titulo="<?php echo htmlspecialchars($a["titulo"]); ?>" data-materia="<?php echo $a["materia_id"]; ?>">
    <small><?php echo $materia["nombre"]; ?> - <?php echo $a["anio"]; ?>° año</small>
    <h3><?php echo htmlspecialchars($a["titulo"]); ?></h3>
    <p><?php echo htmlspecialchars($a["descripcion"]); ?></p>
    <small>Subido por <?php echo htmlspecialchars($autor["nombre"]); ?></small>

    <?php if ($a["archivo"] != "") { ?>
      <a href="uploads/<?php echo $a["archivo"]; ?>" target="_blank">Ver archivo</a>
    <?php } ?>
    <?php if ($a["enlace"] != "") { ?>
      <a href="<?php echo htmlspecialchars($a["enlace"]); ?>" target="_blank">Ir al enlace</a>
    <?php } ?>

    <form method="post" action="votar.php">
      <input type="hidden" name="id" value="<?php echo $a["id"]; ?>">
      <?php if ($ya_voto) { ?>
        <button type="button" disabled>Ya votaste (<?php echo $a["votos"]; ?>)</button>
      <?php } else { ?>
        <button type="submit">Me sirvio (<?php echo $a["votos"]; ?>)</button>
      <?php } ?>
    </form>
  </div>
<?php } ?>
</div>

</main>

<script src="script.js"></script>
</body>
</html>
