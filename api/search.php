<?php
require_once 'includes/db.php';
$q = isset($_GET['q']) ? "%".$mysqli->real_escape_string($_GET['q'])."%" : "%";
$sql = "SELECT id_libro, titulo, descripcion, precio FROM libros WHERE titulo LIKE ? OR descripcion LIKE ? LIMIT 100";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("ss",$q,$q);
$stmt->execute();
$res = $stmt->get_result();
$rows=[]; while($r=$res->fetch_assoc()) $rows[]=$r;
send_json(["success"=>true,"books"=>$rows]);
