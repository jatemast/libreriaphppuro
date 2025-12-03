<?php
session_start();
require_once __DIR__ . "/../logica/funciones_api.php";

// Verificar autenticación y rol de administrador
if (!isset($_SESSION['user_token']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: ../logica/login.php');
    exit;
}

$libro = null;
$error = '';
$libro_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($libro_id > 0) {
    // Obtener detalles del libro de la API
    $apiUrl = "https://bibliotecaitesg.online/api/books/" . $libro_id;
    $bearerToken = $_SESSION['user_token'];

    $options = [
        'http' => [
            'header' => "Content-type: application/json\r\n" .
                        "Authorization: Bearer " . $bearerToken . "\r\n",
            'method' => 'GET',
            'ignore_errors' => true
        ]
    ];

    $context = stream_context_create($options);
    $response = @file_get_contents($apiUrl, false, $context);

    if ($response === FALSE) {
        $error = "Error al conectar con la API para obtener el libro.";
    } else {
        $http_response_header_str = implode("\r\n", $http_response_header);
        if (preg_match('/HTTP\/1\.\d\s(\d{3})/', $http_response_header_str, $matches)) {
            $statusCode = (int)$matches[1];
            if ($statusCode !== 200) {
                $response_data = json_decode($response, true);
                $error = $response_data['message'] ?? 'Error desconocido al obtener el libro.';
            } else {
                $libro = json_decode($response, true);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    $error = "Error al decodificar la respuesta JSON del libro.";
                }
            }
        }
    }
} else {
    $error = "ID de libro no proporcionado.";
}

// Procesar el formulario de edición
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $libro_id > 0) {
    $name = trim($_POST['name'] ?? '');
    $quantity = (int)($_POST['quantity'] ?? 0);
    $price = (float)($_POST['price'] ?? 0.0);
    $image_base64 = $_POST['image_base64'] ?? null; // Campo oculto para la imagen Base64

    $update_data = [
        'name' => $name,
        'quantity' => $quantity,
        'price' => $price,
    ];

    if ($image_base64) {
        $update_data['image'] = $image_base64;
    }

    $apiUrl = "https://bibliotecaitesg.online/api/books/" . $libro_id;
    $bearerToken = $_SESSION['user_token'];

    $options = [
        'http' => [
            'header'  => "Content-type: application/json\r\n" .
                         "Authorization: Bearer " . $bearerToken . "\r\n",
            'method'  => 'PUT',
            'content' => json_encode($update_data),
            'ignore_errors' => true
        ],
    ];

    $context  = stream_context_create($options);
    $result = file_get_contents($apiUrl, false, $context);

    if ($result === FALSE) {
        $error = "Error al conectar con la API para actualizar el libro.";
    } else {
        $http_response_header_str = implode("\r\n", $http_response_header);
        if (preg_match('/HTTP\/1\.\d\s(\d{3})/', $http_response_header_str, $matches)) {
            $statusCode = (int)$matches[1];
            if ($statusCode !== 200) {
                $response_data = json_decode($result, true);
                $error = $response_data['message'] ?? 'Error desconocido al actualizar el libro.';
            } else {
                echo "<script>
                    alert('Libro actualizado correctamente.');
                    window.location.href = '../index.php';
                </script>";
                exit;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Libro</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../src/index.css"> <!-- Reutiliza el CSS principal -->
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="../index.php">Librería</a>
        </div>
    </nav>

    <div class="container mt-5">
        <h1>Editar Libro</h1>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger" role="alert">
                <?= htmlspecialchars($error) ?>
            </div>
            <a href="../index.php" class="btn btn-secondary">Volver al inicio</a>
        <?php elseif ($libro): ?>
            <form action="editar_libro.php?id=<?= $libro['id'] ?>" method="POST" enctype="multipart/form-data" class="mt-4">
                <div class="mb-3">
                    <label for="name" class="form-label">Nombre del Libro:</label>
                    <input type="text" class="form-control" id="name" name="name" value="<?= htmlspecialchars($libro['name'] ?? '') ?>" required>
                </div>
                <div class="mb-3">
                    <label for="quantity" class="form-label">Cantidad:</label>
                    <input type="number" class="form-control" id="quantity" name="quantity" value="<?= htmlspecialchars($libro['quantity'] ?? 0) ?>" required min="0">
                </div>
                <div class="mb-3">
                    <label for="price" class="form-label">Precio:</label>
                    <input type="number" class="form-control" id="price" name="price" value="<?= htmlspecialchars($libro['price'] ?? 0.0) ?>" required min="0" step="0.01">
                </div>
                <div class="mb-3">
                    <label for="image_upload" class="form-label">Imagen (opcional):</label>
                    <input type="file" class="form-control" id="image_upload" name="image_upload" accept="image/*">
                    <input type="hidden" id="image_base64" name="image_base64">
                    <?php if (!empty($libro['image_url'])): ?>
                        <p class="mt-2">Imagen actual:</p>
                        <img src="<?= htmlspecialchars($libro['image_url']) ?>" alt="Imagen actual del libro" style="max-width: 150px; height: auto; display: block; margin-top: 10px;">
                    <?php endif; ?>
                </div>
                <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                <a href="../index.php" class="btn btn-secondary">Cancelar</a>
            </form>
        <?php else: ?>
            <div class="alert alert-info" role="alert">
                No se encontró el libro o no se pudo cargar.
            </div>
            <a href="../index.php" class="btn btn-secondary">Volver al inicio</a>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('image_upload').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onloadend = function() {
                    document.getElementById('image_base64').value = reader.result;
                };
                reader.readAsDataURL(file);
            } else {
                document.getElementById('image_base64').value = '';
            }
        });
    </script>
</body>
</html>