<?php
session_start();

ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . "/includes/auth.php";
require __DIR__ . '/../logica/conexion.php';
// PHPMailer
require_once "../vendor/autoload.php";
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Si el carrito está vacío, detener
if (!isset($_SESSION['carrito']) || empty($_SESSION['carrito'])) {
    echo "<script>alert('Tu carrito está vacío'); window.location='index.php';</script>";
    exit;
}

$id_usuario = $_SESSION['id_usuario'];

// Obtener datos del usuario
$usuario = $conexion->query("SELECT * FROM usuarios WHERE id_usuario=$id_usuario")->fetch_assoc();

// Calcular total y obtener datos de libros
$total = 0;
$carrito_detallado = [];

foreach ($_SESSION['carrito'] as $id_libro => $cantidad) {

    $q = $conexion->query("SELECT titulo, precio, stock FROM libros WHERE id_libro=$id_libro");
    $libro = $q->fetch_assoc();

    if (!$libro) continue;

    $precio = $libro['precio'];
    $subtotal = $precio * $cantidad;

    $total += $subtotal;

    $carrito_detallado[] = [
        "id" => $id_libro,
        "titulo" => $libro['titulo'],
        "precio" => $precio,
        "cantidad" => $cantidad,
        "subtotal" => $subtotal
    ];
}

// Registrar venta
$conexion->query("
    INSERT INTO ventas (id_usuario, fecha, total)
    VALUES ($id_usuario, NOW(), $total)
");

$id_venta = $conexion->insert_id;

// Registrar detalle de venta y actualizar stock
foreach ($carrito_detallado as $item) {

    $conexion->query("
        INSERT INTO detalle_ventas (id_venta, id_libro, cantidad, precio_unitario, subtotal)
        VALUES ($id_venta, {$item['id']}, {$item['cantidad']}, {$item['precio']}, {$item['subtotal']})
    ");

    // Descontar stock
    $conexion->query("
        UPDATE libros 
        SET stock = stock - {$item['cantidad']}
        WHERE id_libro = {$item['id']}
    ");
}

// Construcción del ticket
$ticket_html = "
<h2>Ticket de Compra - Librería</h2>
<p><strong>Cliente:</strong> {$usuario['nombre']} {$usuario['apellido']}</p>
<p><strong>Email:</strong> {$usuario['email']}</p>
<p><strong>Direccion:</strong> {$usuario['direccion']}</p>
<p><strong>Telefono:</strong> {$usuario['telefono']}</p>
<hr>

<table border='1' cellspacing='0' cellpadding='5' width='100%'>
<tr>
    <th>Libro</th>
    <th>Cant.</th>
    <th>Precio</th>
    <th>Subtotal</th>
</tr>
";

foreach ($carrito_detallado as $item) {
    $ticket_html .= "
    <tr>
        <td>{$item['titulo']}</td>
        <td>{$item['cantidad']}</td>
        <td>\${$item['precio']}</td>
        <td>\${$item['subtotal']}</td>
    </tr>";
}

$ticket_html .= "
</table>
<h3>Total pagado: \$$total</h3>
<p>Gracias por tu compra 😊</p>
";

// Enviar ticket por correo
try {
    $mail = new PHPMailer(true);

    $mail->isSMTP();
    $mail->Host = 'smtp.hostinger.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'admin@bibliotecaitesg.com';
    $mail->Password = 'ProgramacionWeb2025!';
    $mail->SMTPSecure = 'ssl';
    $mail->Port = 465;

    $mail->setFrom('admin@bibliotecaitesg.com', 'Librería');
    $mail->addAddress($usuario['email']);

    $mail->isHTML(true);
    $mail->Subject = "Tu ticket de compra – Librería";
    $mail->Body = $ticket_html;

    $mail->send();

} catch (Exception $e) {
    echo "Error al enviar ticket: {$mail->ErrorInfo}";
}

// Vaciar carrito
unset($_SESSION['carrito']);

echo "<script>
alert('Compra realizada con éxito. Ticket enviado a tu correo.');
window.location='index.php';
</script>";
?>
