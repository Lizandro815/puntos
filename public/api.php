<?php
header("Content-Type: application/json");

include '../src/config/config.php'; // Ajusta la ruta si es necesario

$method = $_SERVER['REQUEST_METHOD'];
$path = isset($_GET['path']) ? explode('/', trim($_GET['path'], '/')) : [];

if (count($path) === 0) {
    http_response_code(404);
    echo json_encode(["message" => "Not found"]);
    exit();
}

$resource = $path[0];
$id = $path[1] ?? null;

switch ($resource) {
    case 'users':
        handleUsers($method, $id);
        break;
    default:
        http_response_code(404);
        echo json_encode(["message" => "Not found"]);
        break;
}

function handleUsers($method, $id) {
    global $conn;
    switch ($method) {
        case 'GET':
            if ($id) {
                getUser($id);
            } else {
                getUsers();
            }
            break;
        case 'POST':
            createUser();
            break;
        case 'PUT':
            if ($id) {
                updateUser($id);
            } else {
                http_response_code(400);
                echo json_encode(["message" => "ID is required for updating"]);
            }
            break;
        case 'DELETE':
            if ($id) {
                deleteUser($id);
            } else {
                http_response_code(400);
                echo json_encode(["message" => "ID is required for deleting"]);
            }
            break;
        default:
            http_response_code(405);
            echo json_encode(["message" => "Method not allowed"]);
            break;
    }
}

function getUsers() {
    global $conn;
    $stmt = $conn->query("SELECT * FROM clientes");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($users);
}

function getUser($id) {
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM clientes WHERE id_cliente = ?");
    $stmt->execute([$id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($user) {
        echo json_encode($user);
    } else {
        http_response_code(404);
        echo json_encode(["message" => "User not found"]);
    }
}

function createUser() {
    global $conn;
    $data = json_decode(file_get_contents("php://input"), true);
    if (validateUserData($data)) {
        $stmt = $conn->prepare("INSERT INTO clientes (telefono_movil, nombre, apellidos, direccion, correo_electronico, estado, ciudad, puntos, rol, contrasena, numero_tarjeta) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $data['telefono_movil'], $data['nombre'], $data['apellidos'], $data['direccion'],
            $data['correo_electronico'], $data['estado'], $data['ciudad'], $data['puntos'],
            'cliente', password_hash($data['contrasena'], PASSWORD_DEFAULT), generateCardNumber()
        ]);
        http_response_code(201);
        echo json_encode(["message" => "User created"]);
    } else {
        http_response_code(400);
        echo json_encode(["message" => "Invalid user data"]);
    }
}

function updateUser($id) {
    global $conn;
    $data = json_decode(file_get_contents("php://input"), true);
    if (validateUserData($data, false)) {
        $stmt = $conn->prepare("UPDATE clientes SET telefono_movil = ?, nombre = ?, apellidos = ?, direccion = ?, correo_electronico = ?, estado = ?, ciudad = ?, puntos = ? WHERE id_cliente = ?");
        $stmt->execute([
            $data['telefono_movil'], $data['nombre'], $data['apellidos'], $data['direccion'],
            $data['correo_electronico'], $data['estado'], $data['ciudad'], $data['puntos'], $id
        ]);
        echo json_encode(["message" => "User updated"]);
    } else {
        http_response_code(400);
        echo json_encode(["message" => "Invalid user data"]);
    }
}

function deleteUser($id) {
    global $conn;
    $stmt = $conn->prepare("DELETE FROM clientes WHERE id_cliente = ?");
    $stmt->execute([$id]);
    echo json_encode(["message" => "User deleted"]);
}

function validateUserData($data, $isNew = true) {
    $requiredFields = ['telefono_movil', 'nombre', 'apellidos', 'correo_electronico', 'contrasena'];
    foreach ($requiredFields as $field) {
        if ($isNew && empty($data[$field])) {
            return false;
        }
    }
    return true;
}

function generateCardNumber() {
    return substr(str_shuffle(str_repeat("0123456789", 8)), 0, 8);
}
?>
