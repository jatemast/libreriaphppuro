<?php
require_once "../includes/header.php";
require_once "../includes/sidebar.php";

if ($_POST) {
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];

    $conexion->query("INSERT INTO categorias (nombre, descripcion)
                      VALUES ('$nombre', '$descripcion')");

    header("Location: index.php");
}
?>

<h2>Crear Categoría</h2>

<form method="POST">
    <input class="form-control mb-2" name="nombre" placeholder="Nombre" required>
    <input class="form-control mb-2" name="descripcion" placeholder="Descripción (opcional)">
    
    <button class="btn btn-success">Guardar</button>
</form>

<?php require_once "../includes/footer.php"; ?>
