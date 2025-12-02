<?php
require_once "../includes/header.php";
require_once "../includes/sidebar.php";
require_once __DIR__ . '/../../logica/conexion.php';

$result = $conexion->query("SELECT * FROM categorias");
?>

<h2>Categorías</h2>
<a href="crear.php" class="btn btn-primary mb-3">Agregar Categoría</a>

<table class="table table-striped table-bordered">
    <tr>
        <th>ID</th>
        <th>Nombre</th>
        <th>Descripción</th>
        <th>Acciones</th>
    </tr>

    <?php while($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $row['id_categoria'] ?></td>
            <td><?= $row['nombre'] ?></td>
            <td><?= $row['descripcion'] ?></td>

            <td>
                <a href="editar.php?id=<?= $row['id_categoria'] ?>" class="btn btn-warning btn-sm">Editar</a>
                <a href="eliminar.php?id=<?= $row['id_categoria'] ?>" class="btn btn-danger btn-sm">Eliminar</a>
            </td>
        </tr>
    <?php endwhile; ?>
</table>

<?php require_once "../includes/footer.php"; ?>
