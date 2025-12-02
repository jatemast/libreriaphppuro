<?php
require_once "../includes/header.php";
require_once "../includes/sidebar.php";
require_once __DIR__ . '/../../logica/conexion.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $nombre   = trim($_POST['nombre']);
    $apellido = trim($_POST['apellido']);
    $email    = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $rol      = intval($_POST['id_rol']);

  
    $check = $conexion->prepare("SELECT id_usuario FROM usuarios WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        echo "<script>alert('El correo ya está registrado');</script>";
    } else {

        
        $stmt = $conexion->prepare("
            INSERT INTO usuarios (id_rol, nombre, apellido, email, password) 
            VALUES (?, ?, ?, ?, ?)
        ");

        $stmt->bind_param("issss", $rol, $nombre, $apellido, $email, $password);

        if ($stmt->execute()) {
            header("Location: index.php");
            exit;
        } else {
            echo "<div class='alert alert-danger'>Error al guardar el usuario.</div>";
        }

        $stmt->close();
    }

    $check->close();
}
?>

<h2>Crear Usuario</h2>

<form method="POST">

    <input class="form-control mb-2" name="nombre" placeholder="Nombre" required>

    <input class="form-control mb-2" name="apellido" placeholder="Apellido">

    <input class="form-control mb-2" name="email" placeholder="Email" 
           type="email" required>

    <input class="form-control mb-2" name="password" placeholder="Password" 
           type="password" required>

    <select class="form-control mb-2" name="id_rol" required>
        <option value="1">Administrador</option>
        <option value="2">Cliente</option>
    </select>

    <button class="btn btn-success">Guardar</button>
</form>

<?php require_once "../includes/footer.php"; ?>
