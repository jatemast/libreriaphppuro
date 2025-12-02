<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once "../includes/auth.php"; 
require_once __DIR__ . '/../../logica/conexion.php';

// Validar que llega un ID válido
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "<script>alert('ID inválido'); window.location='index.php';</script>";
    exit;
}

$id_usuario = intval($_GET['id']);

// Verificar si el usuario existe
$consulta = $conexion->query("SELECT * FROM usuarios WHERE id_usuario = $id_usuario");

if ($consulta->num_rows === 0) {
    echo "<script>alert('El usuario no existe'); window.location='index.php';</script>";
    exit;
}

// Eliminar usuario
$conexion->query("DELETE FROM usuarios WHERE id_usuario = $id_usuario");

// Redirigir al listado
header("Location: index.php");
exit;
?>
