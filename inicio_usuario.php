<?php
// 1. Inicializar el sistema de sesiones de PHP
session_start();

// 2. Control de Acceso: Si la sesión no existe, expulsar al login
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.html");
    exit();
}

// 3. Control de Acceso: Si es admin, mandarlo a su panel correspondiente
if ($_SESSION['rol'] !== 'usuario') {
    header("Location: panel_admin.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel de Usuario - Inicio</title>
    <style>
        /* Un diseño básico para que los botones y tarjetas se vean ordenados */
        body { font-family: Arial, sans-serif; margin: 40px; background-color: #f4f7f6; color: #333; }
        .card { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); max-width: 500px; }
        .btn { display: inline-block; background-color: #007BFF; color: white; padding: 10px 15px; text-decoration: none; border-radius: 5px; font-weight: bold; margin-top: 15px; }
        .btn:hover { background-color: #0056b3; }
        .btn-danger { background-color: #DC3545; }
        .btn-danger:hover { background-color: #bd2130; }
    </style>
</head>
<body>

    <div class="card">
        <h1>¡Bienvenido/a, <?php echo htmlspecialchars($_SESSION['nombre']); ?>!</h1>
        <p>Has ingresado correctamente al sistema.</p>
        
        <hr>

        <h3>Mis Datos de Sesión:</h3>
        <p><strong>Identificador Único (ID):</strong> <?php echo $_SESSION['usuario_id']; ?></p>
        <p><strong>Rol asignado:</strong> <span style="text-transform: uppercase; color: green; font-weight: bold;"><?php echo htmlspecialchars($_SESSION['rol']); ?></span></p>

        <hr>

        <p>¿Tus datos cambiaron? Puedes actualizarlos pulsando el siguiente botón:</p>
        <a href="editar_perfil.php" class="btn">⚙️ Editar Mi Perfil</a>
        
        <br><br>
        <a href="logout.php" class="btn btn-danger">Cerrar Sesión</a>
    </div>

</body>
</html>