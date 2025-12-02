<?php
require_once "../includes/header.php";
require_once "../includes/sidebar.php";
require_once __DIR__ . '/../../logica/conexion.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "<script>alert('ID inválido'); window.location='index.php';</script>";
    exit;
}
$id = intval($_GET['id']);

$sql = $conexion->query("SELECT * FROM autores WHERE id_autor = $id");

if ($sql->num_rows == 0) {
    echo "<script>alert('El usuario no existe'); window.location='index.php';</script>";
    exit;
}

$autor = $sql->fetch_assoc();

if ($_POST) {
    $nombre = $conexion->real_escape_string($_POST['nombre']);
    $apellido = $conexion->real_escape_string($_POST['apellido']);
    $pais = $conexion->real_escape_string($_POST['pais']);

    $conexion->query("UPDATE autores 
                      SET nombre='$nombre', apellido='$apellido', pais='$pais'
                      WHERE id_autor=$id");

   echo "<script>alert('Autor actualizado correctamente'); window.location='index.php';</script>";
    exit;
}
?>

<h2>Editar Autor</h2>

<form method="POST">
    <label>Nombre:</label>
    <input class="form-control mb-2" name="nombre" value="<?= htmlspecialchars($autor['nombre']) ?>" required>

    <label>Apellido:</label>
    <input class="form-control mb-2" name="apellido" value="<?= htmlspecialchars($autor['apellido']) ?>">

    <label>Pais:</label>
    <input class="form-control mb-2" name="pais" value="<?= htmlspecialchars($autor['pais']) ?>">

    <button class="btn btn-primary">Actualizar</button>
</form>

<?php require_once "../includes/footer.php"; ?>
