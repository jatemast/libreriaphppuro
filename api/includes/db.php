<?php
// includes/db.php
header("Content-Type: application/json; charset=UTF-8");

$DB_HOST = "mysql.darkorange-goose-704475.hostingersite.com";          // Hostinger por lo general usa localhost
$DB_USER = "u550789311_android12";      // ajustar
$DB_PASS = "Carlos12@12";     // ajustar
$DB_NAME = "u550789311_libreria";       // ajustar

$mysqli = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
if ($mysqli->connect_errno) {
    http_response_code(500);
    echo json_encode(["success" => false, "message" => "Database connection failed", "error" => $mysqli->connect_error]);
    exit;
}
$mysqli->set_charset("utf8mb4");

// helper: fetch body json
function get_json_body() {
    $json = file_get_contents('php://input');
    return json_decode($json, true);
}

// helper: send json
function send_json($arr) {
    echo json_encode($arr, JSON_UNESCAPED_UNICODE);
    exit;
}
?>
