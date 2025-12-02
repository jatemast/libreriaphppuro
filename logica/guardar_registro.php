<?php
session_start();

// Conexión a MySQL
require __DIR__ . '/../logica/conexion.php';
//$conexion = new mysqli("localhost", "root", "", "libreria");

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Validar datos enviados por POST
if (!isset($_POST['email']) || !isset($_POST['password'])) {
    echo "<h3>Error: faltan datos del formulario.</h3>";
    echo "<a href='logica/login.php'>Volver</a>";
    exit;
}

$email = trim($_POST['email']);
$password = trim($_POST['password']);

// Verificar que no estén vacíos
if ($email === "" || $password === "") {
    echo "<h3>Error: debes llenar todos los campos.</h3>";
    echo "<a href='login.php'>Volver</a>";
    exit;
}

// Buscar usuario por email
$sql = "SELECT * FROM usuarios WHERE email = ?";
$stmt = $conexion->prepare($sql);

if (!$stmt) {
    die("Error al preparar la consulta: " . $conexion->error);
}

$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

// Verificar si existe el usuario
if ($result->num_rows === 0) {
    echo "<script>
    alert('El usuario no está registrado.');
    window.location.href = 'login.php';
</script>";
exit;
}

$usuario = $result->fetch_assoc();

// Verificar contraseña
if (!password_verify($password, $usuario['password'])) {
    echo "<script>
    alert('Contraseña incorrecta.');
    window.location.href = 'login.php';
</script>";
exit;
    exit;
}

//CODIGO PARA LA PARTE DE GUARDAR EL ROL DE USUARIO PARA CLASIFICARLO SEGUN EL ROL
// Guardar datos de sesión ANTES de redirigir
//$_SESSION['id_usuario'] = $usuario['id_usuario'];
//$_SESSION['nombre'] = $usuario['nombre'];
//$_SESSION['rol'] = $usuario['id_rol'];

// Redirigir a la pantalla del 2FA
//header("Location: verificar_2fa.php");
//exit;

$sql = "SELECT * FROM usuarios WHERE email = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "<h3>El usuario no está registrado.</h3>";
    echo "<a href='registro.php'>Crear una cuenta nueva</a>";
    exit;
}

$usuario = $result->fetch_assoc();

// Verificar contraseña
if (!password_verify($password, $usuario['password'])) {
    echo "<h3>Contraseña incorrecta.</h3>";
    echo "<a href='login.php'>Volver al login</a>";
    exit;
}

// Guardar datos de sesión
$_SESSION['id_usuario'] = $usuario['id_usuario'];
$_SESSION['nombre'] = $usuario['nombre'];
$_SESSION['rol'] = $usuario['id_rol'];

// Mostrar mensaje y redirigir
echo "<script>
    alert('¡Bienvenido, " . $usuario['nombre'] . "!');
    window.location.href = '" . ($usuario['id_rol'] == 1 ? "../index.php" : "../index.php") . "';
</script>";
exit;

// Redirigir según el rol
if ($usuario['id_rol'] == 1) {
    header("Location: admin_panel.php");
} else {
    header("Location: usuario_panel.php");
}

exit;
?>
