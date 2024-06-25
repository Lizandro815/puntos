<?php
// public/editar_beneficio.php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['rol'] != 'admin') {
    header("Location: index.php");
    exit;
}

include '../src/controllers/BeneficioController.php';

$id_beneficio = $_GET['id'];
$beneficio = obtenerBeneficio($id_beneficio);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_beneficio = $_POST['id_beneficio'];
    $nombre_empresa = $_POST['nombre_empresa'];
    $descripcion = $_POST['descripcion'];

    if (!empty($_FILES['imagen']['name'])) {
        // Manejar la nueva imagen
        $target_dir = "C:/xampp/htdocs/Images/Beneficios/";
        $image_name = $nombre_empresa . "_" . time() . ".jpg"; // Se usa time() para asegurar un nombre único
        $target_file = $target_dir . basename($image_name);
        
        if (move_uploaded_file($_FILES["imagen"]["tmp_name"], $target_file)) {
            $imagen_path = "Images/Beneficios/" . basename($image_name); // Guardar solo la ruta relativa
        } else {
            echo "Hubo un error al subir la imagen.";
            exit;
        }
    } else {
        $imagen_path = $beneficio['imagen']; // Mantener la imagen actual si no se sube una nueva
    }

    actualizarBeneficio($id_beneficio, $nombre_empresa, $descripcion, $imagen_path);
    header("Location: beneficios.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Beneficio</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/styles.css">
    <style>
        #imagePreview {
            width: 100%;
            height: 300px;
            border: 1px solid #ddd;
            background-size: cover;
            background-position: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="my-4">Editar Beneficio</h1>
        <form action="editar_beneficio.php?id=<?= $id_beneficio ?>" method="post" enctype="multipart/form-data">
            <input type="hidden" name="id_beneficio" value="<?= htmlspecialchars($beneficio['id_beneficio']) ?>">
            <div class="form-group">
                <label for="nombre_empresa">Nombre de la Empresa:</label>
                <input type="text" class="form-control" id="nombre_empresa" name="nombre_empresa" value="<?= htmlspecialchars($beneficio['nombre_empresa']) ?>" required>
            </div>
            <div class="form-group">
                <label for="descripcion">Descripción:</label>
                <textarea class="form-control" id="descripcion" name="descripcion" required><?= htmlspecialchars($beneficio['descripcion']) ?></textarea>
            </div>
            <div class="form-group">
                <label for="imagen">Imagen de la Empresa:</label>
                <input type="file" class="form-control" id="imagen" name="imagen" accept="image/*">
                <div id="imagePreview" style="background-image: url('http://localhost/<?= htmlspecialchars($beneficio['imagen']) ?>');"></div>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Guardar Cambios</button>
        </form>
        <p><a href="beneficios.php" class="btn btn-secondary mt-3">Volver</a></p>
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
