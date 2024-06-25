<?php
// src/controllers/BeneficioController.php
include __DIR__ . '/../config/config.php';

function getBeneficios() {
    global $conn;
    $stmt = $conn->query("SELECT * FROM Beneficios");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function searchBeneficios($search) {
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM Beneficios WHERE nombre_empresa LIKE ?");
    $search = "%$search%";
    $stmt->execute([$search]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function agregarBeneficio($nombre_empresa, $descripcion, $imagen) {
    global $conn;
    $stmt = $conn->prepare("INSERT INTO Beneficios (nombre_empresa, descripcion, imagen) VALUES (?, ?, ?)");
    $stmt->execute([$nombre_empresa, $descripcion, $imagen]);
}

function eliminarBeneficio($id_beneficio) {
    global $conn;
    $stmt = $conn->prepare("DELETE FROM Beneficios WHERE id_beneficio = ?");
    $stmt->execute([$id_beneficio]);
}

function actualizarBeneficio($id_beneficio, $nombre_empresa, $descripcion, $imagen) {
    global $conn;
    $stmt = $conn->prepare("UPDATE Beneficios SET nombre_empresa = ?, descripcion = ?, imagen = ? WHERE id_beneficio = ?");
    $stmt->execute([$nombre_empresa, $descripcion, $imagen, $id_beneficio]);
}

function obtenerBeneficio($id_beneficio) {
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM Beneficios WHERE id_beneficio = ?");
    $stmt->execute([$id_beneficio]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}
?>
