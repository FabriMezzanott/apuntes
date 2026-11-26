<?php
include "conexion.php";

$mensaje = "";

if (isset($_POST["enviar"])) {
    $email = mysqli_real_escape_string($conexion, trim($_POST["email"]));
    $clave = $_POST["clave"];

    $res = mysqli_query($conexion, "SELECT * FROM usuarios WHERE email = '$email'");
    $usuario = mysqli_fetch_assoc($res);

    if ($usuario && password_verify($clave, $usuario["clave"])) {
        $_SESSION["id"] = $usuario["id"];
        $_SESSION["nombre"] = $usuario["nombre"];
        header("Location: index.php");
        exit;
    } else {
        $mensaje = "Email o contraseña incorrectos.";
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

<h1>Ingresar</h1>

<?php if ($mensaje != "") { ?>
  <p class="mensaje"><?php echo $mensaje; ?></p>
<?php } ?>

<form method="post" class="form" onsubmit="return validarFormulario(this)">
  <label>Email <input type="email" name="email" required></label>
  <label>Contraseña <input type="password" name="clave" required></label>
  <button type="submit" name="enviar">Ingresar</button>
</form>

<p>¿No tenes cuenta? <a href="registro.php">Registrate</a></p>

</main>

<script src="script.js"></script>
</body>
</html>
