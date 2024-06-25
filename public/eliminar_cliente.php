<?php
// public/eliminar_cliente.php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['rol'] != 'admin') {
    header("Location: index.php");
    exit;
}

include '../src/controllers/ClienteController.php';

$id_cliente = $_GET['id'];
eliminarCliente($id_cliente);
header("Location: clientes.php");
exit();
?>
