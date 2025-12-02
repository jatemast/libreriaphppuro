<?php
//ini_set('display_errors', 1);
//error_reporting(E_ALL);
//echo "API FUNCIONA";

header('Content-Type: application/json');

$host = "localhost";
$user = "root";
$password = "";
$database = "biblioteca2";

$conexion = new mysqli($host, $user, $password, $database);

if ($conexion->connect_error) {
    echo json_encode([
        "status" => "ERROR",
        "message" => "Error de conexión a la base de datos"
    ]);
    exit;
}

// Charset correcto
$conexion->set_charset("utf8mb4");
