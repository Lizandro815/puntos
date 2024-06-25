<?php
// public/agregar_puntos.php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['rol'] != 'admin') {
    header("Location: index.php");
    exit;
}

include '../src/controllers/ClienteController.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_cliente = $_POST['id_cliente'];
    $monto = $_POST['monto'];
    agregarPuntos($id_cliente, $monto);
    header("Location: clientes.php");
    exit();
}

$id_cliente = $_GET['id'];
$cliente = obtenerCliente($id_cliente);
if (!$cliente) {
    echo "Error: Cliente no encontrado.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Puntos</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="container">
        <h1 class="my-4">Agregar Puntos</h1>
        <form action="agregar_puntos.php" method="post">
            <input type="hidden" name="id_cliente" value="<?= htmlspecialchars($cliente['id_cliente']) ?>">
            <div class="form-group">
                <label for="monto">Monto de la Compra (en pesos):</label>
                <input type="number" class="form-control" id="monto" name="monto" required>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Agregar Puntos</button>
        </form>
        <p><a href="clientes.php" class="btn btn-secondary mt-3">Volver</a></p>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
