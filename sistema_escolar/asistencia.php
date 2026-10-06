<?php
session_start();
require_once "conexion.php";

if (!isset($_SESSION["usuario_id"]) || !in_array($_SESSION["rol"], ["admin", "profesor"], true)) {
    header("Location: dashboard.php");
    exit;
}

$fecha = $_GET["fecha"] ?? date("Y-m-d");
$mensaje = $_GET["mensaje"] ?? "";

$stmt = $conexion->query(
    "SELECT a.id AS alumno_id, a.matricula, u.nombre, u.apellido
     FROM alumnos a
     INNER JOIN usuarios u ON u.id = a.usuario_id
     ORDER BY u.apellido, u.nombre"
);
$alumnos = $stmt->fetchAll();

$stmt = $conexion->prepare(
    "SELECT alumno_id, estado FROM asistencias WHERE fecha = ?"
);
$stmt->execute([$fecha]);

$asistencias = [];
foreach ($stmt->fetchAll() as $fila) {
    $asistencias[$fila["alumno_id"]] = $fila["estado"];
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Capturar asistencia</title>
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
    <h1>Captura de asistencia</h1>

    <?php if ($mensaje): ?>
        <div class="alert success"><?= htmlspecialchars($mensaje) ?></div>
    <?php endif; ?>

    <form method="POST" action="guardar_asistencia.php">
        <label>Fecha</label>
        <input type="date" name="fecha" value="<?= htmlspecialchars($fecha) ?>" required>

        <table>
            <thead>
                <tr>
                    <th>Matrícula</th>
                    <th>Alumno</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($alumnos as $alumno): ?>
                <?php $estado = $asistencias[$alumno["alumno_id"]] ?? "presente"; ?>
                <tr>
                    <td><?= htmlspecialchars($alumno["matricula"]) ?></td>
                    <td><?= htmlspecialchars($alumno["apellido"] . " " . $alumno["nombre"]) ?></td>
                    <td>
                        <select name="estado[<?= $alumno["alumno_id"] ?>]">
                            <option value="presente" <?= $estado === "presente" ? "selected" : "" ?>>Presente</option>
                            <option value="ausente" <?= $estado === "ausente" ? "selected" : "" ?>>Ausente</option>
                            <option value="retardo" <?= $estado === "retardo" ? "selected" : "" ?>>Retardo</option>
                        </select>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>

        <?php if (count($alumnos) > 0): ?>
            <button type="submit" class="btn">Guardar asistencia</button>
        <?php else: ?>
            <div class="alert error">No hay alumnos registrados.</div>
        <?php endif; ?>
    </form>
</main>
</body>
</html>