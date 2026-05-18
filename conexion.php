<?php
// Configuración de la base de datos
$host     = "localhost";
$db_name  = "sistema_login";
$username = "root";          
$password = "";              

try {
    // Creamos la conexión 
    $conexion = new PDO("mysql:host=$host;dbname=$db_name;charset=utf8", $username, $password);
    
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
} catch (PDOException $e) {
    die("Error crítico de conexión: " . $e->getMessage());
}
?>