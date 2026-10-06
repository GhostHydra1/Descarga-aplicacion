SISTEMA ESCOLAR - XAMPP

1. Copia la carpeta "sistema_escolar" dentro de:
   C:\xampp\htdocs\

2. Abre XAMPP y enciende:
   - Apache
   - MySQL

3. Entra a:
   http://localhost/phpmyadmin

4. Ve a "Importar" y selecciona:
   database.sql

5. Después abre:
   http://localhost/sistema_escolar/

USUARIO ADMINISTRADOR:
Usuario: admin
Contraseña: Admin123

FUNCIONES:
- Inicio de sesión.
- Registro de usuarios.
- Roles: administrador, profesor y alumno.
- Registro automático de matrícula para alumnos.
- Captura de asistencia.
- Estados: presente, ausente y retardo.
- Consulta de asistencias del alumno.
- Consulta de alumnos para administrador.

NOTA:
La contraseña se almacena usando password_hash() de PHP.
Antes de usar este sistema en producción deben agregarse controles de seguridad,
permisos más estrictos, protección CSRF, recuperación de contraseña y otras
medidas de seguridad.
