<?php
require_once "../includes/header.php";
require_once "../includes/sidebar.php";
require_once __DIR__ . '/../../logica/conexion.php';


$result = $conexion->query("
    SELECT v.*, u.nombre, u.apellido 
    FROM ventas v
    INNER JOIN usuarios u ON v.id_usuario = u.id_usuario
");
?>

<h2>Ventas</h2>
<a href="crear.php" class="btn btn-primary mb-3">Agregar Venta</a>

<table class="table table-striped table-bordered">
    <tr>
        <th>ID</th>
        <th>Usuario</th>
        <th>Fecha</th>
        <th>Total</th>
        <th>Acciones</th>
    </tr>

    <?php while ($row = $result->fetch_assoc()): ?>
    <tr>
        <td><?= $row['id_venta'] ?></td>
        <td><?= $row['nombre'] . " " . $row['apellido'] ?></td>
        <td><?= $row['fecha'] ?></td>
        <td>$<?= number_format($row['total'], 2) ?></td>
        <td>
            <a href="editar.php?id=<?= $row['id_venta'] ?>" class="btn btn-warning btn-sm">Editar</a>
            <a href="eliminar.php?id=<?= $row['id_venta'] ?>" class="btn btn-danger btn-sm">Eliminar</a>
        </td>
    </tr>
    <?php endwhile; ?>
</table>

<?php require_once "../includes/footer.php"; ?>
