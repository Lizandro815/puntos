<?php
// public/premios.php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['rol'] != 'admin') {
    header("Location: index.php");
    exit;
}

include '../src/controllers/PremioController.php';

$search = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $search = $_POST['search'];
    $premios = searchPremios($search);
} else {
    $premios = getPremios();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestionar Premios</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    
    <div class="container">
        <h1 class="my-4">Premios</h1>
        <form action="premios.php" method="post" class="form-inline mb-3">
            <input type="text" class="form-control mr-sm-2" name="search" placeholder="Buscar por Nombre" value="<?= htmlspecialchars($search) ?>">
            <button type="submit" class="btn btn-primary">Buscar</button>
            <a href="agregar_premio.php" class="btn btn-primary ml-3">Agregar Premio</a>
        </form>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Imagen</th>
                    <th>Nombre del Premio</th>
                    <th>Descripción</th>
                    <th>Puntos Necesarios</th>
                    <th>Cantidad Disponible</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($premios as $premio): ?>
                <tr>
                    <td><img src="<?= "http://localhost/" . htmlspecialchars($premio['imagen']) ?>" alt="Imagen del Premio" style="width: 100px;"></td>
                    <td><?= htmlspecialchars($premio['nombre_premio']) ?></td>
                    <td><?= htmlspecialchars($premio['descripcion']) ?></td>
                    <td><?= htmlspecialchars($premio['puntos_necesarios']) ?></td>
                    <td><?= htmlspecialchars($premio['cantidad_disponible']) ?></td>
                    <td>
                        <a href="editar_premio.php?id=<?= $premio['id_premio'] ?>" class="btn btn-warning btn-sm">Editar</a>
                        <button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#confirmModal" data-id="<?= $premio['id_premio'] ?>" data-nombre="<?= htmlspecialchars($premio['nombre_premio']) ?>">Eliminar</button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <p><a href="dashboard.php" class="btn btn-secondary">Volver al Dashboard</a></p>
    </div>

    <!-- Modal de Confirmación -->
    <div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmModalLabel">Confirmar Eliminación</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>¿Estás seguro de que deseas eliminar el premio <strong id="premioNombre"></strong>?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-danger" id="confirmarEliminar">Eliminar</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        $('#confirmModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var id = button.data('id');
            var nombre = button.data('nombre');

            var modal = $(this);
            modal.find('#premioNombre').text(nombre);
            modal.find('#confirmarEliminar').data('id', id);
        });

        $('#confirmarEliminar').on('click', function () {
            var id = $(this).data('id');
            window.location.href = 'eliminar_premio.php?id=' + id;
        });
    </script>
</body>
</html>
