<?php
// Definir la clave de servidor de Firebase Cloud Messaging
define('FCM_SERVER_KEY', 'AAAAiXDJStc:APA91bEn0ePsrC2ky0PzRsrzcXguPXUDtHnqg5E9NZNWkup9Pcr5Q48HLCTkgBTRQUkh1DL4_S8ULcetBM3oRrm6As9QfdaXgTzqBAHLysIMco7jYkclDIhP12luCilb_UJAFtI5f2JA');

// URL de la API de FCM
$apiUrl = 'https://fcm.googleapis.com/fcm/send';

// Datos de la notificación a enviar
$data = array(
    'to' => '/topics/all', // Destino de la notificación (todos los usuarios suscritos al tema 'all')
    'notification' => array(
        'title' => $_POST['titulo'], // Título de la notificación
        'body' => $_POST['mensaje'], // Cuerpo de la notificación
    )
);

// Cabecera de la solicitud HTTP
$headers = array(
    'Authorization: key=' . FCM_SERVER_KEY,
    'Content-Type: application/json'
);

// Inicializar cURL
$ch = curl_init();

// Configurar la solicitud cURL
curl_setopt($ch, CURLOPT_URL, $apiUrl);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

// Ejecutar la solicitud cURL y obtener la respuesta
$response = curl_exec($ch);

// Comprobar si hay errores
if ($response === false) {
    error_log('Error: ' . curl_error($ch));
} else {
    error_log('Respuesta FCM: ' . $response);
}

// Cerrar la sesión cURL
curl_close($ch);
?>
