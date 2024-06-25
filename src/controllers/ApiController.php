<?php
// src/controllers/AdminController.php
include __DIR__ . '/../config/config.php';

class ApiController {
    public function getUsers() {
        global $conn;
        $stmt = $conn->query("SELECT * FROM clientes");
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($users);
    }

    public function getUser($id) {
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

    public function createUser() {
        global $conn;
        $data = json_decode(file_get_contents("php://input"), true);
        if ($this->validateUserData($data)) {
            $stmt = $conn->prepare("INSERT INTO clientes (telefono_movil, nombre, apellidos, direccion, correo_electronico, estado, ciudad, puntos, rol, contrasena, numero_tarjeta) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([
                $data['telefono_movil'], $data['nombre'], $data['apellidos'], $data['direccion'],
                $data['correo_electronico'], $data['estado'], $data['ciudad'], $data['puntos'],
                'cliente', password_hash($data['contrasena'], PASSWORD_DEFAULT), $this->generateCardNumber()
            ]);
            http_response_code(201);
            echo json_encode(["message" => "User created"]);
        } else {
            http_response_code(400);
            echo json_encode(["message" => "Invalid user data"]);
        }
    }

    public function updateUser($id) {
        global $conn;
        $data = json_decode(file_get_contents("php://input"), true);
        if ($this->validateUserData($data, false)) {
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

    public function deleteUser($id) {
        global $conn;
        $stmt = $conn->prepare("DELETE FROM clientes WHERE id_cliente = ?");
        $stmt->execute([$id]);
        echo json_encode(["message" => "User deleted"]);
    }

    private function validateUserData($data, $isNew = true) {
        $requiredFields = ['telefono_movil', 'nombre', 'apellidos', 'correo_electronico', 'contrasena'];
        foreach ($requiredFields as $field) {
            if ($isNew && empty($data[$field])) {
                return false;
            }
        }
        return true;
    }

    private function generateCardNumber() {
        return substr(str_shuffle(str_repeat("0123456789", 8)), 0, 8);
    }
}
