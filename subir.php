<?php
include "conexion.php";

// Si no inicio sesion, lo mando a login
if (!isset($_SESSION["id"])) {
    header("Location: login.php");
    exit;
}

$mensaje = "";

if (isset($_POST["enviar"])) {
    $titulo      = mysqli_real_escape_string($conexion, trim($_POST["titulo"]));
    $descripcion = mysqli_real_escape_string($conexion, trim($_POST["descripcion"]));
    $materia     = (int)$_POST["materia"];
    $anio        = (int)$_POST["anio"];
    $enlace      = mysqli_real_escape_string($conexion, trim($_POST["enlace"]));
    $usuario_id  = $_SESSION["id"];
    $nombre_archivo = "";

    if ($titulo == "" || $descripcion == "" || $materia == 0 || $anio == 0) {
        $mensaje = "Completa titulo, descripcion, materia y año.";
    } else if ($_FILES["archivo"]["name"] == "" && $enlace == "") {
        $mensaje = "Tenes que adjuntar un archivo o poner un enlace.";
    } else {
        // Si eligio un archivo, lo guardo en la carpeta uploads
        if ($_FILES["archivo"]["name"] != "") {
            $extension = strtolower(pathinfo($_FILES["archivo"]["name"], PATHINFO_EXTENSION));
            if ($extension == "pdf" || $extension == "jpg" || $extension == "jpeg" || $extension == "png") {
                $nombre_archivo = time() . "." . $extension;
                move_uploaded_file($_FILES["archivo"]["tmp_name"], "uploads/" . $nombre_archivo);
            } else {
                $mensaje = "Solo se aceptan archivos PDF, JPG o PNG.";
            }
        }

        if ($mensaje == "") {
            mysqli_query($conexion, "INSERT INTO apuntes (usuario_id, materia_id, titulo, descripcion, anio, archivo, enlace) VALUES ($usuario_id, $materia, '$titulo', '$descripcion', $anio, '$nombre_archivo', '$enlace')");

            // Le sumo 1 apunte y 2 puntos al usuario (para el ranking)
            mysqli_query($conexion, "UPDATE usuarios SET apuntes_subidos = apuntes_subidos + 1, puntos = puntos + 2 WHERE id = $usuario_id");

            header("Location: index.php");
            exit;
        }
    }
}

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

<h1>Subir apunte</h1>

<?php if ($mensaje != "") { ?>
  <p class="mensaje"><?php echo $mensaje; ?></p>
<?php } ?>

<form method="post" enctype="multipart/form-data" class="form" onsubmit="return validarApunte(this)">
  <label>Titulo <input type="text" name="titulo" required></label>
  <label>Descripcion breve <input type="text" name="descripcion" maxlength="300" required></label>
  <label>Materia
    <select name="materia" required>
      <option value="">Elegi una...</option>
      <?php while ($m = mysqli_fetch_assoc($materias)) { ?>
        <option value="<?php echo $m["id"]; ?>"><?php echo $m["nombre"]; ?></option>
      <?php } ?>
    </select>
  </label>
  <label>Año
    <select name="anio" required>
      <option value="">Elegi...</option>
      <option value="1">1° año</option>
      <option value="2">2° año</option>
      <option value="3">3° año</option>
      <option value="4">4° año</option>
      <option value="5">5° año</option>
      <option value="6">6° año</option>
      <option value="7">7° año</option>
    </select>
  </label>
  <label>Archivo (PDF o imagen) <input type="file" name="archivo"></label>
  <label>O enlace de descarga <input type="text" name="enlace" placeholder="https://"></label>
  <button type="submit" name="enviar">Publicar</button>
</form>

</main>

<script src="script.js"></script>
</body>
</html>
