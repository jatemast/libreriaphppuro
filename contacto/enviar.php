<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require __DIR__ . '/../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $nombre = $_POST["nombre"];
    $correo = $_POST["correo"];
    $mensaje = $_POST["mensaje"];

    $mail = new PHPMailer(true);

    try {
        // CONFIGURACIÓN SMTP
        $mail->isSMTP();
        $mail->Host = 'smtp.hostinger.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'admin@bibliotecaitesg.com';
        $mail->Password = 'ProgramacionWeb2025!'; 
        $mail->SMTPSecure = 'ssl';
        $mail->Port = 465;

        // REMITENTE Y DESTINO
        $mail->setFrom('admin@bibliotecaitesg.com', 'Biblioteca ITESG');
        $mail->addAddress('admin@bibliotecaitesg.com'); // Enviar a tu correo

        $mail->addReplyTo($correo, $nombre); // El usuario puede recibir respuesta

        // CONTENIDO
        $mail->isHTML(true);
        $mail->Subject = 'Nuevo mensaje desde la página web';
        $mail->Body = "
            <strong>Nombre:</strong> $nombre <br>
            <strong>Correo:</strong> $correo <br><br>
            <strong>Mensaje:</strong><br>
            $mensaje
        ";

        // ENVIAR
        $mail->send();
        echo "OK";
    } catch (Exception $e) {
        echo "ERROR";
    }
}
?>
