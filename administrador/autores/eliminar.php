<?php
require_once "../includes/auth.php";
require_once __DIR__ . '/../../logica/conexion.php';

// Validar que llega un ID válido
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "<script>alert('ID inválido'); window.location='index.php';</script>";
    exit;
}


$id_autor = intval($_GET['id']);


$consulta = $conexion->query("SELECT * FROM autores WHERE id_autor = $id_autor");

if ($consulta->num_rows === 0) {
    echo "<script>alert('El usuario no existe'); window.location='index.php';</script>";
    exit;
}

$conexion->query("DELETE FROM autores WHERE id_autor = $id_autor");

header("Location: index.php");
exit;
?>
