<?php
require_once 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $correo     = trim($_POST['correo']);
    $cedula     = trim($_POST['cedula']);
    $nueva_pass = $_POST['nueva_pass'];

    if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        die("Error: El formato del correo electrónico no es válido.");
    }

    try {
        // Verificar si el correo y la cédula coinciden con un registro real
        $stmt = $conexion->prepare("SELECT usuario_id FROM usuarios WHERE correo_usuario = :correo AND cedula_usuario = :cedula");
        $stmt->execute([
            ':correo' => $correo,
            ':cedula' => $cedula
        ]);

        if ($stmt->rowCount() === 1) {
            // El usuario es legítimo, procedemos a encriptar la nueva contraseña
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
            $id_usuario = $usuario['usuario_id'];
            
            $nueva_pass_hash = password_hash($nueva_pass, PASSWORD_BCRYPT);

            $sql_update = "UPDATE usuarios SET password = :pass WHERE usuario_id = :id";
            $stmt_update = $conexion->prepare($sql_update);
            $stmt_update->execute([
                ':pass' => $nueva_pass_hash,
                ':id'   => $id_usuario
            ]);

            echo "<h3>¡Contraseña restablecida con éxito!</h3>";
            echo "<p>Ya puedes iniciar sesión con tus nuevas credenciales.</p>";
            echo "<br><a href='login.html'>Ir al Login</a>";

        } else {
            die("Error: Los datos de identidad provistos no coinciden con nuestros registros.");
        }

    } catch (PDOException $e) {
        die("Error en el servidor al intentar cambiar la contraseña: " . $e->getMessage());
    }
} else {
    header("Location: login.html");
    exit();
}
?>