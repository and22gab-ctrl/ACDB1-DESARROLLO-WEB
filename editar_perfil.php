<?php
// 1. Iniciar sesión para saber quién es el usuario accesando
session_start();
require_once 'conexion.php';

// 2. SEGURIDAD: Si no existe la sesión, redirigir inmediatamente al login
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.html");
    exit();
}

// Guardamos el ID de la sesión en una variable más corta
$id_usuario = $_SESSION['usuario_id'];

try {
    // 3. CONSULTA: Traer los datos actualizados de este usuario desde la BD
    $stmt = $conexion->prepare("SELECT nom_usuario, apellido_usuario, cedula_usuario, telefono_usuario, correo_usuario FROM usuarios WHERE usuario_id = :id");
    $stmt->execute([':id' => $id_usuario]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    // Si por alguna razón extraña el usuario ya no existe en la BD, destruir sesión
    if (!$usuario) {
        header("Location: logout.php");
        exit();
    }

} catch (PDOException $e) {
    die("Error al cargar tus datos: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Modificar Perfil</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; background-color: #f4f7f6; color: #333; }
        .form-container { background: white; padding: 30px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); max-width: 400px; margin: 0 auto; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; font-weight: bold; margin-bottom: 5px; }
        .form-group input { width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        .form-group input:disabled { background-color: #e9ecef; color: #6c757d; cursor: not-allowed; }
        .btn-submit { background-color: #28a745; color: white; padding: 10px 15px; border: none; border-radius: 5px; font-weight: bold; cursor: pointer; width: 100%; }
        .btn-submit:hover { background-color: #218838; }
        .back-link { display: block; text-align: center; margin-top: 15px; color: #007BFF; text-decoration: none; }
        .back-link:hover { text-decoration: underline; }
    </style>
</head>
<body>

    <div class="form-container">
        <h2>Actualizar mis Datos</h2>
        <p style="font-size: 14px; color: #666;">Modifica los campos que necesites cambiar.</p>
        <hr>

        <form action="actualizar_perfil.php" method="POST">
            
            <div class="form-group">
                <label>Nombre:</label>
                <input type="text" name="nom" value="<?php echo htmlspecialchars($usuario['nom_usuario']); ?>" required maxlength="20">
            </div>

            <div class="form-group">
                <label>Apellido:</label>
                <input type="text" name="ape" value="<?php echo htmlspecialchars($usuario['apellido_usuario']); ?>" required maxlength="20">
            </div>

            <div class="form-group">
                <label>Cédula (No modificable):</label>
                <input type="text" value="<?php echo htmlspecialchars($usuario['cedula_usuario']); ?>" disabled>
            </div>

            <div class="form-group">
                <label>Teléfono:</label>
                <input type="text" name="tel" value="<?php echo htmlspecialchars($usuario['telefono_usuario']); ?>" required maxlength="10">
            </div>

            <div class="form-group">
                <label>Correo Electrónico:</label>
                <input type="email" name="correo" value="<?php echo htmlspecialchars($usuario['correo_usuario']); ?>" required maxlength="50">
            </div>

            <button type="submit" class="btn-submit">💾 Guardar Cambios</button>
        </form>

        <a href="inicio_usuario.php" class="back-link">Volver al Panel de Inicio</a>
    </div>
<hr style="margin: 25px 0;">
        <h3>Cambiar Contraseña</h3>
        
        <form action="cambiar_pass_perfil.php" method="POST">
            <div class="form-group">
                <label>Contraseña Actual:</label>
                <input type="password" name="pass_actual" required>
            </div>

            <div class="form-group">
                <label>Nueva Contraseña:</label>
                <input type="password" name="pass_nueva" required minlength="6">
            </div>

            <div class="form-group">
                <label>Confirmar Nueva Contraseña:</label>
                <input type="password" name="pass_confirmar" required minlength="6">
            </div>

            <button type="submit" class="btn-submit" style="background-color: #ff0000;">🔒 Actualizar Contraseña</button>
        </form>
</body>
</html>