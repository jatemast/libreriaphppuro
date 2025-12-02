<?php
require_once 'includes/db.php';
$data = get_json_body();
$nombre = $mysqli->real_escape_string($data['nombre'] ?? '');
$apellido = $mysqli->real_escape_string($data['apellido'] ?? '');
$email = $mysqli->real_escape_string($data['email'] ?? '');
$password = $data['password'] ?? '';

if (!$nombre || !$email || !$password) send_json(["success"=>false,"message"=>"Faltan campos"]);

$q = $mysqli->prepare("SELECT id_usuario FROM usuarios WHERE email = ?");
$q->bind_param("s",$email); $q->execute();
if ($q->get_result()->num_rows > 0) send_json(["success"=>false,"message"=>"Email ya registrado"]);

$pass_hash = password_hash($password, PASSWORD_DEFAULT);
$token = bin2hex(random_bytes(32));
$insert = $mysqli->prepare("INSERT INTO usuarios (id_rol, nombre, apellido, email, password, api_token) VALUES (2,?,?,?,?,?)");
$insert->bind_param("ssss", $nombre, $apellido, $email, $pass_hash, $token);
$insert->execute();

if ($insert->affected_rows > 0) {
    $id = $insert->insert_id;
    send_json(["success"=>true,"message"=>"Registrado","user"=>["id_usuario"=>$id,"nombre"=>$nombre,"email"=>$email,"api_token"=>$token]]);
} else {
    send_json(["success"=>false,"message"=>"Error al crear usuario"]);
}
