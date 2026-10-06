<?php
session_start();
require_once "conexion.php";

if (!isset($_SESSION["usuario_id"]) || $_SESSION["rol"] !== "alumno") {
    header("Location: dashboard.php");
    exit;
}

$stmt = $conexion->prepare(
    "SELECT a.fecha, a.estado
     FROM asistencias a
     INNER JOIN alumnos al ON al.id = a.alumno_id
     WHERE al.usuario_id = ?
     ORDER BY a.fecha DESC"
);
$stmt->execute([$_SESSION["usuario_id"]]);
$asistencias = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis asistencias</title>
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
    <h1>Mis asistencias</h1>

    <table>
        <thead>
            <tr>
                <th>Fecha</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($asistencias as $fila): ?>
            <tr>
                <td><?= htmlspecialchars($fila["fecha"]) ?></td>
                <td><?= htmlspecialchars(ucfirst($fila["estado"])) ?></td>
            </tr>
        <?php endforeach; ?>

        <?php if (count($asistencias) === 0): ?>
            <tr>
                <td colspan="2">No hay registros de asistencia.</td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>
</main>
</body>
</html>