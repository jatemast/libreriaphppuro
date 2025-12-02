<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

require __DIR__ . '/../logica/conexion.php';

// Validar que existe el código generado
if (!isset($_SESSION['registro_temp']['codigo'])) {
    echo "<h3>Error: no se generó un código 2FA.</h3>";
    echo "<a href='registro.php'>Volver</a>";
    exit;
}

$codigo_ingresado = $_POST['codigo'] ?? '';
$codigo_correcto  = $_SESSION['registro_temp']['codigo'];

// Comparar códigos
if ($codigo_ingresado == $codigo_correcto) {

    // Código correcto → Redirigir al registro final
    // Si el código es correcto, registramos el usuario en la BD
//$conexion = new mysqli("localhost", "root", "", "libreria");

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

$datos = $_SESSION['registro_temp'];

// Encriptar contraseña
$pass_segura = password_hash($datos['password'], PASSWORD_DEFAULT);

// Insertar usuario
$sql = "INSERT INTO usuarios (nombre, apellido, email, direccion, telefono, password, id_rol)
        VALUES (?, ?, ?, ?, ?, ?, 2)";

$stmt = $conexion->prepare($sql);
$stmt->bind_param(
    "ssssss",
    $datos['nombre'],
    $datos['apellido'],
    $datos['email'],
    $datos['direccion'],
    $datos['telefono'],
    $pass_segura
);

if ($stmt->execute()) {
    echo "<h3>Registro completado con éxito.</h3>";
    echo "<a href='login.php'>Iniciar sesión</a>";
    // Borrar datos temporales
    unset($_SESSION['registro_temp']);
} else {
    echo "<h3>Error al registrar al usuario: " . $stmt->error . "</h3>";
}
    echo "<script>
            alert('Código verificado correctamente');
            window.location='login.php';
          </script>";
    exit;

} else {

    // Código incorrecto → Volver a pantalla 2FA
    echo "<script>
            alert('El código es incorrecto, inténtalo de nuevo');
            window.location='verificar_2fa.php';
          </script>";
    exit;

}

?>
