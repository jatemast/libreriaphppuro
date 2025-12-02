require_once 'includes/db.php';
$res = $mysqli->query("SELECT id_categoria, nombre FROM categorias ORDER BY nombre");
$rows = []; while($r=$res->fetch_assoc()) $rows[]=$r;
send_json(["success"=>true,"categories"=>$rows]);