<?php
// public/editar_premio.php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['rol'] != 'admin') {
    header("Location: index.php");
    exit;
}

include '../src/controllers/PremioController.php';

$id_premio = $_GET['id'];
$premio = obtenerPremio($id_premio);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_premio = $_POST['id_premio'];
    $nombre_premio = $_POST['nombre_premio'];
    $descripcion = $_POST['descripcion'];
    $puntos_necesarios = $_POST['puntos_necesarios'];
    $cantidad_disponible = $_POST['cantidad_disponible'];

    if (!empty($_FILES['imagen']['name'])) {
        // Manejar la nueva imagen
        $target_dir = "C:/xampp/htdocs/Images/Premios/";
        $image_name = $nombre_premio . "_" . time() . ".jpg"; // Se usa time() para asegurar un nombre único
        $target_file = $target_dir . basename($image_name);
        
        if (move_uploaded_file($_FILES["imagen"]["tmp_name"], $target_file)) {
            $imagen_path = "Images/Premios/" . basename($image_name); // Guardar solo la ruta relativa
        } else {
            echo "Hubo un error al subir la imagen.";
            exit;
        }
    } else {
        $imagen_path = $premio['imagen']; // Mantener la imagen actual si no se sube una nueva
    }

    actualizarPremio($id_premio, $nombre_premio, $descripcion, $puntos_necesarios, $cantidad_disponible, $imagen_path);
    header("Location: premios.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Premio</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/styles.css">
    <style>
        #imagePreview {
            width: 100%;
            height: 500px;
            border: 1px solid #ddd;
            background-size: cover;
            background-position: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="my-4">Editar Premio</h1>
        <form action="editar_premio.php?id=<?= $id_premio ?>" method="post" enctype="multipart/form-data">
            <input type="hidden" name="id_premio" value="<?= htmlspecialchars($premio['id_premio']) ?>">
            <div class="form-group">
                <label for="nombre_premio">Nombre del Premio:</label>
                <input type="text" class="form-control" id="nombre_premio" name="nombre_premio" value="<?= htmlspecialchars($premio['nombre_premio']) ?>" required>
            </div>
            <div class="form-group">
                <label for="descripcion">Descripción:</label>
                <textarea class="form-control" id="descripcion" name="descripcion" maxlength="32" required><?= htmlspecialchars($premio['descripcion']) ?></textarea>
            </div>
            <div class="form-group">
                <label for="puntos_necesarios">Puntos Necesarios:</label>
                <input type="number" class="form-control" id="puntos_necesarios" name="puntos_necesarios" value="<?= htmlspecialchars($premio['puntos_necesarios']) ?>" required>
            </div>
            <div class="form-group">
                <label for="cantidad_disponible">Cantidad Disponible:</label>
                <input type="number" class="form-control" id="cantidad_disponible" name="cantidad_disponible" value="<?= htmlspecialchars($premio['cantidad_disponible']) ?>" required>
            </div>
            <div class="form-group">
                <label for="imagen">Imagen del Premio:</label>
                <input type="file" class="form-control" id="imagen" name="imagen" accept="image/*">
                <div id="imagePreview" style="background-image: url('http://localhost/<?= htmlspecialchars($premio['imagen']) ?>');"></div>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Guardar Cambios</button>
        </form>
        <p><a href="premios.php" class="btn btn-secondary mt-3">Volver</a></p>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script>
        $("#imagen").change(function() {
            var input = this;
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#imagePreview').css('background-image', 'url(' + e.target.result + ')').show();
                }
                reader.readAsDataURL(input.files[0]);
            }
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrap.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
