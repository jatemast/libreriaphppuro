<?php
require_once "../includes/header.php";
require_once "../includes/sidebar.php";
require_once __DIR__ . '/../../logica/conexion.php';

// Obtener libro por ID
$id = intval($_GET['id']);
$libro = $conexion->query("SELECT * FROM libros WHERE id_libro=$id")->fetch_assoc();

// Listas para selects
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

    // Imagen
    if (!empty($_FILES['imagen']['tmp_name'])) {
        // Nueva imagen en blob
        $imagenNueva = addslashes(file_get_contents($_FILES['imagen']['tmp_name']));
    } else {
        // Mantener la existente
        $imagenNueva = $libro['imagen'];
    }

    // Actualizar libro
    $conexion->query("
        UPDATE libros SET
            titulo='$titulo',
            id_autor=$id_autor,
            id_categoria=$id_categoria,
            editorial='$editorial',
            precio=$precio,
            stock=$stock,
            fecha_publicacion='$fecha_publicacion',
            descripcion='$descripcion',
            imagen='$imagenNueva'
        WHERE id_libro=$id
    ");

    header("Location: index.php");
    exit;
}
?>

<h2>Editar Libro</h2>

<form method="POST" enctype="multipart/form-data">

    <input class="form-control mb-2" name="titulo" value="<?= $libro['titulo'] ?>" required>

    <select class="form-control mb-2" name="id_autor">
        <?php while ($a = $autores->fetch_assoc()): ?>
            <option value="<?= $a['id_autor'] ?>" <?= $a['id_autor'] == $libro['id_autor'] ? 'selected' : '' ?>>
                <?= $a['nombre'] . " " . $a['apellido'] ?>
            </option>
        <?php endwhile; ?>
    </select>

    <select class="form-control mb-2" name="id_categoria">
        <?php while ($c = $categorias->fetch_assoc()): ?>
            <option value="<?= $c['id_categoria'] ?>" <?= $c['id_categoria'] == $libro['id_categoria'] ? 'selected' : '' ?>>
                <?= $c['nombre'] ?>
            </option>
        <?php endwhile; ?>
    </select>

    <input class="form-control mb-2" name="editorial" value="<?= $libro['editorial'] ?>">

    <input class="form-control mb-2" type="number" step="0.01" name="precio" value="<?= $libro['precio'] ?>">

    <input class="form-control mb-2" type="number" name="stock" value="<?= $libro['stock'] ?>">

    <input class="form-control mb-2" type="date" name="fecha_publicacion" value="<?= $libro['fecha_publicacion'] ?>">

    <textarea class="form-control mb-2" name="descripcion" rows="4"><?= $libro['descripcion'] ?></textarea>

    <p><strong>Imagen actual:</strong></p>

    <?php if ($libro['imagen']): ?>
        <img src="data:image/jpeg;base64,<?= base64_encode($libro['imagen']) ?>" width="140" class="mb-3">
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
