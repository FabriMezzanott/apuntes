<?php
include "conexion.php";

$mensaje = "";

if (isset($_POST["crear"])) {
    if (!isset($_SESSION["id"])) {
        header("Location: login.php");
        exit;
    }

    $materia = (int)$_POST["materia"];
    $tema    = mysqli_real_escape_string($conexion, trim($_POST["tema"]));
    $fecha   = mysqli_real_escape_string($conexion, $_POST["fecha"]);
    $hora    = mysqli_real_escape_string($conexion, $_POST["hora"]);
    $lugar   = mysqli_real_escape_string($conexion, trim($_POST["lugar"]));
    $cupo    = (int)$_POST["cupo"];
    $yo      = $_SESSION["id"];

    if ($materia == 0 || $tema == "" || $fecha == "" || $hora == "" || $lugar == "") {
        $mensaje = "Completa todos los campos.";
    } else if (strtotime($fecha . " " . $hora) <= time()) {
        $mensaje = "La fecha y hora tienen que ser futuras.";
    } else if ($cupo < 2) {
        $mensaje = "El cupo minimo es de 2 personas.";
    } else {

        $lugares = $cupo - 1;
        mysqli_query($conexion, "INSERT INTO grupos (creador_id, materia_id, tema, fecha, hora, lugar, cupo, lugares) VALUES ($yo, $materia, '$tema', '$fecha', '$hora', '$lugar', $cupo, $lugares)");

        $nuevo_id = mysqli_insert_id($conexion);
        mysqli_query($conexion, "INSERT INTO inscripciones (grupo_id, usuario_id) VALUES ($nuevo_id, $yo)");

        $mensaje = "Grupo creado. ¡Ya estas anotado!";
    }
}

if (isset($_POST["unirse"])) {
    if (!isset($_SESSION["id"])) {
        header("Location: login.php");
        exit;
    }

    $grupo_id = (int)$_POST["grupo_id"];
    $yo = $_SESSION["id"];

    $res = mysqli_query($conexion, "SELECT * FROM grupos WHERE id = $grupo_id");
    $grupo = mysqli_fetch_assoc($res);

    $res = mysqli_query($conexion, "SELECT * FROM inscripciones WHERE grupo_id = $grupo_id AND usuario_id = $yo");

    if (mysqli_num_rows($res) > 0) {
        $mensaje = "Ya estabas anotado en ese grupo.";
    } else if ($grupo["lugares"] <= 0) {
        $mensaje = "Ese grupo ya esta completo.";
    } else {
        mysqli_query($conexion, "INSERT INTO inscripciones (grupo_id, usuario_id) VALUES ($grupo_id, $yo)");
        mysqli_query($conexion, "UPDATE grupos SET lugares = lugares - 1 WHERE id = $grupo_id");
        $mensaje = "¡Te sumaste al grupo!";
    }
}

$hoy = date("Y-m-d");
$grupos = mysqli_query($conexion, "SELECT * FROM grupos WHERE fecha >= '$hoy' ORDER BY fecha, hora");
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

<h1>Grupos de estudio</h1>

<?php if ($mensaje != "") { ?>
  <p class="mensaje"><?php echo $mensaje; ?></p>
<?php } ?>

<?php if (isset($_SESSION["id"])) { ?>
  <form method="post" class="form" onsubmit="return validarGrupo(this)">
    <h2>Armar un grupo</h2>
    <label>Materia
      <select name="materia" required>
        <option value="">Elegi una...</option>
        <?php while ($m = mysqli_fetch_assoc($materias)) { ?>
          <option value="<?php echo $m["id"]; ?>"><?php echo $m["nombre"]; ?></option>
        <?php } ?>
      </select>
    </label>
    <label>Tema a repasar <input type="text" name="tema" required></label>
    <label>Fecha <input type="date" name="fecha" required></label>
    <label>Hora <input type="time" name="hora" required></label>
    <label>Lugar <input type="text" name="lugar" placeholder="Ej: Biblioteca" required></label>
    <label>Cupo maximo <input type="number" name="cupo" required></label>
    <button type="submit" name="crear">Crear grupo</button>
  </form>
<?php } else { ?>
  <p><a href="login.php">Ingresa</a> para armar o sumarte a un grupo.</p>
<?php } ?>

<div class="grid">
<?php while ($g = mysqli_fetch_assoc($grupos)) {

    $materia_id = $g["materia_id"];
    $res = mysqli_query($conexion, "SELECT * FROM materias WHERE id = $materia_id");
    $materia = mysqli_fetch_assoc($res);

    $ya_anotado = false;
    if (isset($_SESSION["id"])) {
        $yo = $_SESSION["id"];
        $grupo_id = $g["id"];
        $res = mysqli_query($conexion, "SELECT * FROM inscripciones WHERE grupo_id = $grupo_id AND usuario_id = $yo");
        if (mysqli_num_rows($res) > 0) {
            $ya_anotado = true;
        }
    }
?>
  <div class="card">
    <small><?php echo $materia["nombre"]; ?></small>
    <h3><?php echo htmlspecialchars($g["tema"]); ?></h3>
    <p><?php echo date("d/m/Y", strtotime($g["fecha"])); ?> a las <?php echo substr($g["hora"], 0, 5); ?></p>
    <p>Lugar: <?php echo htmlspecialchars($g["lugar"]); ?></p>
    <p>Lugares libres: <strong><?php echo $g["lugares"]; ?></strong> de <?php echo $g["cupo"]; ?></p>

    <?php if ($ya_anotado) { ?>
      <p>Ya estas anotado</p>
    <?php } else if ($g["lugares"] <= 0) { ?>
      <button type="button" disabled>Cupo completo</button>
    <?php } else if (isset($_SESSION["id"])) { ?>
      <form method="post">
        <input type="hidden" name="grupo_id" value="<?php echo $g["id"]; ?>">
        <button type="submit" name="unirse">Sumarme</button>
      </form>
    <?php } else { ?>
      <a href="login.php">Ingresa para sumarte</a>
    <?php } ?>
  </div>
<?php } ?>
</div>

</main>

<script src="script.js"></script>
</body>
</html>
