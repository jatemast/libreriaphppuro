<?php
//NOs ayuda aver los errores en tiempo real
//error_reporting(E_ALL);
//ini_set('display_errors', 1);
session_start();
//Importamos la libreria para que valide el correo
require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../logica/conexion.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Conexión
//$conexion = new mysqli("localhost", "root", "", "libreria");

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Recibir datos del formulario
$nombre     = $_POST['nombre'] ?? '';
$apellido   = $_POST['apellido'] ?? '';
$email      = $_POST['email'] ?? '';
$direccion  = $_POST['direccion'] ?? '';
$telefono   = $_POST['telefono'] ?? '';
$password   = $_POST['password'] ?? '';

// Validación básica
if ($nombre === '' || $email === '' || $password === '' || $direccion === '' || $telefono === '') {
     echo "<script>
    alert('Faltan Datos.');
    window.location.href = 'registro.php';
</script>";
exit;
}

// Verificar si el correo ya existe
$sql = "SELECT * FROM usuarios WHERE email = ?";
$stmt = $conexion->prepare($sql);

if (!$stmt) {
    die("Error en prepare: " . $conexion->error);
}

$stmt->bind_param("s", $email);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows > 0) {
     echo "<script>
    alert('El usuario ya esta resgistrado.');
    window.location.href = 'login.php';
</script>";
exit;
}

// --- GENERAR CÓDIGO DE VERIFICACIÓN (aseguramos que $codigo exista) ---
$codigo = random_int(100000, 999999);

// Guardar datos en sesión temporal (no insertamos aún)
$_SESSION['registro_temp'] = [
    'nombre'    => $nombre,
    'apellido'  => $apellido,
    'email'     => $email,
    'direccion' => $direccion,
    'telefono'  => $telefono,
    'password'  => $password,
    'codigo'    => $codigo,
    'timestamp' => time()
];

// Enviar correo con PHPMailer
$mail = new PHPMailer(true);
try {
    //DATOS DEL DOMINIO
    //Agregamos una linea para ver los errores
    $mail->SMTPDebug = 2;
    $mail->isSMTP();
    $mail->Host       = 'smtp.hostinger.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'admin@bibliotecaitesg.com';
    $mail->Password   = 'ProgramacionWeb2025!';
    $mail->SMTPSecure = 'ssl';
    $mail->Port       = 465;
    //DATOS DE  QUIEN RECIVE
    $mail->setFrom('admin@bibliotecaitesg.com', 'Librería');
    $mail->addAddress($email);

    $mail->isHTML(true);
    $mail->Subject = 'Código de verificación - Librería';
    $mail->Body = "
        <h3>Hola $nombre</h3>
        <p>Tu código de verificación es:</p>
        <h1>$codigo</h1>
        <p>Ingresa este código para continuar con el registro.</p>
    ";

    $mail->send();

    echo "<script>
    alert('Tu cuenta fue creada. Revisa tu correo para obtener el código de verificación.');
    window.location.href = 'verificar_2fa.php?email=$email';
    </script>";
    exit;

} catch (Exception $e) {
    echo "Error al enviar correo: " . $mail->ErrorInfo;
}

// Insertar usuario nuevo (contraseña encriptada)
//$pass_segura = password_hash($password, PASSWORD_DEFAULT);

//$sql = "INSERT INTO usuarios (nombre, apellido, email, direccion, telefono, password, id_rol)
  //      VALUES (?, ?, ?, ?, ?, ?, 1)";

//$stmt = $conexion->prepare($sql);

//if (!$stmt) {
  //  die("Error en prepare (insert): " . $conexion->error);
//}

//$stmt->bind_param("ssssss", $nombre, $apellido, $email, $direccion, $telefono, $pass_segura);

//if ($stmt->execute()) {
  //  echo "<h3>Usuario registrado con éxito.</h3>";
    //echo "<a href='login.php'>Iniciar sesión</a>";
//} else {
  //  echo "<h3>Error al registrar usuario: " . $stmt->error . "</h3>";
//}

?>
