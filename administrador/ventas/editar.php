<?php
require_once "../includes/header.php";
require_once "../includes/sidebar.php";

$id = $_GET['id'];

$venta = $conexion->query("
    SELECT * FROM ventas WHERE id_venta=$id
")->fetch_assoc();

$usuarios = $conexion->query("SELECT * FROM usuarios");

if ($_POST) {
    $id_usuario = $_POST['id_usuario'];
    $total = $_POST['total'];

    $conexion->query("
        UPDATE ventas
        SET id_usuario=$id_usuario, total=$total
        WHERE id_venta=$id
    ");

    header("Location: index.php");
}
?>

<h2>Editar Venta</h2>

<form method="POST">
    <label>Usuario</label>
    <select class="form-control mb-2" name="id_usuario">
        <?php while ($u = $usuarios->fetch_assoc()): ?>
            <option value="<?= $u['id_usuario'] ?>"
                <?= ($u['id_usuario'] == $venta['id_usuario']) ? 'selected' : '' ?>>
                <?= $u['nombre'] . " " . $u['apellido'] ?>
            </option>
        <?php endwhile; ?>
    </select>

    <label>Total</label>
    <input class="form-control mb-2" type="number" step="0.01"
           name="total" value="<?= $venta['total'] ?>" required>

    <button class="btn btn-primary">Actualizar</button>
</form>

<?php require_once "../includes/footer.php"; ?>
