<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");

$host = "localhost";
$user = "apiuser";
$password = "1234";
$dbname = "sistema_de_ventas"; // Tu base de datos

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    die(json_encode(["success" => false, "message" => "Error de conexión: " . $conn->connect_error]));
}

// Aseguramos que el método sea POST y se reciba JSON
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["success" => false, "message" => "Método no permitido"]);
    exit;
}

$api = isset($_GET['api']) ? $_GET['api'] : '';
$data = json_decode(file_get_contents("php://input"));
file_put_contents("debug_data.txt", var_export($data, true));

// DEBUG TEMPORAL
file_put_contents("debug.txt", "API = " . $api);

switch ($api) {
    case 'Register':
        if (!isset($data->usuario) || !isset($data->contraseña)) {
            echo json_encode(["success" => false, "message" => "Faltan datos"]);
            exit;
        }

        $usuario = $data->usuario;
        $contraseña = password_hash($data->contraseña, PASSWORD_DEFAULT);

        $sql = "INSERT INTO usuarios (usuario, contraseña) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $usuario, $contraseña);

        if ($stmt->execute()) {
            echo json_encode(["success" => true, "message" => "Usuario registrado correctamente"]);
        } else {
            echo json_encode(["success" => false, "message" => "Error al registrar usuario"]);
        }
        break;

    case 'Login':
        if (!isset($data->usuario) || !isset($data->contraseña)) {
            echo json_encode(["success" => false, "message" => "Faltan datos"]);
            exit;
        }

        $usuario = $data->usuario;
        $contraseña = $data->contraseña;

        $sql = "SELECT contraseña FROM usuarios WHERE usuario = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $usuario);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows === 1) {
            $stmt->bind_result($hash);
            $stmt->fetch();

            if (password_verify($contraseña, $hash)) {
                echo json_encode(["success" => true, "message" => "Autenticación satisfactoria"]);
            } else {
                echo json_encode(["success" => false, "message" => "Contraseña incorrecta"]);
            }
        } else {
            echo json_encode(["success" => false, "message" => "Usuario no encontrado"]);
        }
        break;

    default:
        echo json_encode(["success" => false, "message" => "Operación no válida"]);
        break;
}
?>
