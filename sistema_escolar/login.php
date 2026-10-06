<?php
session_start();
require_once "conexion.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $usuario = trim($_POST["usuario"] ?? "");
    $password = $_POST["password"] ?? "";

    if ($usuario === "" || $password === "") {
        $error = "Completa todos los campos.";
    } else {
        $stmt = $conexion->prepare("SELECT * FROM usuarios WHERE usuario = ? LIMIT 1");
        $stmt->execute([$usuario]);
        $datos = $stmt->fetch();

        if ($datos && password_verify($password, $datos["password"])) {
            $_SESSION["usuario_id"] = $datos["id"];
            $_SESSION["nombre"] = $datos["nombre"];
            $_SESSION["rol"] = $datos["rol"];

            header("Location: dashboard.php");
            exit;
        } else {
            $error = "Usuario o contraseña incorrectos.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar sesión - Sistema Escolar</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body class="login-body">
<div class="login-card">
    <h1>Sistema Escolar</h1>
    <p class="subtitle">Inicio de sesión</p>

    <?php if ($error): ?>
        <div class="alert error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST">
        <label>Usuario</label>
        <input type="text" name="usuario" required>

        <label>Contraseña</label>
        <input type="password" name="password" required>

        <button type="submit" class="btn">Iniciar sesión</button>
    </form>

    <p class="center">
        ¿No tienes una cuenta?
        <a href="registro.php">Registrarte</a>
    </p>
</div>
</body>
</html>