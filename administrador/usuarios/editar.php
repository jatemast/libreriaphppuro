<?php
require_once "../includes/header.php";
require_once "../includes/sidebar.php";
require_once __DIR__ . '/../../logica/conexion.php';
// --- VALIDAR ID ---
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "<script>alert('ID inválido'); window.location='index.php';</script>";
    exit;
}

$id = intval($_GET['id']);

// --- OBTENER USUARIO ---
$sql = $conexion->query("SELECT * FROM usuarios WHERE id_usuario = $id");

if ($sql->num_rows == 0) {
    echo "<script>alert('El usuario no existe'); window.location='index.php';</script>";
    exit;
}

$user = $sql->fetch_assoc();

// --- PROCESAR ACTUALIZACIÓN ---
if ($_POST) {

    $nombre = $conexion->real_escape_string($_POST['nombre']);
    $apellido = $conexion->real_escape_string($_POST['apellido']);
    $rol = intval($_POST['id_rol']);

    // Si se quiere cambiar la contraseña
    if (!empty($_POST['password'])) {
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $conexion->query("
            UPDATE usuarios 
            SET nombre='$nombre', apellido='$apellido', id_rol=$rol, password='$password'
            WHERE id_usuario=$id
        ");
    } else {
        // Sin cambiar contraseña
        $conexion->query("
            UPDATE usuarios 
            SET nombre='$nombre', apellido='$apellido', id_rol=$rol
            WHERE id_usuario=$id
        ");
    }

    echo "<script>alert('Usuario actualizado correctamente'); window.location='index.php';</script>";
    exit;
}
?>

<h2>Editar Usuario</h2>

<form method="POST">

    <label>Nombre:</label>
    <input class="form-control mb-2" name="nombre" value="<?= htmlspecialchars($user['nombre']) ?>" required>

    <label>Apellido:</label>
    <input class="form-control mb-2" name="apellido" value="<?= htmlspecialchars($user['apellido']) ?>">

    <label>Rol:</label>
    <select class="form-control mb-2" name="id_rol" required>
        <option value="1" <?= $user['id_rol'] == 1 ? 'selected' : '' ?>>Administrador</option>
        <option value="2" <?= $user['id_rol'] == 2 ? 'selected' : '' ?>>Usuario</option>
    </select>

    <label>Nueva contraseña (opcional):</label>
    <input type="password" class="form-control mb-3" name="password">

    <button class="btn btn-primary">Actualizar</button>
</form>

<?php require_once "../includes/footer.php"; ?>
