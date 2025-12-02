require_once 'includes/db.php';
$res = $mysqli->query("SELECT id_autor, nombre, apellido FROM autores ORDER BY nombre");
$rows = []; while($r=$res->fetch_assoc()) $rows[]=$r;
send_json(["success"=>true,"authors"=>$rows]);