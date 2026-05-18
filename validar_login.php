<?php
// Es obligatorio iniciar sesión para usar $_SESSION
session_start();
require_once 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $correo = trim($_POST['correo']);
    $pass   = $_POST['pass'];

    try {
        
        $stmt = $conexion->prepare("SELECT * FROM usuarios WHERE correo_usuario = :correo");
        $stmt->execute([':correo' => $correo]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($usuario && password_verify($pass, $usuario['password'])) {
            
            // Variables de sesión
            $_SESSION['usuario_id'] = $usuario['usuario_id'];
            $_SESSION['nombre']     = $usuario['nom_usuario'];
            $_SESSION['rol']        = $usuario['rol_usuario'];

            if ($_SESSION['rol'] === 'admin') {
                header("Location: panel_admin.php");
            } else {
                header("Location: inicio_usuario.php");
            }
            exit();

        } else {
            echo "Error: Correo o contraseña incorrectos.";
            echo "<br><a href='login.html'>Intentar de nuevo</a>";
        }

    } catch (PDOException $e) {
        die("Error en el servidor: " . $e->getMessage());
    }
}
?>