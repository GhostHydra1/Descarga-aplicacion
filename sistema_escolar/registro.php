<?php
session_start();
require_once "conexion.php";

$mensaje = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre = trim($_POST["nombre"] ?? "");
    $apellido = trim($_POST["apellido"] ?? "");
    $usuario = trim($_POST["usuario"] ?? "");
    $password = $_POST["password"] ?? "";
    $rol = $_POST["rol"] ?? "alumno";

    if ($nombre === "" || $apellido === "" || $usuario === "" || $password === "") {
        $error = "Todos los campos son obligatorios.";
    } elseif (!in_array($rol, ["admin", "profesor", "alumno"], true)) {
        $error = "Rol no válido.";
    } elseif (strlen($password) < 6) {
        $error = "La contraseña debe tener al menos 6 caracteres.";
    } else {
        $stmt = $conexion->prepare("SELECT id FROM usuarios WHERE usuario = ?");
        $stmt->execute([$usuario]);

        if ($stmt->fetch()) {
            $error = "Ese usuario ya existe.";
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $conexion->prepare(
                "INSERT INTO usuarios (nombre, apellido, usuario, password, rol)
                 VALUES (?, ?, ?, ?, ?)"
            );
            $stmt->execute([$nombre, $apellido, $usuario, $hash, $rol]);

            $usuarioId = $conexion->lastInsertId();

            if ($rol === "alumno") {
                $matricula = "ALU" . str_pad($usuarioId, 4, "0", STR_PAD_LEFT);
                $stmt = $conexion->prepare(
                    "INSERT INTO alumnos (usuario_id, matricula) VALUES (?, ?)"
                );
                $stmt->execute([$usuarioId, $matricula]);
            }

            $mensaje = "Registro realizado correctamente. Ya puedes iniciar sesión.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - Sistema Escolar</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body class="login-body">
<div class="login-card">
    <h1>Crear cuenta</h1>

    <?php if ($error): ?>
        <div class="alert error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <?php if ($mensaje): ?>
        <div class="alert success"><?= htmlspecialchars($mensaje) ?></div>
    <?php endif; ?>

    <form method="POST">
        <label>Nombre</label>
        <input type="text" name="nombre" required>

        <label>Apellido</label>
        <input type="text" name="apellido" required>

        <label>Usuario</label>
        <input type="text" name="usuario" required>

        <label>Contraseña</label>
        <input type="password" name="password" minlength="6" required>

        <label>Tipo de usuario</label>
        <select name="rol" required>
            <option value="alumno">Alumno</option>
            <option value="profesor">Profesor</option>
            <option value="admin">Administrador</option>
        </select>

        <button type="submit" class="btn">Registrarme</button>
    </form>

    <p class="center">
        <a href="login.php">Volver al inicio de sesión</a>
    </p>
</div>
</body>
</html>