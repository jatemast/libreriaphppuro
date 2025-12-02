<?php
require_once 'includes/db.php';

$data = get_json_body();
$token = $data['token'] ?? null;
if (!$token) send_json(["success"=>false,"message"=>"Token requerido"]);

$fields = [];
$params = [];
$types = "";

$allowed = ['nombre','apellido','direccion','telefono','lat','lng','email','password'];
foreach ($allowed as $f) {
    if (isset($data[$f])) {
        if ($f === 'password') {
            $fields[] = "$f = ?";
            $params[] = password_hash($data[$f], PASSWORD_DEFAULT);
            $types .= "s";
        } else {
            $fields[] = "$f = ?";
            $params[] = $data[$f];
            $types .= "s";
        }
    }
}
if (count($fields) === 0) send_json(["success"=>false,"message"=>"Nada para actualizar"]);

$sql = "UPDATE usuarios SET ".implode(",", $fields)." WHERE api_token = ?";
$params[] = $token; $types .= "s";

$stmt = $mysqli->prepare($sql);
$stmt->bind_param($types, ...$params);
$stmt->execute();

if ($stmt->affected_rows >= 0) send_json(["success"=>true,"message"=>"Actualizado"]);
else send_json(["success"=>false,"message"=>"Error al actualizar"]);
