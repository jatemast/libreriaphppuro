<?php
require_once "../includes/header.php";
require_once "../includes/sidebar.php";
require_once __DIR__ . '/../../logica/conexion.php';


$sql = "SELECT l.*, 
               a.nombre AS autor_nombre, a.apellido AS autor_apellido, 
               c.nombre AS categoria_nombre
        FROM libros l
        LEFT JOIN autores a ON l.id_autor = a.id_autor
        LEFT JOIN categorias c ON l.id_categoria = c.id_categoria
        ORDER BY l.id_libro DESC";

$result = $conexion->query($sql);
?>

<h2>Libros</h2>
<a href="crear.php" class="btn btn-primary mb-3">Agregar Libro</a>

<table class="table table-striped table-bordered">
    <tr>
        <th>ID</th>
        <th>Título</th>
        <th>Autor</th>
        <th>Categoría</th>
        <th>Editorial</th>
        <th>Precio</th>
        <th>Stock</th>
        <th>Publicación</th>
        <th>Acciones</th>
        <th>Imagen</th>
    </tr>

    <?php while($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $row['id_libro'] ?></td>
            <td><?= htmlspecialchars($row['titulo']) ?></td>
            <td><?= $row['autor_nombre'] . " " . $row['autor_apellido'] ?></td>
            <td><?= $row['categoria_nombre'] ?></td>
            <td><?= $row['editorial'] ?></td>
            <td>$<?= number_format($row['precio'], 2) ?></td>
            <td><?= $row['stock'] ?></td>
            <td><?= $row['fecha_publicacion'] ?></td>

            <td>
                <a href="editar.php?id=<?= $row['id_libro'] ?>" class="btn btn-warning btn-sm">Editar</a>
                <a onclick="return confirm('¿Eliminar libro?')" 
                   href="eliminar.php?id=<?= $row['id_libro'] ?>" 
                   class="btn btn-danger btn-sm">Eliminar</a>
            </td>

            <!-- Imagen -->
           <td>
                <a href="editar.php?id=<?= $row['id_libro'] ?>" class="btn btn-warning btn-sm">Editar</a>
                <a onclick="return confirm('¿Eliminar libro?')" 
                   href="eliminar.php?id=<?= $row['id_libro'] ?>" 
                   class="btn btn-danger btn-sm">Eliminar</a>
            </td>

        </tr>
    <?php endwhile; ?>
</table>

<?php require_once "../includes/footer.php"; ?>
