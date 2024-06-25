<?php
// public/login.php
session_start();
include '../src/config/config.php';
include '../src/controllers/AdminController.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $telefono_movil = $_POST['telefono_movil'];
    $contrasena = $_POST['contrasena'];

    // Verificar si es un administrador
    $admin = getAdministradorPorTelefono($telefono_movil);

    if ($admin && password_verify($contrasena, $admin['admin_contrasena'])) {
        $_SESSION['loggedin'] = true;
        $_SESSION['telefono_movil'] = $telefono_movil;
        $_SESSION['nombre'] = $admin['nombre'];  // Asignar el nombre del administrador a la sesión
        $_SESSION['rol'] = 'admin';
        header("Location: dashboard.php");
        exit();
    }

    // Verificar si es un cliente
    $stmt = $conn->prepare("SELECT * FROM Clientes WHERE telefono_movil = ?");
    $stmt->execute([$telefono_movil]);
    $cliente = $stmt->fetch();

    if ($cliente && password_verify($contrasena, $cliente['contrasena'])) {
        $_SESSION['loggedin'] = true;
        $_SESSION['telefono_movil'] = $telefono_movil;
        $_SESSION['id_cliente'] = $cliente['id_cliente'];  // Asignar id_cliente a la sesión
        $_SESSION['rol'] = 'cliente';
        header("Location: user.php");
        exit();
    }

    // Si llega aquí, las credenciales no son válidas
    echo "Usuario no encontrado o contraseña incorrecta.";
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Programa de Fidelización - Login</title>
    <link rel="stylesheet" href="https://stackpath.bootstrap.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <h1 class="text-center my-4">Login</h1>
                <form action="login.php" method="post">
                    <div class="form-group">
                        <label for="telefono_movil">Teléfono Móvil:</label>
                        <input type="text" class="form-control" id="telefono_movil" name="telefono_movil" required>
                    </div>
                    <div class="form-group">
                        <label for="contrasena">Contraseña:</label>
                        <input type="password" class="form-control" id="contrasena" name="contrasena" required>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Login</button>
                </form>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrap.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
