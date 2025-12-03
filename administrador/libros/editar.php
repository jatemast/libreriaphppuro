<?php
session_start();

// Verificar si el usuario está logueado y es administrador
if (!isset($_SESSION['user_token']) || !isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    echo "<script>
        alert('Acceso denegado. Solo los administradores pueden editar libros.');
        window.location.href = '../../logica/login.php'; // Redirige a la página de login o a una página de error
    </script>";
    exit;
}

require_once "../includes/header.php";
require_once "../includes/sidebar.php";
require_once __DIR__ . '/../../logica/conexion.php'; // Para la conexión a la base de datos local
require_once __DIR__ . '/../../logica/funciones_api.php'; // Para obtener libros de la API (si se usa)

$api_base_url = "https://bibliotecaitesg.online/api";

// Obtener libro por ID
$id = intval($_GET['id']);

// Verificar si existe el token en la sesión
if (!isset($_SESSION['user_token'])) {
    echo "<script>
        alert('No estás autenticado. Por favor, inicia sesión.');
        window.location.href = '../../logica/login.php';
    </script>";
    exit;
}

$bearerToken = $_SESSION['user_token'];

// Obtener el libro actual de la API para mostrar los datos en el formulario
$book_api_url = $api_base_url . "/books/" . $id;
$options_get = [
    'http' => [
        'header' => "Content-type: application/json\r\n" .
                    "Authorization: Bearer " . $bearerToken . "\r\n",
        'method' => 'GET',
        'ignore_errors' => true
    ]
];
$context_get  = stream_context_create($options_get);
$result_get = file_get_contents($book_api_url, false, $context_get);

$libro = null;
if ($result_get === FALSE) {
    echo "<script>alert('Error al obtener el libro de la API.'); window.location.href='index.php';</script>";
    exit;
} else {
    $http_response_header_array_get = $http_response_header;
    $http_status_code_get = 0;
    foreach($http_response_header_array_get as $header) {
        if (preg_match("/^HTTP\/\d(?:\.\d)?\s*(\d+)/", $header, $matches)) {
            $http_status_code_get = (int)$matches[1];
            break;
        }
    }

    if ($http_status_code_get === 200) {
        $libro = json_decode($result_get, true);
    } else {
        $response_data_get = json_decode($result_get, true);
        $error_message_get = $response_data_get['message'] ?? 'Error desconocido al obtener el libro de la API.';
        echo "<script>alert('Error: " . htmlspecialchars($error_message_get, ENT_QUOTES, 'UTF-8') . "'); window.location.href='index.php';</script>";
        exit;
    }
}

if (!$libro) {
    echo "<script>alert('Libro no encontrado.'); window.location.href='index.php';</script>";
    exit;
}

// Listas para selects (estos se pueden seguir obteniendo de la DB local o de la API si se requiere)
$autores = $conexion->query("SELECT * FROM autores ORDER BY nombre");
$categorias = $conexion->query("SELECT * FROM categorias ORDER BY nombre");

