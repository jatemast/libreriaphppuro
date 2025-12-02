<?php
require __DIR__ . '/../logica/conexion.php';
//require_once "../includes/validar_sesion_usuario.php";
require_once "includes/header.php";
require_once "includes/sidebar.php";

$id_usuario = $_SESSION['id_usuario'];

$sql = "SELECT nombre, email FROM usuarios WHERE id_usuario = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
?>

<h2>Mi Cuenta</h2>

<div class="card">
    <div class="card-header bg-primary text-white">Información del Usuario</div>
    <div class="card-body">
        <p><strong>Nombre:</strong> <?= $user['nombre'] ?></p>
        <p><strong>Email:</strong> <?= $user['email'] ?></p>

        <a href="editar_usuario.php" class="btn btn-warning">Editar Datos</a>
        <a href="cambiar_password.php" class="btn btn-secondary">Cambiar Contraseña</a>
    </div>
</div>

<?php require_once "includes/footer.php"; ?>
