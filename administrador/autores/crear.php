<?php
require_once "../includes/header.php";
require_once "../includes/sidebar.php";
require_once __DIR__ . "/../../logica/conexion.php";  // Asegurar conexión a BD

// Si envían el formulario
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $nombre = $conexion->real_escape_string(trim($_POST['nombre']));
    $apellido = $conexion->real_escape_string(trim($_POST['apellido']));
    $pais = $conexion->real_escape_string(trim($_POST['pais']));

    
    if ($nombre === "") {
        echo "<script>alert('El nombre del autor es obligatorio');</script>";
    } else {

        // Insertar autor
        $conexion->query("
            INSERT INTO autores (nombre, apellido, pais)
            VALUES ('$nombre', '$apellido', '$pais')
        ");

        // Redirigir a lista de autores
        header("Location: index.php");
        exit;
    }
}
?>

<h2>Agregar Autor</h2>

<form method="POST">

    <input class="form-control mb-2"
           name="nombre"
           placeholder="Nombre"
           required>

    <input class="form-control mb-2"
           name="apellido"
           placeholder="Apellido">

    <input class="form-control mb-2"
           name="pais"
           placeholder="País">

    <button class="btn btn-success">Guardar</button>
</form>

<?php require_once "../includes/footer.php"; ?>
