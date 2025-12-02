<?php
require_once 'includes/db.php';

$limit = isset($_GET['limit']) ? intval($_GET['limit']) : 50;
$offset = isset($_GET['offset']) ? intval($_GET['offset']) : 0;

$sql = "SELECT l.id_libro, l.titulo, l.precio, l.stock, l.descripcion, c.nombre as categoria, a.nombre as autor, a.apellido as autor_apellido 
        FROM libros l
        LEFT JOIN categorias c ON l.id_categoria = c.id_categoria
        LEFT JOIN autores a ON l.id_autor = a.id_autor
        ORDER BY l.titulo LIMIT ?, ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("ii",$offset,$limit);
$stmt->execute();
$res = $stmt->get_result();
$rows = [];
while ($r = $res->fetch_assoc()) {
    $rows[] = $r;
}
send_json(["success"=>true,"books"=>$rows]);
