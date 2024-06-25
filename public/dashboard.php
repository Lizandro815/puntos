<?php
// public/dashboard.php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['rol'] != 'admin') {
    header("Location: index.php");
    exit;
}

include '../src/controllers/ClienteController.php';
include '../src/controllers/PremioController.php';
include '../src/controllers/BeneficioController.php';

$clientes = getClientes();
$premios = getPremios();
$beneficios = getBeneficios();
$canjes = getCanjesUltimos30Dias();
$admin_nombre = $_SESSION['nombre'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/styles.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .sidebar {
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            width: 250px;
            background-color: #343a40;
            padding-top: 20px;
        }
        .sidebar a {
            color: white;
            display: block;
            padding: 10px 20px;
            text-decoration: none;
        }
        .sidebar a:hover {
            background-color: #495057;
        }
        .sidebar .active {
            background-color: #495057;
        }
        .main-content {
            margin-left: 250px;
            padding: 20px;
        }
        .card {
            margin-bottom: 20px;
        }
        .card-title {
            font-size: 2rem;
        }
        .card-body {
            text-align: center;
        }
        .welcome-title {
            font-size: 1.5rem;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <a href="dashboard.php" class="<?= basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : '' ?>">Inicio</a>
        <a href="clientes.php" class="<?= basename($_SERVER['PHP_SELF']) == 'clientes.php' ? 'active' : '' ?>">Clientes</a>
        <a href="premios.php" class="<?= basename($_SERVER['PHP_SELF']) == 'premios.php' ? 'active' : '' ?>">Premios</a>
        <a href="beneficios.php" class="<?= basename($_SERVER['PHP_SELF']) == 'beneficios.php' ? 'active' : '' ?>">Beneficios</a>
        <a href="canjes.php" class="<?= basename($_SERVER['PHP_SELF']) == 'canjes.php' ? 'active' : '' ?>">Canjes</a>
        <a href="#" data-toggle="modal" data-target="#logoutModal">Cerrar Sesión</a>
    </div>

    <div class="main-content">
        <div class="welcome-title">
            Bienvenido, <?= htmlspecialchars($admin_nombre) ?>
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="card text-white bg-danger">
                    <div class="card-body">
                        <h5 class="card-title"><?= count($clientes) ?></h5>
                        <p class="card-text">Clientes</p>
                        <a href="clientes.php" class="btn btn-light">Mostrar Clientes</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-white bg-success">
                    <div class="card-body">
                        <h5 class="card-title"><?= count($premios) ?></h5>
                        <p class="card-text">Premios</p>
                        <a href="premios.php" class="btn btn-light">Mostrar Premios</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-white bg-warning">
                    <div class="card-body">
                        <h5 class="card-title"><?= count($beneficios) ?></h5>
                        <p class="card-text">Beneficios</p>
                        <a href="beneficios.php" class="btn btn-light">Mostrar Beneficios</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <canvas id="canjesChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Modal de Confirmación de Cerrar Sesión -->
    <div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="logoutModalLabel">Confirmar Cierre de Sesión</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    ¿Estás seguro de que deseas cerrar sesión?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <a href="logout.php" class="btn btn-danger">Cerrar Sesión</a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        var ctx = document.getElementById('canjesChart').getContext('2d');
        var canjesChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: <?= json_encode(array_column($canjes, 'fecha')) ?>,
                datasets: [{
                    label: 'Canjes en los últimos 30 días',
                    data: <?= json_encode(array_column($canjes, 'total')) ?>,
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
</body>
</html>
