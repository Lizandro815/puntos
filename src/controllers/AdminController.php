<?php
// src/controllers/AdminController.php
include __DIR__ . '/../config/config.php';

function getAdministradorPorTelefono($telefono_movil) {
    global $conn;
    $stmt = $conn->prepare("SELECT Administradores.*, Admin_Auth.contrasena AS admin_contrasena FROM Administradores INNER JOIN Admin_Auth ON Administradores.id_administrador = Admin_Auth.id_administrador WHERE Administradores.telefono_movil = ?");
    $stmt->execute([$telefono_movil]);
    return $stmt->fetch();
}
?>
