<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require __DIR__ . '/../logica/conexion.php';
//require_once "../includes/validar_sesion_usuario.php";
require_once "includes/header.php";
require_once "includes/sidebar.php";
?>

<h2>Bienvenido al Panel de Usuario</h2>
<p>Aquí puedes administrar tu cuenta y ver tus últimos movimientos.</p>

<?php require_once "includes/footer.php"; ?>
