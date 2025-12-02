<?php
require_once 'includes/db.php';
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if (!$id) send_json(["success"=>false,"message"=>"id requerido"]);

$sql = "SELECT l.*, c.nombre as categoria, a.nombre as autor_nombre, a.apellido as autor_apellido FROM libros l
        LEFT JOIN categorias c ON l.id_categoria = c.id_categoria
        LEFT JOIN autores a ON l.id_autor = a.id_autor
        WHERE l.id_libro = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("i",$id);
$stmt->execute();
$res = $stmt->get_result();
if ($res->num_rows === 0) send_json(["success"=>false,"message"=>"No encontrado"]);
$book = $res->fetch_assoc();

// si tienes imagen guardada en longblob, mejor servirla por separado; aquí devolvemos meta
send_json(["success"=>true,"book"=>$book]);
