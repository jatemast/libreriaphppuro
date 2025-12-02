<?php
require_once "../includes/header.php";
require_once "../includes/sidebar.php";

$id = $_GET['id'];
$categoria = $conexion->query("SELECT * FROM categorias WHERE id_categoria = $id")->fetch_assoc();

if ($_POST) {
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];

    $conexion->query("
        UPDATE categorias 
        SET nombre='$nombre', descripcion='$descripcion'
        WHERE id_categoria=$id
    ");

    header("Location: index.php");
}
?>

<h2>Editar Categoría</h2>

<form method="POST">
    <input class="form-control mb-2" name="nombre" value="<?= $categoria['nombre'] ?>" required>
    <input class="form-control mb-2" name="descripcion" value="<?= $categoria['descripcion'] ?>">

    <button class="btn btn-primary">Actualizar</button>
</form>

<?php require_once "../includes/footer.php"; ?>
