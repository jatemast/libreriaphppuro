<?php
require_once 'includes/db.php';
$data = get_json_body();
$token = $data['token'] ?? null;
$direccion = $mysqli->real_escape_string($data['direccion'] ?? '');
$lat = isset($data['lat']) ? floatval($data['lat']) : null;
$lng = isset($data['lng']) ? floatval($data['lng']) : null;
if (!$token) send_json(["success"=>false,"message"=>"Token requerido"]);

$sql = "UPDATE usuarios SET direccion = ?, lat = ?, lng = ? WHERE api_token = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("sdds", $direccion, $lat, $lng, $token);
$stmt->execute();

if ($stmt->affected_rows >= 0) send_json(["success"=>true,"message"=>"Dirección guardada"]);
else send_json(["success"=>false,"message"=>"Error"]);
