<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once __DIR__ . '/../../logica/conexion.php';

require_once "../includes/header.php";
require_once "../includes/sidebar.php";

// ✔ Consulta corregida
$sql = "
    SELECT d.id_detalle, d.cantidad, d.precio_unitario, d.subtotal,
           v.id_venta,
           l.titulo
    FROM detalle_ventas d
    INNER JOIN ventas v ON d.id_venta = v.id_venta
    INNER JOIN libros l ON d.id_libro = l.id_libro
";

$result = $conexion->query($sql);

if (!$result) {
    die("Error en la consulta: " . $conexion->error);
}
?>

<h2>Detalle de Venta</h2>
<a href="crear.php" class="btn btn-primary mb-3">Agregar Detalle</a>

<table class="table table-striped table-bordered">
    <tr>
        <th>ID Detalle</th>
        <th>ID Venta</th>
        <th>Libro</th>
        <th>Cantidad</th>
        <th>Precio Unitario</th>
        <th>Subtotal</th>
        <th>Acciones</th>
    </tr>

    <?php while($row = $result->fetch_assoc()): ?>
    <tr>
        <td><?= $row['id_detalle'] ?></td>
        <td><?= $row['id_venta'] ?></td>
        <td><?= $row['titulo'] ?></td>
        <td><?= $row['cantidad'] ?></td>
        <td>$<?= number_format($row['precio_unitario'], 2) ?></td>
        <td>$<?= number_format($row['subtotal'], 2) ?></td>
        <td>
            <a href="editar.php?id=<?= $row['id_detalle'] ?>" class="btn btn-warning btn-sm">Editar</a>
            <a href="eliminar.php?id=<?= $row['id_detalle'] ?>" class="btn btn-danger btn-sm">Eliminar</a>
        </td>
    </tr>
    <?php endwhile; ?>
</table>

<?php require_once "../includes/footer.php"; ?>
