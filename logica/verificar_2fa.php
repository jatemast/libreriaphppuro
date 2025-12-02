<?php
session_start();

// Evitar acceso sin tener el código generado
if (!isset($_SESSION['registro_temp']['codigo']) || !isset($_SESSION['registro_temp']['email'])) {
    header("Location: registro.php");
    exit;
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Verificación en Dos Pasos</title>
    <link rel="stylesheet" href="/libreria/src/2fa.css" />
  
</head>
<body>

<div class="contenedor">
    <h2>Verificación en dos pasos</h2>
    <p>Se ha enviado un código a tu correo: <b><?php echo $_SESSION['2fa_email']; ?></b></p>

    <form action="verificar_2fa_procesar.php" method="POST">
        <input type="text" name="codigo" placeholder="Ingresa tu código" required>
        <button type="submit">Verificar</button>
    </form>
</div>
 <script src="/libreria/js/2fa.js"></script>

</body>
</html>
