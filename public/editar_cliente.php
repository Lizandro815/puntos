<?php
// public/editar_cliente.php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['rol'] != 'admin') {
    header("Location: index.php");
    exit;
}

include '../src/controllers/ClienteController.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_cliente = $_POST['id_cliente'];
    $telefono_movil = $_POST['telefono_movil'];
    $nombre = $_POST['nombre'];
    $apellidos = $_POST['apellidos'];
    $direccion = $_POST['direccion'];
    $correo_electronico = $_POST['correo_electronico'];
    $estado = $_POST['estado'];
    $ciudad = $_POST['ciudad'];
    $puntos = $_POST['puntos'];
    $contrasena = $_POST['contrasena'] ? password_hash($_POST['contrasena'], PASSWORD_BCRYPT) : null;

    actualizarCliente($id_cliente, $telefono_movil, $nombre, $apellidos, $direccion, $correo_electronico, $estado, $ciudad, $puntos, $contrasena);
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
    <title>Editar Cliente</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="container">
        <h1 class="my-4">Editar Cliente</h1>
        <form action="editar_cliente.php" method="post">
            <input type="hidden" name="id_cliente" value="<?= htmlspecialchars($cliente['id_cliente']) ?>">
            <div class="form-group">
                <label for="telefono_movil">Teléfono Móvil:</label>
                <input type="text" class="form-control" id="telefono_movil" name="telefono_movil" value="<?= htmlspecialchars($cliente['telefono_movil']) ?>" required>
            </div>
            <div class="form-group">
                <label for="nombre">Nombre:</label>
                <input type="text" class="form-control" id="nombre" name="nombre" value="<?= htmlspecialchars($cliente['nombre']) ?>" required>
            </div>
            <div class="form-group">
                <label for="apellidos">Apellidos:</label>
                <input type="text" class="form-control" id="apellidos" name="apellidos" value="<?= htmlspecialchars($cliente['apellidos']) ?>" required>
            </div>
            <div class="form-group">
                <label for="direccion">Dirección:</label>
                <input type="text" class="form-control" id="direccion" name="direccion" value="<?= htmlspecialchars($cliente['direccion']) ?>" required>
            </div>
            <div class="form-group">
                <label for="correo_electronico">Correo Electrónico:</label>
                <input type="email" class="form-control" id="correo_electronico" name="correo_electronico" value="<?= htmlspecialchars($cliente['correo_electronico']) ?>" required>
            </div>
            <div class="form-group">
                <label for="estado">Estado:</label>
                <input type="text" class="form-control" id="estado" name="estado" value="<?= htmlspecialchars($cliente['estado']) ?>" required>
            </div>
            <div class="form-group">
                <label for="ciudad">Ciudad:</label>
                <input type="text" class="form-control" id="ciudad" name="ciudad" value="<?= htmlspecialchars($cliente['ciudad']) ?>" required>
            </div>
            <div class="form-group">
                <label for="puntos">Puntos:</label>
                <input type="number" class="form-control" id="puntos" name="puntos" value="<?= htmlspecialchars($cliente['puntos']) ?>" required>
            </div>
            <div class="form-group">
                <label for="contrasena">Contraseña (dejar en blanco para no cambiar):</label>
                <input type="password" class="form-control" id="contrasena" name="contrasena">
            </div>
            <button type="submit" class="btn btn-primary btn-block">Guardar Cambios</button>
        </form>
        <p><a href="clientes.php" class="btn btn-secondary mt-3">Volver</a></p>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
