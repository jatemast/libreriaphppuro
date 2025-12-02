<?php
require_once 'includes/db.php';
$data = get_json_body();
$nombre = $mysqli->real_escape_string($data['nombre'] ?? '');
$email = $mysqli->real_escape_string($data['email'] ?? '');
$mensaje = $mysqli->real_escape_string($data['mensaje'] ?? '');

if (!$nombre || !$email || !$mensaje) send_json(["success"=>false,"message"=>"Faltan campos"]);

$to = "tu-correo@tudominio.com"; // ajustar
$subject = "Contacto App - $nombre";
$body = "Nombre: $nombre\nEmail: $email\n\nMensaje:\n$mensaje";
$headers = "From: $email\r\n";

if (mail($to, $subject, $body, $headers)) {
    send_json(["success"=>true,"message"=>"Mensaje enviado"]);
} else {
    send_json(["success"=>false,"message"=>"Error enviando correo"]);
}
