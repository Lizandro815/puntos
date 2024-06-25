<?php
// public/create_admin.php
include '../src/config/config.php';

$telefono_movil = '1234567890';
$nombre = 'Admin';
$correo_electronico = 'admin@example.com';
$contrasena = password_hash('adminpassword', PASSWORD_BCRYPT);

try {
    // Insertar en la tabla Administradores
    $stmt = $conn->prepare("INSERT INTO Administradores (telefono_movil, nombre, correo_electronico, rol) VALUES (?, ?, ?, 'admin')");
    $stmt->execute([$telefono_movil, $nombre, $correo_electronico]);

    // Obtener el id_administrador recién insertado
    $id_administrador = $conn->lastInsertId();

    // Insertar en la tabla Admin_Auth
    $stmt = $conn->prepare("INSERT INTO Admin_Auth (id_administrador, contrasena) VALUES (?, ?)");
    $stmt->execute([$id_administrador, $contrasena]);

    echo "Administrador creado con éxito.";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
