<?php
require_once 'includes/db.php';

$headers = getallheaders();
$token = null;
if (isset($headers['Authorization'])) {
  $matches = [];
  if (preg_match('/Bearer\s(\S+)/', $headers['Authorization'], $matches)) $token = $matches[1];
}
if (!$token && isset($_GET['token'])) $token = $_GET['token'];
if (!$token) send_json(["success"=>false,"message"=>"Token requerido"]);

$q = $mysqli->prepare("SELECT id_usuario, id_rol, nombre, apellido, email, direccion, telefono, lat, lng FROM usuarios WHERE api_token = ?");
$q->bind_param("s",$token); $q->execute();
$res = $q->get_result();
if ($res->num_rows === 0) send_json(["success"=>false,"message"=>"Token inválido"]);
$user = $res->fetch_assoc();
send_json(["success"=>true,"user"=>$user]);
