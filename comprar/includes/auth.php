<?php
//Validar que el usuario este logeado para continuar
if (!isset($_SESSION)) {
    session_start();
}

if (!isset($_SESSION['id_usuario'])) {
    header("Location: /libreria/logica/login.php");
    exit;
}
?>
