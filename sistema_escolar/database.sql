CREATE DATABASE IF NOT EXISTS sistema_escolar
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE sistema_escolar;

CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    apellido VARCHAR(100) NOT NULL,
    usuario VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    rol ENUM('admin','profesor','alumno') NOT NULL DEFAULT 'alumno',
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE alumnos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL UNIQUE,
    matricula VARCHAR(30) NOT NULL UNIQUE,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
);

CREATE TABLE asistencias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    alumno_id INT NOT NULL,
    fecha DATE NOT NULL,
    estado ENUM('presente','ausente','retardo') NOT NULL DEFAULT 'presente',
    FOREIGN KEY (alumno_id) REFERENCES alumnos(id) ON DELETE CASCADE,
    UNIQUE KEY asistencia_unica (alumno_id, fecha)
);

-- Usuario administrador inicial.
-- Contraseña: Admin123
INSERT INTO usuarios (nombre, apellido, usuario, password, rol)
VALUES (
    'Administrador',
    'Sistema',
    'admin',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC8h2sH5Q8lYQj5wqW8K',
    'admin'
);
