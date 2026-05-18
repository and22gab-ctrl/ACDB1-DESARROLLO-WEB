# Sistema de Autenticación y Gestión de Perfil en PHP

## 📝 Descripción breve del sistema
Este proyecto es un sistema de inicio de sesión (Login) y registro de usuarios web desarrollado desde cero con PHP y MySQL. El sistema implementa buenas prácticas de seguridad informática y control de acceso.

### Características principales:
* **Registro seguro:** Captura datos personales (Nombre, Apellido, Cédula, Teléfono y Correo) asignando automáticamente el rol de `usuario`.
* **Validación estricta:** Comprobación de formato de correo electrónico (`@` y dominio) y bloqueo de correos duplicados en la base de datos.
* **Seguridad de contraseñas:** Encriptación de claves mediante el algoritmo hash moderno `BCRYPT` (`password_hash`).
* **Control de sesiones:** Manejo seguro del estado del usuario con variables globales `$_SESSION`.
* **Gestión de Perfil:** Panel privado donde el usuario logueado puede actualizar sus datos personales o cambiar su contraseña validando su clave actual.
* **Recuperación de cuenta:** Mecanismo local para restablecer contraseñas mediante validación doble de identidad (Cédula y Correo).

---

## 🛠️ Requisitos del sistema
Para ejecutar este proyecto de forma local, necesitas tener instalado un entorno de desarrollo de servidores web. El sistema fue construido bajo los siguientes parámetros técnicos:

* **Servidor Web:** Apache (incluido en XAMPP).
* **Lenguaje de Programación:** PHP 8.0 o superior.
* **Gestor de Base de Datos:** MySQL / MariaDB.
* **Motor de Almacenamiento:** MyISAM (configurado explícitamente en las tablas).
* **Extensión de Conexión:** PHP Data Objects (PDO) para prevención de Inyecciones SQL.

---

## 🚀 Pasos para instalar y probar localmente

Sigue estos pasos detallados para desplegar el proyecto en tu computadora usando XAMPP:

### 1. Clonar o descargar el proyecto
Descarga los archivos del proyecto y colócalos dentro de la carpeta raíz de tu servidor local XAMPP:
`C:\xampp\htdocs\acdb1dw\`

### 2. Configurar la Base de Datos
1. Abre el **XAMPP Control Panel** e inicia los módulos de **Apache** y **MySQL**.
2. Abre tu navegador web e ingresa a `http://localhost/phpmyadmin/`.
3. Crea una nueva base de datos llamada exactamente `sistema_login`.
4. Selecciona la base de datos, ve a la pestaña **SQL**, pega el siguiente código y presiona **Continuar** para crear la estructura:

```sql
CREATE TABLE IF NOT EXISTS `usuarios`(
    `usuario_id` INT AUTO_INCREMENT PRIMARY KEY,
    `nom_usuario` VARCHAR(20) NOT NULL,
    `apellido_usuario` VARCHAR(20) NOT NULL,
    `cedula_usuario` VARCHAR(10) NOT NULL,
    `telefono_usuario` VARCHAR(10) NOT NULL,
    `correo_usuario` VARCHAR(50) UNIQUE NOT NULL,
    `password` VARCHAR (255) NOT NULL,
    `rol_usuario` VARCHAR(20),
    `fecha_registro` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET = utf8mb4;

-- Usuario Administrador de pruebas por defecto (Password: admin123)
INSERT INTO `usuarios` 
(`nom_usuario`, `apellido_usuario`,`cedula_usuario`, `telefono_usuario`, `correo_usuario`, `password`, `rol_usuario`)
VALUES
('Juan', 'Andrade', '1001234567', '0991234567', 'admin@yo.com', '$2y$10$7R9X18M6dZ8vV8Kx1bYwreLp8f0gK6hQ9e8rT7uI6oO5pP4q3rS2t', 'admin');
