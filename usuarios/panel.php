<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

require __DIR__ . '/../logica/conexion.php';   
require_once "/includes/header.php";
require_once "/includes/sidebar.php";

// Validar si el usuario está logueado
if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../login.php");
    exit();
}

$id_usuario = $_SESSION['id_usuario'];

// Obtener datos del usuario
$sqlUser = "SELECT nombre, email FROM usuarios WHERE id_usuario = ?";
$stmtUser = $conexion->prepare($sqlUser);
$stmtUser->bind_param("i", $id_usuario);
$stmtUser->execute();
$user = $stmtUser->get_result()->fetch_assoc();

// Obtener últimos movimientos
$sqlMov = "
    SELECT id_venta, fecha, total 
    FROM ventas 
    WHERE id_usuario = ?
    ORDER BY fecha DESC
    LIMIT 10
";

$stmtMov = $conexion->prepare($sqlMov);
$stmtMov->bind_param("i", $id_usuario);
$stmtMov->execute();
$movimientos = $stmtMov->get_result();
?>
<h2>Mi Cuenta</h2>

<div class="card mb-4">
    <div class="card-header bg-primary text-white">Datos del Usuario</div>
    <div class="card-body">
        <p><strong>Nombre:</strong> <?= $user['nombre'] ?></p>
        <p><strong>Email:</strong> <?= $user['email'] ?></p>

        <a href="editar_usuario.php" class="btn btn-warning">Editar Datos</a>
        <a href="cambiar_password.php" class="btn btn-secondary">Cambiar Contraseña</a>
    </div>
</div>

<div class="card">
    <div class="card-header bg-success text-white">Últimos Movimientos</div>
    <div class="card-body">
        <table class="table table-bordered">
            <tr>
                <th>ID Venta</th>
                <th>Fecha</th>
                <th>Total</th>
            </tr>

            <?php while ($m = $movimientos->fetch_assoc()): ?>
            <tr>
                <td><?= $m['id_venta'] ?></td>
                <td><?= $m['fecha'] ?></td>
                <td>$<?= number_format($m['total'], 2) ?></td>
            </tr>
            <?php endwhile; ?>
        </table>
    </div>
</div>

<?php require_once "../includes/footer.php"; ?>
