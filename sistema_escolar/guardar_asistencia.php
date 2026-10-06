<?php
session_start();
require_once "conexion.php";

if (!isset($_SESSION["usuario_id"]) || !in_array($_SESSION["rol"], ["admin", "profesor"], true)) {
    header("Location: dashboard.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: asistencia.php");
    exit;
}

$fecha = $_POST["fecha"] ?? "";
$estados = $_POST["estado"] ?? [];

if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $fecha)) {
    die("Fecha no válida.");
}

$permitidos = ["presente", "ausente", "retardo"];

$conexion->beginTransaction();

try {
    $stmt = $conexion->prepare(
        "INSERT INTO asistencias (alumno_id, fecha, estado)
         VALUES (?, ?, ?)
         ON DUPLICATE KEY UPDATE estado = VALUES(estado)"
    );

    foreach ($estados as $alumnoId => $estado) {
        if (in_array($estado, $permitidos, true)) {
            $stmt->execute([(int)$alumnoId, $fecha, $estado]);
        }
    }

    $conexion->commit();

    header("Location: asistencia.php?fecha=" . urlencode($fecha) . "&mensaje=" . urlencode("Asistencia guardada correctamente."));
    exit;
} catch (Exception $e) {
    $conexion->rollBack();
    die("Error al guardar la asistencia: " . $e->getMessage());
}
?>