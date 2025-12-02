<?php
session_start();
//CODIGO QUE VA A VERIFICAR EL ADMIN

// Si no está logeado → afuera
if (!isset($_SESSION['id_usuario'])) {
    header("Location: /login.php");
    exit;
}

// Si no es administrador → afuera
if ($_SESSION['rol'] != 1) { 
    echo "<h2>No tienes permiso para acceder a esta página.</h2>";
    echo "<a href='/index.php'>Volver al inicio</a>";
    exit;
}
?>