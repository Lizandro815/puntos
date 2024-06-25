<?php
// public/hash_passwords.php
include '../src/config/config.php';

$stmt = $conn->query("SELECT * FROM Clientes");
$clientes = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($clientes as $cliente) {
    if (!password_verify('123456', $cliente['contrasena'])) {  // Ajusta esto según sea necesario
        $hashed_password = password_hash($cliente['contrasena'], PASSWORD_BCRYPT);
        $update_stmt = $conn->prepare("UPDATE Clientes SET contrasena = ? WHERE id_cliente = ?");
        $update_stmt->execute([$hashed_password, $cliente['id_cliente']]);
    }
}

echo "Contraseñas actualizadas correctamente.";
?>
