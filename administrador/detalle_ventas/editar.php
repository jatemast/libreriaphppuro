<?php
require_once "../includes/header.php";
require_once "../includes/sidebar.php";

$id = $_GET['id'];

$detalle = $conexion->query("
    SELECT * FROM detalle_venta WHERE id_detalle=$id
")->fetch_assoc();

$ventas = $conexion->query("SELECT * FROM ventas");
$libros = $conexion->query("SELECT * FROM libros");

if ($_POST) {
    $id_venta = $_POST['id_venta'];
    $id_libro = $_POST['id_libro'];
    $cantidad = $_POST['cantidad'];
    $precio_unitario = $_POST['precio_unitario'];
    $subtotal = $cantidad * $precio_unitario;

    $conexion->query("
        UPDATE detalle_venta
        SET id_venta=$id_venta,
            id_libro=$id_libro,
            cantidad=$cantidad,
            precio_unitario=$precio_unitario,
            subtotal=$subtotal
        WHERE id_detalle=$id
    ");

    header("Location: index.php");
}
?>

<h2>Editar Detalle de Venta</h2>

<form method="POST">

    <label>Venta</label>
    <select class="form-control mb-2" name="id_venta">
        <?php while ($v = $ventas->fetch_assoc()): ?>
            <option value="<?= $v['id_venta'] ?>"
                <?= $v['id_venta'] == $detalle['id_venta'] ? 'selected' : '' ?>>
                Venta #<?= $v['id_venta'] ?>
            </option>
        <?php endwhile; ?>
    </select>

    <label>Libro</label>
    <select class="form-control mb-2" name="id_libro">
        <?php while ($l = $libros->fetch_assoc()): ?>
            <option value="<?= $l['id_libro'] ?>"
                <?= $l['id_libro'] == $detalle['id_libro'] ? 'selected' : '' ?>>
                <?= $l['titulo'] ?>
            </option>
        <?php endwhile; ?>
    </select>

    <label>Cantidad</label>
    <input class="form-control mb-2" name="cantidad" type="number"
           value="<?= $detalle['cantidad'] ?>">

    <label>Precio Unitario</label>
    <input class="form-control mb-2" name="precio_unitario" type="number" step="0.01"
           value="<?= $detalle['precio_unitario'] ?>">

    <button class="btn btn-primary">Actualizar</button>
</form>

<?php require_once "../includes/footer.php"; ?>
