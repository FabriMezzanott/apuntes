<?php
include "conexion.php";

$mensaje = "";

if (isset($_POST["enviar"])) {
    $nombre   = mysqli_real_escape_string($conexion, trim($_POST["nombre"]));
    $apellido = mysqli_real_escape_string($conexion, trim($_POST["apellido"]));
    $curso    = mysqli_real_escape_string($conexion, trim($_POST["curso"]));
    $email    = mysqli_real_escape_string($conexion, trim($_POST["email"]));
    $clave    = $_POST["clave"];

    if ($nombre == "" || $apellido == "" || $curso == "" || $email == "" || $clave == "") {
        $mensaje = "Completa todos los campos.";
    } else {
        // Veo si ya hay alguien con ese email
        $res = mysqli_query($conexion, "SELECT * FROM usuarios WHERE email = '$email'");
        if (mysqli_num_rows($res) > 0) {
            $mensaje = "Ese email ya esta registrado.";
        } else {
            $clave_segura = password_hash($clave, PASSWORD_DEFAULT);
            mysqli_query($conexion, "INSERT INTO usuarios (nombre, apellido, curso, email, clave) VALUES ('$nombre', '$apellido', '$curso', '$email', '$clave_segura')");
            $mensaje = "Cuenta creada. Ya podes ingresar.";
        }
    }
}
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

<h1>Crear cuenta</h1>

<?php if ($mensaje != "") { ?>
  <p class="mensaje"><?php echo $mensaje; ?></p>
<?php } ?>

<form method="post" class="form" onsubmit="return validarFormulario(this)">
  <label>Nombre <input type="text" name="nombre" required></label>
  <label>Apellido <input type="text" name="apellido" required></label>
  <label>Curso <input type="text" name="curso" placeholder="Ej: 5° 2ª" required></label>
  <label>Email <input type="email" name="email" required></label>
  <label>Contraseña <input type="password" name="clave" required></label>
  <button type="submit" name="enviar">Crear cuenta</button>
</form>

<p>¿Ya tenes cuenta? <a href="login.php">Ingresa</a></p>

</main>

<script src="script.js"></script>
</body>
</html>
