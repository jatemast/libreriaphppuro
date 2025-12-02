<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro</title>
    <link rel="stylesheet" href="../src/registro.css" />
</head>

<body>
<div class="login-container" id="formBox">
    <h2>Crear cuenta</h2>

    <form id="registerForm" action="procesar_registro.php" method="POST" class="register-card">

        <input type="text" name="nombre" id="nombre" placeholder="Nombre" required>

        <input type="text" name="apellido" id="apellido" placeholder="Apellido" required>

        <input type="email" name="email" id="email" placeholder="Correo electrónico" required>

        <input type="text" name="direccion" id="direccion" placeholder="Dirección" required>

        <input type="text" name="telefono" id="telefono" placeholder="Teléfono" required>

        <input type="password" name="password" id="password" placeholder="Contraseña" required>

        <input type="password" id="password_confirm" placeholder="Confirmar contraseña" required>

        <button type="submit" class="btn-register">Registrarme</button>
    </form>

    <div class="login-link">
        <a href="/libreria/logica/login.php">¿Ya tienes cuenta? Inicia sesión</a>
    </div>
</div>
 <script src="../js/login.js"></script>

</body>
</html>
