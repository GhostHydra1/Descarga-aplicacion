<?php
session_start();

if (!isset($_SESSION["usuario_id"])) {
    header("Location: login.php");
    exit;
}

$rol = $_SESSION["rol"];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Sistema Escolar</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>
<nav class="navbar">
    <div><strong>Sistema Escolar</strong></div>
    <div>
        Hola, <?= htmlspecialchars($_SESSION["nombre"]) ?>
        <a href="logout.php" class="logout">Cerrar sesión</a>
    </div>
</nav>

<main class="container">
    <h1>Panel principal</h1>
    <p>Rol: <strong><?= htmlspecialchars($rol) ?></strong></p>

    <div class="cards">
        <?php if ($rol === "profesor" || $rol === "admin"): ?>
            <a class="card" href="asistencia.php">
                <h2>Capturar asistencia</h2>
                <p>Registrar asistencia de los alumnos.</p>
            </a>
        <?php endif; ?>

        <?php if ($rol === "admin"): ?>
            <a class="card" href="alumnos.php">
                <h2>Alumnos</h2>
                <p>Consultar los alumnos registrados.</p>
            </a>
        <?php endif; ?>

        <?php if ($rol === "alumno"): ?>
            <a class="card" href="mis_asistencias.php">
                <h2>Mis asistencias</h2>
                <p>Consultar tu historial de asistencia.</p>
            </a>
        <?php endif; ?>
    </div>
</main>
</body>
</html>