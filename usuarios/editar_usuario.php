<?php
require __DIR__ . '/../logica/conexion.php';
//require_once "../includes/validar_sesion_usuario.php";

$id_usuario = $_SESSION['id_usuario'];

if ($_POST) {
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];

    $update = $conexion->prepare("UPDATE usuarios SET nombre=?, email=? WHERE id_usuario=?");
    $update->bind_param("ssi", $nombre, $email, $id_usuario);
    $update->execute();

    header("Location: cuenta.php?ok=1");
    exit();
}

$sql = $conexion->prepare("SELECT nombre, email FROM usuarios WHERE id_usuario=?");
$sql->bind_param("i", $id_usuario);
$sql->execute();
$user = $sql->get_result()->fetch_assoc();
?>

<?php require_once "includes/header.php"; ?>
<?php require_once "includes/sidebar.php"; ?>

<h2>Editar Mis Datos</h2>

<form method="POST">
    <div class="mb-3">
        <label>Nombre</label>
        <input type="text" name="nombre" class="form-control" value="<?= $user['nombre'] ?>" required>
    </div>

    <div class="mb-3">
        <label>Email</label>
        <input type="email" name="email" class="form-control" value="<?= $user['email'] ?>" required>
    </div>

    <button class="btn btn-primary">Guardar</button>
</form>

<?php require_once "includes/footer.php"; ?>
