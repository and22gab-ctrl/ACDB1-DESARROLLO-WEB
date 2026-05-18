<?php
session_start();
require_once 'conexion.php';

if (!isset($_SESSION['usuario_id']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: login.html");
    exit();
}

$id_usuario = $_SESSION['usuario_id'];

$nom    = trim($_POST['nom']);
$ape    = trim($_POST['ape']);
$tel    = trim($_POST['tel']);
$correo = trim($_POST['correo']);

if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
    die("Error: El correo electrónico no tiene un formato válido (falta el @ o el dominio).");
}

try {
    $stmt_check = $conexion->prepare("SELECT usuario_id FROM usuarios WHERE correo_usuario = :correo AND usuario_id != :id");
    $stmt_check->execute([
        ':correo' => $correo, 
        ':id'     => $id_usuario
    ]);

    if ($stmt_check->rowCount() > 0) {
        die("Error: Este correo electrónico ya está registrado por otro usuario. Por favor, usa uno diferente.");
    }

    $sql = "UPDATE usuarios SET 
                nom_usuario = :nom, 
                apellido_usuario = :ape, 
                telefono_usuario = :tel, 
                correo_usuario = :correo 
            WHERE usuario_id = :id";

    $stmt = $conexion->prepare($sql);
    $stmt->execute([
        ':nom'    => $nom,
        ':ape'    => $ape,
        ':tel'    => $tel,
        ':correo' => $correo,
        ':id'     => $id_usuario
    ]);

    $_SESSION['nombre'] = $nom;

    echo "<h3>¡Perfil actualizado con éxito!</h3>";
    echo "<p>Los cambios se han guardado de forma permanente.</p>";
    echo "<br><a href='inicio_usuario.php'>Regresar al Panel de Inicio</a>";

} catch (PDOException $e) {
    die("Error crítico al actualizar los datos en la base de datos: " . $e->getMessage());
}
?>