<?php
session_start();
include '../src/config/config.php';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Programa de Fidelización - Login</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/index.css">
    <style>
        body {
            background-image: url('https://img.freepik.com/vector-gratis/fondo-geometrico-degradado_23-2148828429.jpg?t=st=1718498508~exp=1718502108~hmac=3e66fa32fe4469c05aeb3a9f460625888597304122198e5d0b4faa49e805961e&w=1380'); /* Usa la ruta correcta de tu imagen de fondo */
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: Arial, sans-serif;
        }
        .login-container {
            background-color: rgba(255, 255, 255, 0.9);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 100%;
        }
        .login-header {
            text-align: center;
            margin-bottom: 20px;
        }
        .login-header h1 {
            font-size: 24px;
            margin-bottom: 10px;
            color: #1E3A8A; /* Color similar al azul de la imagen */
        }
        .login-header p {
            font-size: 14px;
            color: #666;
        }
        .form-control {
            border-radius: 30px; /* Borde redondeado similar al de la imagen */
            padding: 10px 20px; /* Aumentar el padding para mejor apariencia */
        }
        .btn-primary {
            background-color: #1E3A8A; /* Color similar al azul de la imagen */
            border: none;
            border-radius: 30px; /* Borde redondeado similar al de la imagen */
            padding: 10px 20px; /* Aumentar el padding para mejor apariencia */
            font-size: 16px; /* Tamaño de fuente más grande para mejor apariencia */
        }
        .btn-primary:hover {
            background-color: #1C3380; /* Un tono más oscuro al pasar el ratón */
        }
        .login-footer {
            text-align: center;
            margin-top: 20px;
        }
        .login-footer a {
            color: #1E3A8A; /* Color similar al azul de la imagen */
            text-decoration: none;
        }
        .login-footer a:hover {
            text-decoration: underline;
        }
        .background-image {
            position: absolute;
            bottom: 0;
            width: 100%;
            height: 60%;
            background-image: url('/mnt/data/login.jpg'); /* Imagen que deseas usar */
            background-size: contain;
            background-position: bottom;
            background-repeat: no-repeat;
            z-index: -1;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <h1>Bienvenido a la Familia</h1>
            <p>Ingresa para poder acceder a premios y beneficios.</p>
        </div>
        <form action="login.php" method="post">
            <div class="form-group">
                <label for="telefono_movil">Teléfono Móvil:</label>
                <input type="text" class="form-control" id="telefono_movil" name="telefono_movil" required>
            </div>
            <div class="form-group">
                <label for="contrasena">Contraseña:</label>
                <input type="password" class="form-control" id="contrasena" name="contrasena" required>
            </div>
            <div class="form-group form-check">
                <input type="checkbox" class="form-check-input" id="remember">
                <label class="form-check-label" for="remember">Recuérdame</label>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Login</button>
        </form>
        <div class="login-footer">
            <a href="#">¿Olvidaste tu contraseña?</a>
        </div>
    </div>
    <div class="background-image"></div>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrap.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        // Check if the user was just logged out
        if (window.location.search.includes('logged_out=true')) {
            // Clear local storage
            localStorage.clear();
            // Remove the logged_out parameter from the URL
            const url = new URL(window.location);
            url.searchParams.delete('logged_out');
            window.history.replaceState({}, document.title, url);
        }
    </script>
</body>
</html>
