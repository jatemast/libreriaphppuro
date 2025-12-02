<?php
require_once "../includes/header.php";
require_once "../includes/sidebar.php";
require_once __DIR__ . '/../../logica/conexion.php';

$autores = $conexion->query("SELECT * FROM autores ORDER BY nombre");
$categorias = $conexion->query("SELECT * FROM categorias ORDER BY nombre");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $titulo = $_POST['titulo'];
    $id_autor = $_POST['id_autor'];
    $id_categoria = $_POST['id_categoria'];
    $editorial = $_POST['editorial'];
    $precio = $_POST['precio'];
    $stock = $_POST['stock'];
    $fecha_publicacion = $_POST['fecha_publicacion'];
    $descripcion = $_POST['descripcion'];

    // Imagen BLOB
    $imagen = NULL;
    if (!empty($_FILES['imagen']['tmp_name'])) {
        $imagen = file_get_contents($_FILES['imagen']['tmp_name']);
        $imagen = $conexion->real_escape_string($imagen);
    }

    $sql = "
        INSERT INTO libros 
        (titulo, id_autor, id_categoria, editorial, precio, stock, fecha_publicacion, descripcion, imagen)
        VALUES 
        ('$titulo', $id_autor, $id_categoria, '$editorial', $precio, $stock, '$fecha_publicacion', '$descripcion', '$imagen')
    ";

    if (!$conexion->query($sql)) {
        die("Error al insertar libro: " . $conexion->error);
    }

    header("Location: index.php");
    exit;
}
?>

<h2>Agregar Libro</h2>

<form method="POST" enctype="multipart/form-data">

    <input class="form-control mb-2" name="titulo" placeholder="Título" required>

    <select class="form-control mb-2" name="id_autor" required>
        <option value="">Seleccione Autor</option>
        <?php while($a = $autores->fetch_assoc()): ?>
            <option value="<?= $a['id_autor'] ?>"><?= $a['nombre'] ." ". $a['apellido'] ?></option>
        <?php endwhile; ?>
    </select>

    <select class="form-control mb-2" name="id_categoria" required>
        <option value="">Seleccione Categoría</option>
        <?php while($c = $categorias->fetch_assoc()): ?>
            <option value="<?= $c['id_categoria'] ?>"><?= $c['nombre'] ?></option>
        <?php endwhile; ?>
    </select>

    <input class="form-control mb-2" name="editorial" placeholder="Editorial">

    <input class="form-control mb-2" type="number" name="precio" step="0.01" placeholder="Precio">

    <input class="form-control mb-2" type="number" name="stock" placeholder="Stock" value="0">

    <label>Fecha de publicación:</label>
    <input class="form-control mb-2" type="date" name="fecha_publicacion">

    <textarea class="form-control mb-2" name="descripcion" rows="3" placeholder="Descripción"></textarea>

    <label>Imagen del libro:</label>
    <input type="file" id="imagenInput" name="imagen" class="form-control mb-3" accept="image/*">

    <!-- Vista previa -->
    <img id="previewImg" src="" class="mt-2" width="150" style="display:none; border-radius:8px; border:1px solid #444;">

    <button class="btn btn-success">Guardar</button>
</form>

<script>
document.getElementById("imagenInput").addEventListener("change", function(e) {
    const file = e.target.files[0];
    if (!file) return;

    const reader = new FileReader();
    reader.onload = function() {
        const img = document.getElementById("previewImg");
        img.src = reader.result;
        img.style.display = "block";
    };
    reader.readAsDataURL(file);
});
</script>

<?php require_once "../includes/footer.php"; ?>
