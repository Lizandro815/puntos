<?php
// public/canjear_beneficio.php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['rol'] != 'user') {
    header("Location: index.php");
    exit;
}

include '../src/controllers/ClienteController.php';
include '../src/controllers/BeneficioController.php';

$id_beneficio = $_GET['id'];
$puntos = $_GET['puntos'];
$id_cliente = $_SESSION['id_cliente'];

$cliente = obtenerCliente($id_cliente);

if ($cliente['puntos'] >= $puntos) {
    // Actualizar puntos del cliente
    $nuevo_puntos = $cliente['puntos'] - $puntos;
    actualizarPuntosCliente($id_cliente, $nuevo_puntos);

    // Reducir cantidad disponible del beneficio
    reducirCantidadBeneficio($id_beneficio);

    // Redirigir con mensaje de éxito
    $_SESSION['mensaje'] = "Beneficio canjeado exitosamente.";
} else {
    // Redirigir con mensaje de error
    $_SESSION['mensaje'] = "No tienes suficientes puntos.";
}

header("Location: user.php");
exit();
?>
