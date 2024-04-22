<?php
header('Content-Type: text/html; charset=utf-8');
include 'database.php'; // Incluir el archivo de conexión a la base de datos
function insertarUsuario($nombre, $contrasena) {
    global $conn;
    
    $stmt = $conn->prepare("INSERT INTO Usuario (Nombre, Contrasena) VALUES (?, ?)");
    $stmt->bindParam(1,$nombre, PDO::PARAM_STR);
    $stmt->bindParam(2, $contrasena, PDO::PARAM_STR);
    
    if ($stmt->execute()) {
        return true; // Usuario insertado correctamente
    } else {
        return false; // Error al insertar usuario
    }
}

function obtenerUsuario($nombre,$contrasena) {
    global $conn;
    
    $stmt = $conn->prepare("SELECT * FROM Usuario WHERE Nombre=? AND Contrasena=?");
    $stmt->bindParam(1,$nombre, PDO::PARAM_STR);
    $stmt->bindParam(2, $contrasena, PDO::PARAM_STR);
    $stmt->execute();
    
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    error_log('Usuario encontrado: ' . print_r($result, true));
    
    if ($result) {
        // Usuario encontrado, devolver datos como un array asociativo
        return true;
    } else {
        // Usuario no encontrado
        return null;
    }
}

function comprobarUsuario($nombre) {
    global $conn;
    
    $stmt = $conn->prepare("SELECT * FROM Usuario WHERE Nombre=?");
    $stmt->bindParam(1,$nombre, PDO::PARAM_STR);
    $stmt->execute();
    
    // $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    // error_log('Usuario encontrado: ' . print_r($result, true));
    error_log('Número de filas: ' . $stmt -> rowCount());
    return $stmt -> rowCount();
}

?>
