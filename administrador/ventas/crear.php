<?php
require_once "../includes/header.php";
require_once "../includes/sidebar.php";

$usuarios = $conexion->query("SELECT * FROM usuarios");

if ($_POST) {
    $id_usuario = $_POST['id_usuario'];
    $total = $_POST['total'];

    $conexion->query("
        INSERT INTO ventas (id_usuario, total)
        VALUES ($id_usuario, $total)
    ");

    header("Location: index.php");
}
?>

<h2>Crear Venta</h2>

<form method="POST">
    <label>Usuario</label>
    <select class="form-control mb-2" name="id_usuario">
        <?php while ($u = $usuarios->fetch_assoc()): ?>
            <option value="<?= $u['id_usuario'] ?>">
                <?= $u['nombre'] . " " . $u['apellido'] ?>
            </option>
        <?php endwhile; ?>
    </select>

    <label>Total</label>
    <input class="form-control mb-2" type="number" step="0.01" name="total" required>

    <button class="btn btn-success">Guardar</button>
</form>

<?php require_once "../includes/footer.php"; ?>
