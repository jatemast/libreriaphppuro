<?php
require_once "../includes/auth.php";
require_once __DIR__ . '/../../logica/conexion.php';


$id = $_GET['id'];

$conexion->query("DELETE FROM detalle_venta WHERE id_detalle=$id");

header("Location: index.php");
