<?php
require_once "../includes/auth.php";
require_once __DIR__ . '/../../logica/conexion.php';


$id = $_GET['id'];

$libro = $conexion->query("SELECT imagen FROM libros WHERE id_libro=$id")->fetch_assoc();

if ($libro['imagen'] && file_exists("/libreria/img/libros/" . $libro['imagen'])) {
    unlink("/libreria/img/libros/" . $libro['imagen']);
}

$conexion->query("DELETE FROM libros WHERE id_libro=$id");

header("Location: index.php");
