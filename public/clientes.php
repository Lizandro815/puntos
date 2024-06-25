<?php
// public/clientes.php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['rol'] != 'admin') {
    header("Location: index.php");
    exit;
}

include '../src/controllers/ClienteController.php';

$search = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $search = $_POST['search'];
    $clientes = searchClientes($search);
} else {
    $clientes = getClientes();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestionar Clientes</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="container">
        <h1 class="my-4">Clientes</h1>
        <form action="clientes.php" method="post" class="form-inline mb-3">
            <input type="text" class="form-control mr-sm-2" name="search" placeholder="Buscar por Teléfono o Nombre" value="<?= htmlspecialchars($search) ?>">
            <button type="submit" class="btn btn-primary">Buscar</button>
            <a href="agregar_cliente.php" class="btn btn-primary ml-3">Agregar Cliente</a>
        </form>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Teléfono Móvil</th>
                    <th>Nombre</th>
                    <th>Apellidos</th>
                    <th>Correo Electrónico</th>
                    <th>Puntos</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($clientes as $cliente): ?>
                <tr>
                    <td><?= htmlspecialchars($cliente['telefono_movil']) ?></td>
                    <td><?= htmlspecialchars($cliente['nombre']) ?></td>
                    <td><?= htmlspecialchars($cliente['apellidos']) ?></td>
                    <td><?= htmlspecialchars($cliente['correo_electronico']) ?></td>
                    <td><?= htmlspecialchars($cliente['puntos']) ?></td>
                    <td>
                        <a href="editar_cliente.php?id=<?= $cliente['id_cliente'] ?>" class="btn btn-warning btn-sm">Editar</a>
                        <button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#confirmModal" data-id="<?= $cliente['id_cliente'] ?>" data-nombre="<?= htmlspecialchars($cliente['nombre']) ?>">Eliminar</button>
                        <a href="agregar_puntos.php?id=<?= $cliente['id_cliente'] ?>" class="btn btn-success btn-sm">Agregar Puntos</a>
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
                    <p>¿Estás seguro de que deseas eliminar al cliente <strong id="clienteNombre"></strong>?</p>
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
            modal.find('#clienteNombre').text(nombre);
            modal.find('#confirmarEliminar').data('id', id);
        });

        $('#confirmarEliminar').on('click', function () {
            var id = $(this).data('id');
            window.location.href = 'eliminar_cliente.php?id=' + id;
        });
    </script>
</body>
</html>
