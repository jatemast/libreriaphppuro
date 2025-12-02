<?php
require_once "../includes/header.php";
require_once "../includes/sidebar.php";

$ventas = $conexion->query("SELECT * FROM ventas");
$libros = $conexion->query("SELECT * FROM libros");

if ($_POST) {
    $id_venta = $_POST['id_venta'];
    $id_libro = $_POST['id_libro'];
    $cantidad = $_POST['cantidad'];
    $precio_unitario = $_POST['precio_unitario'];
    $subtotal = $cantidad * $precio_unitario;

    $conexion->query("
        INSERT INTO detalle_venta (id_venta, id_libro, cantidad, precio_unitario, subtotal)
        VALUES ($id_venta, $id_libro, $cantidad, $precio_unitario, $subtotal)
    ");

    header("Location: index.php");
}
?>

<h2>Crear Detalle de Venta</h2>

<form method="POST">

    <label>Venta</label>
    <select class="form-control mb-2" name="id_venta" required>
        <?php while ($v = $ventas->fetch_assoc()): ?>
            <option value="<?= $v['id_venta'] ?>">Venta #<?= $v['id_venta'] ?></option>
        <?php endwhile; ?>
    </select>

    <label>Libro</label>
    <select class="form-control mb-2" name="id_libro" required>
        <?php while ($l = $libros->fetch_assoc()): ?>
            <option value="<?= $l['id_libro'] ?>"><?= $l['titulo'] ?></option>
        <?php endwhile; ?>
    </select>

    <label>Cantidad</label>
    <input class="form-control mb-2" type="number" name="cantidad" required>

    <label>Precio Unitario</label>
    <input class="form-control mb-2" type="number" step="0.01" name="precio_unitario" required>

    <button class="btn btn-success">Guardar</button>
</form>

<?php require_once "../includes/footer.php"; ?>
