
<?php
header('Content-Type: application/json');
include "conexion.php";

$data = json_decode(file_get_contents("php://input"), true);

$email = $data["email"];
$password = $data["password"];

$stmt = $conexion->prepare(
    "SELECT id, nombre, password FROM usuarios WHERE email = ? LIMIT 1"
);
$stmt->bind_param("s", $email);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();

    if (password_verify($password, $user["password"])) {
        echo json_encode([
            "status" => "OK",
            "id" => $user["id"],
            "nombre" => $user["nombre"]
        ]);
    } else {
        echo json_encode(["status" => "ERROR", "message" => "Contraseña incorrecta"]);
    }
} else {
    echo json_encode(["status" => "ERROR", "message" => "Usuario no encontrado"]);
}
