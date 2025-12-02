<?php

require_once "../includes/header.php";
require_once "../includes/sidebar.php";
require_once __DIR__ . '/../../logica/conexion.php';



//Ventana para ver los errores
$result = $conexion->query("SELECT * FROM usuarios");
?>

<h2>Usuarios</h2>
<a href="crear.php" class="btn btn-primary mb-3">Agregar Usuario</a>

<table class="table table-striped table-bordered">
    <tr>
        <th>ID</th><th>Nombre</th><th>Email</th><th>Rol</th><th>Acciones</th>
    </tr>

    <?php while($row = $result->fetch_assoc()): ?>
    <tr>
        <td><?= $row['id_usuario'] ?></td>
        <td><?= $row['nombre'] . " " . $row['apellido'] ?></td>
        <td><?= $row['email'] ?></td>
        <td><?= $row['id_rol'] ?></td>
        <td>
            <a href="editar.php?id=<?= $row['id_usuario'] ?>" class="btn btn-warning btn-sm">Editar</a>
            <a href="eliminar.php?id=<?= $row['id_usuario'] ?>" class="btn btn-danger btn-sm">Eliminar</a>
        </td>
    </tr>
    <?php endwhile; ?>
</table>

<?php require_once "../includes/footer.php"; ?>
