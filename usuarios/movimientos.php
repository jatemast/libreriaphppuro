<?php
require __DIR__ . '/../logica/conexion.php';
//require_once "../includes/validar_sesion_usuario.php";
require_once "includes/header.php";
require_once "includes/sidebar.php";

$id_usuario = $_SESSION['id_usuario'];

$sqlMov = "
    SELECT id_venta, fecha, total
    FROM ventas
    WHERE id_usuario = ?
    ORDER BY fecha DESC
    LIMIT 20
";
$stmt = $conexion->prepare($sqlMov);
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$movimientos = $stmt->get_result();
?>

<h2>Mis Últimos Movimientos</h2>

<table class="table table-bordered">
    <tr>
        <th>Fecha</th>
        <th>Total</th>
    </tr>

    <?php while ($m = $movimientos->fetch_assoc()): ?>
        <tr>
            <td><?= $m['fecha'] ?></td>
            <td>$<?= number_format($m['total'], 2) ?></td>
        </tr>
    <?php endwhile; ?>
</table>

<?php require_once "includes/footer.php"; ?>
