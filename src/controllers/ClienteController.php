<?php
// src/controllers/ClienteController.php
include __DIR__ . '/../config/config.php';

function getClientes() {
    global $conn;
    $stmt = $conn->query("SELECT * FROM Clientes");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function obtenerCliente($id_cliente) {
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM Clientes WHERE id_cliente = ?");
    $stmt->execute([$id_cliente]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function obtenerClientePorTelefono($telefono_movil) {
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM Clientes WHERE telefono_movil = ?");
    $stmt->execute([$telefono_movil]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function generarNumeroTarjeta() {
    global $conn;
    do {
        $numero_tarjeta = sprintf('%08d', mt_rand(0, 99999999));
        $stmt = $conn->prepare("SELECT COUNT(*) FROM Clientes WHERE numero_tarjeta = ?");
        $stmt->execute([$numero_tarjeta]);
        $count = $stmt->fetchColumn();
    } while ($count > 0);
    return $numero_tarjeta;
}

function agregarCliente($telefono_movil, $nombre, $apellidos, $direccion, $correo_electronico, $estado, $ciudad, $contrasena) {
    global $conn;

    // Verificar si el teléfono o correo ya existen
    $stmt = $conn->prepare("SELECT COUNT(*) FROM Clientes WHERE telefono_movil = ? OR correo_electronico = ?");
    $stmt->execute([$telefono_movil, $correo_electronico]);
    $count = $stmt->fetchColumn();
    if ($count > 0) {
        throw new Exception("El teléfono móvil o correo electrónico ya están registrados.");
    }

    $numero_tarjeta = generarNumeroTarjeta();
    $stmt = $conn->prepare("INSERT INTO Clientes (telefono_movil, nombre, apellidos, direccion, correo_electronico, estado, ciudad, puntos, contrasena, numero_tarjeta) VALUES (?, ?, ?, ?, ?, ?, ?, 0, ?, ?)");
    $stmt->execute([$telefono_movil, $nombre, $apellidos, $direccion, $correo_electronico, $estado, $ciudad, $contrasena, $numero_tarjeta]);
}

function eliminarCliente($id_cliente) {
    global $conn;
    $stmt = $conn->prepare("DELETE FROM Clientes WHERE id_cliente = ?");
    $stmt->execute([$id_cliente]);
}

function actualizarCliente($id_cliente, $telefono_movil, $nombre, $apellidos, $direccion, $correo_electronico, $estado, $ciudad, $puntos, $contrasena = null) {
    global $conn;

    // Verificar si el teléfono o correo ya existen en otro cliente
    $stmt = $conn->prepare("SELECT COUNT(*) FROM Clientes WHERE (telefono_movil = ? OR correo_electronico = ?) AND id_cliente != ?");
    $stmt->execute([$telefono_movil, $correo_electronico, $id_cliente]);
    $count = $stmt->fetchColumn();
    if ($count > 0) {
        throw new Exception("El teléfono móvil o correo electrónico ya están registrados.");
    }

    if ($contrasena) {
        $stmt = $conn->prepare("UPDATE Clientes SET telefono_movil = ?, nombre = ?, apellidos = ?, direccion = ?, correo_electronico = ?, estado = ?, ciudad = ?, puntos = ?, contrasena = ? WHERE id_cliente = ?");
        $stmt->execute([$telefono_movil, $nombre, $apellidos, $direccion, $correo_electronico, $estado, $ciudad, $puntos, $contrasena, $id_cliente]);
    } else {
        $stmt = $conn->prepare("UPDATE Clientes SET telefono_movil = ?, nombre = ?, apellidos = ?, direccion = ?, correo_electronico = ?, estado = ?, ciudad = ?, puntos = ? WHERE id_cliente = ?");
        $stmt->execute([$telefono_movil, $nombre, $apellidos, $direccion, $correo_electronico, $estado, $ciudad, $puntos, $id_cliente]);
    }
}

function agregarPuntos($id_cliente, $monto) {
    global $conn;
    $puntos = intval($monto / 100) * 5;
    $stmt = $conn->prepare("UPDATE Clientes SET puntos = puntos + ? WHERE id_cliente = ?");
    $stmt->execute([$puntos, $id_cliente]);
}
?>
