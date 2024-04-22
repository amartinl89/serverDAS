<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
include 'database.php'; // Incluir el archivo de conexión a la base de datos
include 'reviews.php'; // Incluir el archivo de funciones de revisión
include 'usuarios.php'; // Incluir el archivo de funciones de usuario

// Verificar el método de la solicitud
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    // Obtener la operación y los parámetros
    $operation = $_GET['operation'] ?? '';
    

    switch ($operation) {
        case 'visualizarLista':
            $usuario = $_GET['usuario'] ?? '';

            // Llamar a la función visualizarLista para obtener las revisiones del usuario
            $reviews = visualizarLista($usuario);

            // Decodificar las imágenes binarias
            

            // Devolver las revisiones como JSON
            echo json_encode($reviews);
            break;

        // Agregar más casos para otras operaciones GET si es necesario
        default:
            // Operación no válida
            echo json_encode(['error' => 'Operación GET no válida']);
        
        break;

        case 'comprobarUsuario':
            $nombre = $_GET['nombre'] ?? '';
            error_log(comprobarUsuario($nombre));
            if (comprobarUsuario($nombre)>0) {
                echo json_encode(['success' => false]);
            } else {
                echo json_encode(['success' => true]);
            }
            break;
    }
} elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtener la operación y los datos del cuerpo de la solicitud
    $postData = file_get_contents('php://input');

    // Decodificar los datos JSON en un arreglo asociativo
    $data = json_decode($postData, true);

    if ($data === null) {
        // Error al decodificar JSON
        http_response_code(400); // Bad Request
        echo json_encode(['error' => 'Datos JSON no válidos']);
        exit;
    }
    $operation = $data['operation'] ?? '';
    error_log('Operación recibida: ' . $operation);
    
    
    switch ($operation) {
        case 'insertarReview':
            $nombre = $data['nombre'] ?? '';
            $imagen = $data['imagen'] ?? ''; // Los datos binarios de la imagen (por ejemplo, desde un formulario de carga de archivos)
            
            $ano = $data['ano'] ?? '';
            $resena = isset($data['resena']) ? $data['resena'] : '';
            $puntuacion = $data['puntuacion'] ?? '';
            $fecha = $data['fecha'] ?? '';
            $usuario = $data['usuario'] ?? '';
            error_log('Datos de la revisión: ' . print_r($data, true));
            if(insertarReview($fecha, $usuario, $nombre, $imagen, $ano, $resena, $puntuacion)){
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'error' => 'Error al insertar reseña']);
            }
            break;
        case 'insertarUsuario':
            // Obtener parámetros del cuerpo de la solicitud
            $nombre = $data['nombre'] ?? '';
            $contrasena = $data['contrasena'] ?? '';

            // Llamar a la función insertarUsuario
            if (insertarUsuario($nombre, $contrasena)) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'error' => 'Error al insertar usuario']);
            }
            break;
        case 'obtenerUsuario':
            // Obtener parámetros del cuerpo de la solicitud
            $nombre = $data['nombre'] ?? '';
            $contrasena = $data['contrasena'] ?? '';


            // Llamar a la función obtenerUsuario
            $usuario = obtenerUsuario($nombre, $contrasena);
            if ($usuario !== null) {
                echo json_encode(['success' => true, 'usuario' => $usuario]);
            } else {
                echo json_encode(['success' => false, 'error' => 'Usuario no encontrado']);
            }
            break;
        case 'borrarResena':
            $fecha = $data['fecha'] ?? '';
            if (borrarResena($fecha)) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'error' => 'Error al borrar reseña']);
            }
            break;
        // Agregar más casos para otras operaciones POST si es necesario
        default:
            // Operación no válida
            echo json_encode(['error' => 'Operación POST no válida']);
            break;
        case 'actualizarReview':
            $fecha = $data['fecha'] ?? '';
            $nuevoNombre = $data['nuevoNombre'] ?? '';
            $nuevaImagen = $data['nuevaImagen'] ?? '';
            $nuevoAno = $data['nuevoAno'] ?? '';
            $nuevaResena = isset($data['nuevaResena']) ? $data['nuevaResena'] : '';
            $nuevaPunt = $data['nuevaPunt'] ?? '';
            if (actualizarReview($fecha, $nuevoNombre, $nuevaImagen, $nuevoAno, $nuevaResena, $nuevaPunt)) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'error' => 'Error al actualizar reseña']);
            }
            break;
    }
} else {
    // Método de solicitud no admitido
    echo json_encode(['error' => 'Método de solicitud no admitido']);
}
?>
