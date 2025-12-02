<?php
require_once "../includes/auth.php";
require_once __DIR__ . '/../../logica/conexion.php';


$id = $_GET['id'];

// Eliminar categoría
$conexion->query("DELETE FROM categorias WHERE id_categoria = $id");

header("Location: index.php");
