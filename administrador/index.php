
<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require __DIR__ . '/../logica/conexion.php';
//require_once "../includes/validar_sesion_usuario.php";
require_once "includes/header.php";
require_once "includes/sidebar.php";
?>


<h1>Panel de Administración</h1>
<p>Bienvenido, <?php echo $_SESSION['nombre']; ?>. Selecciona una opción del menú lateral.</p>

<?php require_once "includes/footer.php"; ?>
