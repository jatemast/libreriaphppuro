<?php
require __DIR__ . '/../logica/conexion.php';
//require_once "../includes/validar_sesion_usuario.php";

$id_usuario = $_SESSION['id_usuario'];

if ($_POST) {
    $pass1 = $_POST['pass1'];
    $pass2 = $_POST['pass2'];

    if ($pass1 === $pass2) {
        $hash = password_hash($pass1, PASSWORD_DEFAULT);

        $update = $conexion->prepare("UPDATE usuarios SET password=? WHERE id_usuario=?");
        $update->bind_param("si", $hash, $id_usuario);
        $update->execute();

        header("Location: cuenta.php?pass=1");
        exit();
    } else {
        $error = "Las contraseñas no coinciden.";
    }
}
?>

<?php require_once "includes/header.php"; ?>
<?php require_once "includes/sidebar.php"; ?>

<h2>Cambiar Contraseña</h2>

<?php if (isset($error)): ?>
<div class="alert alert-danger"><?= $error ?></div>
<?php endif; ?>

<form method="POST">
    <div class="mb-3">
        <label>Nueva Contraseña</label>
        <input type="password" name="pass1" class="form-control" required>
    </div>

    <div class="mb-3">
        <label>Confirmar Contraseña</label>
        <input type="password" name="pass2" class="form-control" required>
    </div>

    <button class="btn btn-primary">Actualizar</button>
</form>

<?php require_once "includes/footer.php"; ?>
