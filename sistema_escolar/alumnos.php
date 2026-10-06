<?php
session_start();
require_once "conexion.php";

if (!isset($_SESSION["usuario_id"]) || $_SESSION["rol"] !== "admin") {
    header("Location: dashboard.php");
    exit;
}

$stmt = $conexion->query(
    "SELECT a.matricula, u.nombre, u.apellido, u.usuario
     FROM alumnos a
     INNER JOIN usuarios u ON u.id = a.usuario_id
     ORDER BY u.apellido, u.nombre"
);
$alumnos = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alumnos</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>
<nav class="navbar">
    <strong>Sistema Escolar</strong>
    <div>
        <a href="dashboard.php">Inicio</a>
        <a href="logout.php" class="logout">Cerrar sesión</a>
    </div>
</nav>

<main class="container">
    <h1>Alumnos registrados</h1>

    <table>
        <thead>
            <tr>
                <th>Matrícula</th>
                <th>Nombre</th>
                <th>Usuario</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($alumnos as $alumno): ?>
            <tr>
                <td><?= htmlspecialchars($alumno["matricula"]) ?></td>
                <td><?= htmlspecialchars($alumno["apellido"] . " " . $alumno["nombre"]) ?></td>
                <td><?= htmlspecialchars($alumno["usuario"]) ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</main>
</body>
</html>