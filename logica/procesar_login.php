<?php
session_start();

if (!isset($_POST['email']) || !isset($_POST['password'])) {
    echo "<h3>Error: faltan datos del formulario.</h3>";
    echo "<a href='login.php'>Volver</a>";
    exit;
}

$email = trim($_POST['email']);
$password = trim($_POST['password']);

if ($email === "" || $password === "") {
    echo "<h3>Error: debes llenar todos los campos.</h3>";
    echo "<a href='login.php'>Volver</a>";
    exit;
}

$api_url = "https://bibliotecaitesg.online/api/login";

$data = [
    'email' => $email,
    'password' => $password,
];

$options = [
    'http' => [
        'header'  => "Content-type: application/json\r\n",
        'method'  => 'POST',
        'content' => json_encode($data),
        'ignore_errors' => true // Permite leer la respuesta incluso si hay errores HTTP
    ],
];

$context  = stream_context_create($options);
$result = file_get_contents($api_url, false, $context);

if ($result === FALSE) {
    echo "<h3>Error al conectar con la API de autenticación.</h3>";
    echo "<a href='login.php'>Volver</a>";
    exit;
}

$http_response_header_array = $http_response_header;
$http_status_code = 0;

foreach($http_response_header_array as $header) {
    if (preg_match("/^HTTP\/\d(?:\.\d)?\s*(\d+)/", $header, $matches)) {
        $http_status_code = (int)$matches[1];
        break;
    }
}

if ($http_status_code !== 200) {
    $response_data = json_decode($result, true);
    $error_message = $response_data['message'] ?? 'Error desconocido en la API.';
    echo "<script>
        alert('Error de autenticación: " . htmlspecialchars($error_message, ENT_QUOTES, 'UTF-8') . "');
        window.location.href = 'login.php';
    </script>";
    exit;
}

$response_data = json_decode($result, true);

if (isset($response_data['token']) && isset($response_data['user'])) {
    $_SESSION['user_token'] = $response_data['token'];
    $_SESSION['user_id'] = $response_data['user']['id'];
    $_SESSION['user_name'] = $response_data['user']['name'];
    $_SESSION['user_email'] = $response_data['user']['email'];

    // Puedes agregar el rol si la API lo proporciona y lo necesitas
    // $_SESSION['user_rol'] = $response_data['user']['id_rol'];

    echo "<script>
        alert('¡Bienvenido, " . htmlspecialchars($_SESSION['user_name'], ENT_QUOTES, 'UTF-8') . "!');
        window.location.href = '../index.php'; // Redirige a la página principal o panel de usuario
    </script>";
    exit;
} else {
    echo "<script>
        alert('Respuesta inesperada de la API. Inténtalo de nuevo.');
        window.location.href = 'login.php';
    </script>";
    exit;
}
?>
