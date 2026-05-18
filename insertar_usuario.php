<?php
require_once 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $nom    = trim($_POST['nom']);
    $ape    = trim($_POST['ape']);
    $ced    = trim($_POST['ced']);
    $tel    = trim($_POST['tel']);    
    $correo = trim($_POST['correo']); 
    $pass   = $_POST['pass'];
   
    // VALIDACIÓN Formato de correo
    if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        die("Error: El correo electrónico no tiene un formato válido.");
    }

    // VALIDACIÓN: Verificar si el correo ya existe
    $stmt_check = $conexion->prepare("SELECT usuario_id FROM usuarios WHERE correo_usuario = :correo");
    $stmt_check->execute([':correo' => $correo]);

    if ($stmt_check->rowCount() > 0) {
        die("Error: Este correo ya está registrado. Intenta con otro.");
    }
    // Encriptar contraseña y asignar rol por defecto
    $pass_hash = password_hash($pass, PASSWORD_BCRYPT);
    $rol_defecto = 'usuario';

    try {
        $sql = "INSERT INTO usuarios (nom_usuario, apellido_usuario, cedula_usuario, correo_usuario, telefono_usuario, rol_usuario, password) 
                VALUES (:nom, :ape, :ced, :correo, :tel, :rol, :pass)";
        
        $stmt = $conexion->prepare($sql);
        $stmt->execute([
            ':nom'    => $nom,
            ':ape'    => $ape,
            ':ced'    => $ced,
            ':correo' => $correo,
            ':tel'    => $tel,
            ':rol'    => $rol_defecto,
            ':pass'   => $pass_hash
        ]);

        echo "Registro exitoso. Ahora puedes iniciar sesión como usuario normal.";
        echo "<br><a href='login.html'>Ir al Login</a>";

    } catch (PDOException $e) {
        die("Error al registrar: " . $e->getMessage());
    }
}
?>