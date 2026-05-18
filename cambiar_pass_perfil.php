<?php
session_start();
require_once 'conexion.php';

// Validar sesión activa y método POST
if (!isset($_SESSION['usuario_id']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: login.html");
    exit();
}

$id_usuario      = $_SESSION['usuario_id'];
$pass_actual     = $_POST['pass_actual'];
$pass_nueva      = $_POST['pass_nueva'];
$pass_confirmar  = $_POST['pass_confirmar'];

// Verificar que las dos contraseñas nuevas coincidan exactamente
if ($pass_nueva !== $pass_confirmar) {
    die("Error: La nueva contraseña y la confirmación no coinciden.");
}

try {
    // CONSULTA: Traer la contraseña encriptada actual desde la base de datos
    $stmt = $conexion->prepare("SELECT password FROM usuarios WHERE usuario_id = :id");
    $stmt->execute([':id' => $id_usuario]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$usuario) {
        die("Error: Usuario no encontrado.");
    }
    // VALIDACIÓN DE IDENTIDAD: Verificar si la contraseña actual introducida es correcta
    if (!password_verify($pass_actual, $usuario['password'])) {
        die("Error: La contraseña actual que ingresaste es incorrecta.");
    }
    // ENCRIPTIACIÓN: Crear el hash de la nueva contraseña segura
    $nueva_pass_hash = password_hash($pass_nueva, PASSWORD_BCRYPT);
    // EJECUCIÓN: Modificar la contraseña en la tabla MyISAM
    $sql_update = "UPDATE usuarios SET password = :pass WHERE usuario_id = :id";
    $stmt_update = $conexion->prepare($sql_update);
    $stmt_update->execute([
        ':pass' => $nueva_pass_hash,
        ':id'   => $id_usuario
    ]);
header("Refresh: 3; url=inicio_usuario.php");
    
    // Mostramos un mensaje visualmente agradable mientras ocurre la espera
    echo "<div style='font-family: Arial, sans-serif; text-align: center; margin-top: 50px;'>";
    echo "  <h3 style='color: #28a745;'>✔️ ¡Contraseña cambiada con éxito!</h3>";
    echo "  <p style='color: #666;'>Tus credenciales de seguridad han sido actualizadas permanentemente.</p>";
    echo "  <p style='font-size: 14px; color: #999;'>Redirigiendo a tu perfil automáticamente en 3 segundos...</p>";
    echo "</div>";
    exit();
} catch (PDOException $e) {
    die("Error crítico en el servidor al actualizar la clave: " . $e->getMessage());
}
?>