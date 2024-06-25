<?php
// src/controllers/PremioController.php
include __DIR__ . '/../config/config.php';

function getPremios() {
    global $conn;
    $stmt = $conn->query("SELECT * FROM Premios");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function searchPremios($search) {
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM Premios WHERE nombre_premio LIKE ?");
    $search = "%$search%";
    $stmt->execute([$search]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function agregarPremio($nombre_premio, $descripcion, $puntos_necesarios, $cantidad_disponible, $imagen) {
    global $conn;
    $stmt = $conn->prepare("INSERT INTO Premios (nombre_premio, descripcion, puntos_necesarios, cantidad_disponible, imagen) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$nombre_premio, $descripcion, $puntos_necesarios, $cantidad_disponible, $imagen]);
}

function eliminarPremio($id_premio) {
    global $conn;
    $stmt = $conn->prepare("DELETE FROM Premios WHERE id_premio = ?");
    $stmt->execute([$id_premio]);
}

function actualizarPremio($id_premio, $nombre_premio, $descripcion, $puntos_necesarios, $cantidad_disponible, $imagen) {
    global $conn;
    $stmt = $conn->prepare("UPDATE Premios SET nombre_premio = ?, descripcion = ?, puntos_necesarios = ?, cantidad_disponible = ?, imagen = ? WHERE id_premio = ?");
    $stmt->execute([$nombre_premio, $descripcion, $puntos_necesarios, $cantidad_disponible, $imagen, $id_premio]);
}

function obtenerPremio($id_premio) {
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM Premios WHERE id_premio = ?");
    $stmt->execute([$id_premio]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function canjearPremio($id_premio, $id_cliente, $puntos_necesarios) {
    global $conn;
    try {
        $conn->beginTransaction();

        // Restar la cantidad de premios disponibles
        $stmt = $conn->prepare("UPDATE Premios SET cantidad_disponible = cantidad_disponible - 1 WHERE id_premio = ? AND cantidad_disponible > 0");
        $stmt->execute([$id_premio]);

        if ($stmt->rowCount() == 0) {
            throw new Exception("No hay suficientes premios disponibles.");
        }

        // Restar los puntos del cliente
        $stmt = $conn->prepare("UPDATE Clientes SET puntos = puntos - ? WHERE id_cliente = ? AND puntos >= ?");
        $stmt->execute([$puntos_necesarios, $id_cliente, $puntos_necesarios]);

        if ($stmt->rowCount() == 0) {
            throw new Exception("No hay suficientes puntos en la cuenta del cliente.");
        }

        // Registrar el canje en la tabla Canjes
        $stmt = $conn->prepare("INSERT INTO Canjes (id_premio, id_cliente) VALUES (?, ?)");
        $stmt->execute([$id_premio, $id_cliente]);

        $conn->commit();
        return true;
    } catch (Exception $e) {
        $conn->rollBack();
        return false;
    }
}

function registrarCanjes($id_cliente, $id_premio ){
    // Obtener la fecha actual
    global $conn;
    $stmt = $conn->prepare("INSERT INTO canjes (id_premio, id_cliente) VALUES (?, ?)");
    $stmt->execute([ $id_premio, $id_cliente]);
}

function getCanjesUltimos30Dias() {
    global $conn;
    $stmt = $conn->query("SELECT DATE(fecha) as fecha, COUNT(*) as total FROM Canjes WHERE fecha >= CURDATE() - INTERVAL 30 DAY GROUP BY DATE(fecha)");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

//obtiene todos los canjes hecho por el cliente
function obtenerCanjesCliente($id_cliente) {
    global $conn;
    $stmt = $conn->prepare("
        SELECT c.id_cliente, c.id_premio, c.fecha, p.nombre_premio
        FROM canjes c
        JOIN premios p ON c.id_premio = p.id_premio
        WHERE c.id_cliente = ?
    ");
    $stmt->execute([$id_cliente]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
function obtenerCanjesAdmin() {
    global $conn;
    $stmt = $conn->prepare("
        SELECT c.id_cliente, cl.nombre, cl.apellidos, c.id_premio, p.nombre_premio, c.fecha
        FROM canjes c
        JOIN premios p ON c.id_premio = p.id_premio
        JOIN clientes cl ON c.id_cliente = cl.id_cliente
    ");
    $stmt->execute([]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