if ($_POST) {
    $titulo = $_POST['titulo'];
    $id_autor = $_POST['id_autor'];
    $id_categoria = $_POST['id_categoria'];
    $editorial = $_POST['editorial'];
    $precio = $_POST['precio'];
    $stock = $_POST['stock'];
    $fecha_publicacion = $_POST['fecha_publicacion'];
    $descripcion = $_POST['descripcion'];

    $imagenBase64 = null;
    // Imagen: Si se sube una nueva, convertir a Base64
    if (!empty($_FILES['imagen']['tmp_name'])) {
        $type = pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION);
        $data = file_get_contents($_FILES['imagen']['tmp_name']);
        $imagenBase64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
    }

    $data_to_send = [
        "name" => $titulo,
        "author_id" => $id_autor, // Suponiendo que la API espera author_id
        "category_id" => $id_categoria, // Suponiendo que la API espera category_id
        "editorial" => $editorial,
        "price" => floatval($precio),
        "quantity" => intval($stock),
        "publication_date" => $fecha_publicacion,
        "description" => $descripcion,
    ];

    if ($imagenBase64) {
        $data_to_send['image'] = $imagenBase64;
    }

    $options_put = [
        'http' => [
            'header'  => "Content-type: application/json\r\n" .
                         "Accept: application/json\r\n" .
                         "Authorization: Bearer " . $bearerToken . "\r\n",
            'method'  => 'PUT',
            'content' => json_encode($data_to_send),
            'ignore_errors' => true
        ],
    ];

    $context_put  = stream_context_create($options_put);
    $result_put = file_get_contents($book_api_url, false, $context_put);

    if ($result_put === FALSE) {
        echo "<script>alert('Error al conectar con la API de edición de libros.');</script>";
    } else {
        $http_response_header_array_put = $http_response_header;
        $http_status_code_put = 0;
        foreach($http_response_header_array_put as $header) {
            if (preg_match("/^HTTP\/\d(?:\.\d)?\s*(\d+)/", $header, $matches)) {
                $http_status_code_put = (int)$matches[1];
                break;
            }
        }

        if ($http_status_code_put === 200) {
            echo "<script>alert('Libro actualizado exitosamente.'); window.location.href = 'index.php';</script>";
        } else {
            $response_data_put = json_decode($result_put, true);
            $error_message_put = $response_data_put['message'] ?? 'Error desconocido al editar el libro.';
            if (isset($response_data_put['errors'])) {
                foreach ($response_data_put['errors'] as $field => $errors) {
                    $error_message_put .= "\n" . ucfirst($field) . ": " . implode(", ", $errors);
                }
            }
            echo "<script>alert('Error al editar el libro: " . htmlspecialchars($error_message_put, ENT_QUOTES, 'UTF-8') . "');</script>";
        }
    }
}
?>

<h2>Editar Libro</h2>

<form method="POST" enctype="multipart/form-data">

    <input class="form-control mb-2" name="titulo" value="<?= htmlspecialchars($libro['name']) ?>" required>

    <select class="form-control mb-2" name="id_autor">
        <?php while ($a = $autores->fetch_assoc()): ?>
            <option value="<?= $a['id_autor'] ?>" <?= ($a['id_autor'] == ($libro['author_id'] ?? 0)) ? 'selected' : '' ?>>
                <?= htmlspecialchars($a['nombre'] . " " . $a['apellido']) ?>
            </option>
        <?php endwhile; ?>
    </select>

    <select class="form-control mb-2" name="id_categoria">
        <?php while ($c = $categorias->fetch_assoc()): ?>
            <option value="<?= $c['id_categoria'] ?>" <?= ($c['id_categoria'] == ($libro['category_id'] ?? 0)) ? 'selected' : '' ?>>
                <?= htmlspecialchars($c['nombre']) ?>
            </option>
        <?php endwhile; ?>
    </select>

    <input class="form-control mb-2" name="editorial" value="<?= htmlspecialchars($libro['editorial'] ?? '') ?>">

    <input class="form-control mb-2" type="number" step="0.01" name="precio" value="<?= htmlspecialchars($libro['price'] ?? '') ?>">

    <input class="form-control mb-2" type="number" name="stock" value="<?= htmlspecialchars($libro['quantity'] ?? '') ?>">

    <input class="form-control mb-2" type="date" name="fecha_publicacion" value="<?= htmlspecialchars($libro['publication_date'] ?? '') ?>">

    <textarea class="form-control mb-2" name="descripcion" rows="4"><?= htmlspecialchars($libro['description'] ?? '') ?></textarea>

    <p><strong>Imagen actual:</strong></p>

    <?php if (!empty($libro['image_url'])): ?>
        <img src="<?= htmlspecialchars($libro['image_url']) ?>" width="140" class="mb-3">
    <?php else: ?>
        <p class="text-muted">Sin imagen</p>
    <?php endif; ?>

    <label>Nueva imagen (opcional):</label>
    <input id="imagenInput" type="file" name="imagen" class="form-control mb-3" accept="image/*">

    <!-- Vista previa -->
    <img id="previewImg" src="" width="150" style="display:none; border-radius:8px; border:1px solid #444;">

    <button class="btn btn-primary mt-3">Actualizar</button>
</form>

<script>
document.getElementById("imagenInput").addEventListener("change", function(e) {
    const file = e.target.files[0];
    if (file) {
        let reader = new FileReader();
        reader.onload = () => {
            let img = document.getElementById("previewImg");
            img.src = reader.result;
            img.style.display = "block";
        };
        reader.readAsDataURL(file);
    }
});
</script>

<?php require_once "../includes/footer.php"; ?>
