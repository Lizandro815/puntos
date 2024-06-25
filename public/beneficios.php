<?php
// Aquí va el código PHP para manejar la lógica del backend
// incluyendo las funciones de búsqueda, obtención de beneficios, etc.
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['rol'] != 'admin') {
    header("Location: index.php");
    exit;
}

include '../src/controllers/BeneficioController.php';

$search = '';
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['search'])) {
    $search = $_POST['search'];
    $beneficios = searchBeneficios($search);
} else {
    $beneficios = getBeneficios();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestionar Beneficios</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/styles.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .table thead th {
            text-align: center;
        }
        .table tbody td {
            vertical-align: middle;
            text-align: center;
        }
        .table tbody td.description {
            width: 40% !important;
            word-wrap: break-word;
            white-space: normal;
        }
        .form-inline {
            display: flex;
            flex-wrap: nowrap;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="my-4">Beneficios</h1>
        <form action="beneficios.php" method="post" class="form-inline mb-3">
            <input type="text" class="form-control mr-sm-2" name="search" placeholder="Buscar por Nombre" value="<?= htmlspecialchars($search) ?>">
            <button type="submit" class="btn btn-primary">Buscar</button>
            <a href="agregar_beneficio.php" class="btn btn-primary ml-3">Agregar Beneficio</a>
        </form>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Imagen</th>
                    <th>Nombre de la Empresa</th>
                    <th>Descripción</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($beneficios as $beneficio): ?>
                <tr>
                    <td><img src="<?= "http://localhost/" . htmlspecialchars($beneficio['imagen']) ?>" alt="Imagen de la Empresa" style="width: 100px;"></td>
                    <td><?= htmlspecialchars($beneficio['nombre_empresa']) ?></td>
                    <td class="description"><?= htmlspecialchars($beneficio['descripcion']) ?></td>
                    <td>
                        <a href="editar_beneficio.php?id=<?= $beneficio['id_beneficio'] ?>" class="btn btn-warning btn-sm">Editar</a>
                        <button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#confirmModal" data-id="<?= $beneficio['id_beneficio'] ?>" data-nombre="<?= htmlspecialchars($beneficio['nombre_empresa']) ?>">Eliminar</button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <p><a href="dashboard.php" class="btn btn-secondary">Volver al Dashboard</a></p>
    </div>

    <!-- Modal de Confirmación de Eliminación -->
    <div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmModalLabel">Confirmar Eliminación</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    ¿Estás seguro de que deseas eliminar el beneficio <strong id="beneficioNombre"></strong>?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <a href="#" class="btn btn-danger" id="confirmarEliminar">Eliminar</a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        $('#confirmModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var id = button.data('id');
            var nombre = button.data('nombre');

            var modal = $(this);
            modal.find('#beneficioNombre').text(nombre);
            modal.find('#confirmarEliminar').attr('href', 'eliminar_beneficio.php?id=' + id);
        });
    </script>
</body>
</html>
