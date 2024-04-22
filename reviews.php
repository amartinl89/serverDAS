<?php
header('Content-Type: text/html; charset=utf-8');
include 'database.php'; // Incluir el archivo de conexión a la base de datos

// Insertar una nueva revisión
function insertarReview($fecha, $usuario, $nombre, $imagen, $ano, $resena, $punt) {
    global $conn;
    
    try {
        // Preparar la consulta SQL con los marcadores de posición
        $stmt = $conn->prepare("INSERT INTO Review (Fecha, Usuario, Nombre, Imagen, Ano, Puntuacion, Resena) VALUES (?, ?, ?, ?, ?, ?, ?)");
        error_log($punt);
        // Vincular los parámetros a los marcadores de posición
        error_log($imagen);
        $stmt->bindParam(1, $fecha, PDO::PARAM_STR);
        $stmt->bindParam(2, $usuario, PDO::PARAM_STR);
        $stmt->bindParam(3, $nombre, PDO::PARAM_STR);
        //$stmt->bindParam(4, $imagen, PDO::PARAM_LOB);  // Si $imagen es una cadena de datos binarios (base64, por ejemplo)
        $stmt->bindParam(4, $imagen, PDO::PARAM_STR);
        $stmt->bindParam(5, $ano, PDO::PARAM_INT);
        $stmt->bindParam(6, $punt, PDO::PARAM_INT);
        $stmt->bindParam(7, $resena, PDO::PARAM_STR);
        
        // Ejecutar la consulta preparada
        $stmt->execute();
        return true;
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'error' => 'Error al insertar revisión: ' . $e->getMessage()]);
        return false;
    }
}

// Actualizar una revisión existente
function actualizarReview($fecha, $nuevoNombre, $nuevaImagen, $nuevoAno, $nuevaResena, $nuevaPunt) {
    global $conn;
    try{
    $stmt = $conn->prepare("UPDATE Review SET Nombre=?, Imagen=?, Ano=?, Resena=?, Puntuacion=? WHERE Fecha=?");
    $stmt->bindParam(1, $nuevoNombre, PDO::PARAM_STR);
    $stmt->bindParam(2, $nuevaImagen, PDO::PARAM_STR);
    $stmt->bindParam(3, $nuevoAno, PDO::PARAM_INT);
    $stmt->bindParam(4, $nuevaResena, PDO::PARAM_STR);
    $stmt->bindParam(5, $nuevaPunt, PDO::PARAM_INT);
    $stmt->bindParam(6, $fecha,PDO::PARAM_STR);
    $stmt->execute();
    return true;
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'error' => 'Error al insertar revisión: ' . $e->getMessage()]);
        return false;
    }
}

// Obtener todas las revisiones como JSON
function visualizarLista($usuario) {
    global $conn;
    try {
        $stmt = $conn->prepare("SELECT Fecha, Nombre, Imagen, Ano, Puntuacion, Resena FROM Review WHERE Usuario = ?");
        
        if (!$stmt) {
            throw new Exception("Error al preparar la consulta");
        }
        $stmt->bindParam(1, $usuario, PDO::PARAM_STR);
        $stmt->execute();
        $reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);
        error_log('Revisiones: ' . print_r($reviews, true));
        return $reviews;
        
    } catch (Exception $e) {
        // Manejar errores aquí (puedes registrarlos en los registros de errores)
        error_log('Error en visualizarLista: ' . $e->getMessage());
        return false; // Devolver indicador de error
    }
}


// Borrar una revisión por fecha
function borrarResena($fecha) {
    global $conn;
    
    $stmt = $conn->prepare("DELETE FROM Review WHERE Fecha=?");
    $stmt->bindParam(1, $fecha,PDO::PARAM_STR);
    $stmt->execute();
    $stmt->close();
    if ($stmt) {
        return true;
    } else {
        return false;
    }
}
?>
