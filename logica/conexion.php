<?php
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);

// CONFIGURACIÓN CORRECTA PARA HOSTINGER
$host = "localhost";
$user = "u550789311_android12";
$password = "Carlos12@12";
$database = "u550789311_libreria";

$conexion = new mysqli($host, $user, $password, $database);

if ($conexion->connect_error) {
    die("Error de conexión a la base de datos: " . $conexion->connect_error);
}

// Configurar charset
$conexion->set_charset("utf8");
?>