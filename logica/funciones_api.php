<?php

function obtenerLibrosDeAPI() {
    $apiUrl = "https://bibliotecaitesg.online/api/books";
    // El Bearer Token se obtiene de la sesión después del login.
    // Usaremos el token guardado en $_SESSION['user_token'].
    $bearerToken = isset($_SESSION['user_token']) ? $_SESSION['user_token'] : '';

    $options = [
        'http' => [
            'header' => "Content-type: application/json\r\n" .
                        "Authorization: Bearer " . $bearerToken . "\r\n",
            'method' => 'GET',
            'ignore_errors' => true // Permite capturar errores HTTP como 401, 404, etc.
        ]
    ];

    $context = stream_context_create($options);
    $response = @file_get_contents($apiUrl, false, $context);

    if ($response === FALSE) {
        error_log("Error al acceder a la API: " . error_get_last()['message']);
        return null;
    }

    $http_response_header_str = implode("\r\n", $http_response_header);
    if (preg_match('/HTTP\/1\.\d\s(\d{3})/', $http_response_header_str, $matches)) {
        $statusCode = (int)$matches[1];
        if ($statusCode >= 400) {
            error_log("Error de la API (Código: $statusCode): " . $response);
            return null;
        }
    }
    
    $libros = json_decode($response, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        error_log("Error al decodificar la respuesta JSON de la API: " . json_last_error_msg());
        return null;
    }

    return $libros;
}

?>