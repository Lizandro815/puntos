<?php
// public/user.php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['rol'] != 'cliente') {
    header("Location: index.php");
    exit;
}

include '../src/controllers/ClienteController.php';
include '../src/controllers/PremioController.php';
include '../src/controllers/BeneficioController.php';

$cliente = obtenerClientePorTelefono($_SESSION['telefono_movil']);
$premios = getPremios();
$beneficios = getBeneficios();
$mensaje = isset($_GET['mensaje']) ? $_GET['mensaje'] : '';
$canjes = obtenerCanjesCliente($_SESSION['id_cliente']);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Puntos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        body {
            background-color: var(--bg-color, #ffffff); /* Default white */
        }
        .card-img-top {
            height: 200px;
            object-fit: fill;
        }
        .card {
            height: 100%;
            display: flex;
            flex-direction: column;
        }
        .card-body {
            flex: 1;
        }
        .beneficio-card {
            background-size: cover;
            background-position: center;
            color: white;
            text-shadow: 1px 1px 2px black;
            height: 250px;
            display: flex;
            align-items: flex-end;
            justify-content: center;
        }
        .beneficio-card .card-body {
            background: rgba(0, 0, 0, 0.5);
            width: 100%;
            text-align: center;
        }
        .gold-card {
            background: linear-gradient(45deg, #d4af37, #ffd700);
            color: white;
            text-align: center;
            padding: 40px; /* Increased padding for larger height */
            border-radius: 10px;
        }
        .gold-card h5 {
            margin: 10px 0;
        }
        .content-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .content-column {
            flex: 1;
            margin-right: 20px;
        }
        .content-column:last-child {
            margin-right: 0;
        }
        .color-picker {
            position: absolute;
            top: 10px;
            right: 10px;
        }
        .theme-color {
            background-color: var(--theme-color, #ffffff) !important;
        }
        .theme-input {
            border-color: var(--theme-color, #ffffff) !important;
        }
        .logout-icon {
            position: absolute;
            top: 10px;
            left: 10px;
        }
        .dropdown-menu {
            left: 0;
            right: auto;
        }
        .footer-space {
            margin-bottom: 60px; /* Adjust the height according to your footer height */
        }
    </style>
</head>

<body>
    <div class="color-picker dropdown">
        <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownThemeButton" data-bs-toggle="dropdown" aria-expanded="false">
            Tema
        </button>
        <ul class="dropdown-menu" aria-labelledby="dropdownThemeButton">
            <li><a class="dropdown-item" href="#" data-color="#ffffff">Blanco</a></li>
            <li><a class="dropdown-item" href="#" data-color="#f5f5dc">Beige</a></li>
            <li><a class="dropdown-item" href="#" data-color="#e0f7fa">Cyan claro</a></li>
            <li><a class="dropdown-item" href="#" data-color="#ffebee">Rosa claro</a></li>
            <li><a class="dropdown-item" href="#" data-color="#e8f5e9">Verde claro</a></li>
        </ul>
    </div>

    <div class="container">
        <div class="logout-icon dropdown">
            <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fas fa-cog"></i>
            </button>
            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#logoutModal">Cerrar Sesión</a></li>
            </ul>
        </div>

        <h1 class="my-4">Bienvenido a tu cuenta, <?= htmlspecialchars($cliente['nombre']) ?></h1>

        <div class="content-row">
            <div class="content-column">
                <div class="gold-card">
                    <h5>MEGACARD CLUB</h5>
                    <p>Tarjeta <?= htmlspecialchars($cliente['numero_tarjeta']) ?></p>
                </div>
            </div>
            <div class="content-column">
                <div class="card" style="padding: 40px;"> <!-- Increased padding for larger height -->
                    <div class="card-body text-center"> 
                        <h5 class="card-title">Puntos Acumulados</h5>
                        <p class="card-text"><?= htmlspecialchars($cliente['puntos']) ?></p>
                    </div>
                </div>
            </div>
        </div>

        <?php if ($mensaje): ?>
        <div class="alert alert-info"><?= htmlspecialchars($mensaje) ?></div>
        <?php endif; ?>

        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="premios-tab" data-bs-toggle="tab" href="#premios" role="tab" aria-controls="premios" aria-selected="true">Premios</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="beneficios-tab" data-bs-toggle="tab" href="#beneficios" role="tab" aria-controls="beneficios" aria-selected="false">Beneficios</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="canjes-tab" data-bs-toggle="tab" href="#canjes" role="canjes" aria-controls="canjes" aria-selected="false">Mis canjes</a>
            </li>
        </ul>
        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="premios" role="tabpanel" aria-labelledby="premios-tab">
                <h2 class="my-4">Premios Disponibles</h2>
                <input type="text" id="buscarPremios" class="form-control mb-4" placeholder="Buscar premios por nombre...">
                <div class="row" id="listaPremios">
                    <?php foreach ($premios as $premio): ?>
                    <div class="col-md-4 premio-item">
                        <div class="card mb-4 theme-color">
                            <img src="<?= "http://localhost/" . htmlspecialchars($premio['imagen']) ?>" class="card-img-top" alt="Imagen del Premio">
                            <div class="card-body">
                                <h5 class="card-title"><?= htmlspecialchars($premio['nombre_premio']) ?></h5>
                                <p class="card-text"><?= htmlspecialchars($premio['descripcion']) ?></p>
                                <p class="card-text">Puntos necesarios: <?= htmlspecialchars($premio['puntos_necesarios']) ?></p>
                                <?php if ($premio['cantidad_disponible'] > 0): ?>
                                    <?php if ($cliente['puntos'] >= $premio['puntos_necesarios']): ?>
                                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#canjearModal" data-id="<?= $premio['id_premio'] ?>" data-nombre="<?= htmlspecialchars($premio['nombre_premio']) ?>" data-puntos="<?= $premio['puntos_necesarios'] ?>">Canjear</button>
                                    <?php else: ?>
                                    <button class="btn btn-secondary" disabled>No alcanza</button>
                                    <?php endif; ?>
                                <?php else: ?>
                                <button class="btn btn-secondary" disabled>Agotado</button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="tab-pane fade" id="beneficios" role="tabpanel" aria-labelledby="beneficios-tab">
                <h2 class="my-4">Beneficios Disponibles</h2>
                <input type="text" id="buscarBeneficios" class="form-control mb-4" placeholder="Buscar beneficios por nombre...">
                <div class="row" id="listaBeneficios">
                    <?php foreach ($beneficios as $beneficio): ?>
                    <div class="col-md-4 beneficio-item">
                        <div class="card mb-4 beneficio-card" style="background-image: url('http://localhost/<?= htmlspecialchars($beneficio['imagen']) ?>');">
                            <div class="card-body">
                                <h5 class="card-title"><?= htmlspecialchars($beneficio['nombre_empresa']) ?></h5>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <!----- CANJES ------->
            <div class="tab-pane fade" id="canjes" role="tabpanel" aria-labelledby="canjes-tab">
                <h2 class="my-4">Historial de canjes</h2>
                <div class="row">
                    <div class="col-12">
                        <?php if (empty($canjes)) : ?>
                            <div class="alert alert-danger" role="alert">
                                No tienes premios canjeados.
                            </div>
                        <?php else : ?>
                            <table id="canjesTable" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>Premio</th>
                                        <th>Fecha de Canje</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($canjes as $canje) : ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($canje['nombre_premio']); ?></td>
                                            <td><?php echo htmlspecialchars($canje['fecha']); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

        </div>
        <div class="footer-space"></div> <!-- Spacer for the footer -->
    </div>

    <!-- Modal para Canjear Premio -->
    <div class="modal fade" id="canjearModal" tabindex="-1" aria-labelledby="canjearModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="canjearModalLabel">Canjear Premio</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <p>¿Estás seguro de que deseas canjear <strong id="premioNombre"></strong> por <strong id="premioPuntos"></strong> puntos?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" id="confirmarCanje">Canjear</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para Canjear Beneficio -->
    <div class="modal fade" id="canjearBeneficioModal" tabindex="-1" aria-labelledby="canjearBeneficioModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="canjearBeneficioModalLabel">Canjear Beneficio</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <p>¿Estás seguro de que deseas canjear <strong id="beneficioNombre"></strong> por <strong id="beneficioPuntos"></strong> puntos?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" id="confirmarCanjeBeneficio">Canjear</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para Confirmar Cierre de Sesión -->
    <div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="logoutModalLabel">Confirmar Cierre de Sesión</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <p>¿Estás seguro de que deseas cerrar la sesión?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <a href="logout.php" class="btn btn-danger">Cerrar Sesión</a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script>
        // Change background color based on user selection
        document.addEventListener('DOMContentLoaded', (event) => {
            const storedColor = localStorage.getItem('bgColor');
            if (storedColor) {
                document.body.style.backgroundColor = storedColor;
                document.documentElement.style.setProperty('--bg-color', storedColor);
                document.documentElement.style.setProperty('--theme-color', storedColor !== '#ffffff' ? storedColor : '#ffffff');
                toggleThemeClasses(storedColor !== '#ffffff');
            }

            document.querySelectorAll('.dropdown-item[data-color]').forEach(item => {
                item.addEventListener('click', function() {
                    const selectedColor = this.getAttribute('data-color');
                    document.body.style.backgroundColor = selectedColor;
                    document.documentElement.style.setProperty('--bg-color', selectedColor);
                    document.documentElement.style.setProperty('--theme-color', selectedColor !== '#ffffff' ? selectedColor : '#ffffff');
                    localStorage.setItem('bgColor', selectedColor);
                    toggleThemeClasses(selectedColor !== '#ffffff');
                });
            });
        });

        function toggleThemeClasses(apply) {
            const elements = document.querySelectorAll('.theme-color, .theme-input');
            elements.forEach(element => {
                if (apply) {
                    element.classList.add('theme-applied');
                } else {
                    element.classList.remove('theme-applied');
                }
            });
        }

        $('#canjearModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var id = button.data('id');
            var nombre = button.data('nombre');
            var puntos = button.data('puntos');

            var modal = $(this);
            modal.find('#premioNombre').text(nombre);
            modal.find('#premioPuntos').text(puntos);
            modal.find('#confirmarCanje').data('id', id);
            modal.find('#confirmarCanje').data('puntos', puntos);
        });

        $('#confirmarCanje').on('click', function() {
            var id = $(this).data('id');
            var puntos = $(this).data('puntos');
            window.location.href = 'canjear_premio.php?id=' + id + '&puntos=' + puntos;
        });

        $('#canjearBeneficioModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var id = button.data('id');
            var nombre = button.data('nombre');
            var puntos = button.data('puntos');

            var modal = $(this);
            modal.find('#beneficioNombre').text(nombre);
            modal.find('#beneficioPuntos').text(puntos);
            modal.find('#confirmarCanjeBeneficio').data('id', id);
            modal.find('#confirmarCanjeBeneficio').data('puntos', puntos);
        });

        $('#confirmarCanjeBeneficio').on('click', function () {
            var id = $(this).data('id');
            var puntos = $(this).data('puntos');
            window.location.href = 'canjear_beneficio.php?id=' + id + '&puntos=' + puntos;
        });

        // Búsqueda en premios
        $('#buscarPremios').on('keyup', function () {
            var value = $(this).val().toLowerCase();
            $('#listaPremios .premio-item').filter(function () {
                $(this).toggle($(this).find('.card-title').text().toLowerCase().indexOf(value) > -1)
            });
        });

        // Búsqueda en beneficios
        $('#buscarBeneficios').on('keyup', function () {
            var value = $(this).val().toLowerCase();
            $('#listaBeneficios .beneficio-item').filter(function () {
                $(this).toggle($(this).find('.card-title').text().toLowerCase().indexOf(value) > -1)
            });
        });
    </script>
</body>

</html>