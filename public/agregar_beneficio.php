<?php
// public/agregar_beneficio.php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['rol'] != 'admin') {
    header("Location: index.php");
    exit;
}

include '../src/controllers/BeneficioController.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre_empresa = $_POST['nombre_empresa'];
    $descripcion = $_POST['descripcion'];
    
    // Manejar la imagen
    $target_dir = "C:/xampp/htdocs/Images/Beneficios/";
    $image_name = $nombre_empresa . "_" . time() . ".jpg"; // Se usa time() para asegurar un nombre único
    $target_file = $target_dir . basename($image_name);
    
    if (move_uploaded_file($_FILES["imagen"]["tmp_name"], $target_file)) {
        $imagen_path = "Images/Beneficios/" . basename($image_name); // Guardar solo la ruta relativa
        agregarBeneficio($nombre_empresa, $descripcion, $imagen_path);
        header("Location: beneficios.php");
        exit();
    } else {
        echo "Hubo un error al subir la imagen.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Beneficio</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/styles.css">
    <style>
        #imagePreview {
            width: 100%;
            height: 300px;
            border: 1px solid #ddd;
            display: none;
            background-size: cover;
            background-position: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="my-4">Agregar Beneficio</h1>
        <form action="agregar_beneficio.php" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="nombre_empresa">Nombre de la Empresa:</label>
                <input type="text" class="form-control" id="nombre_empresa" name="nombre_empresa" required>
            </div>
            <div class="form-group">
                <label for="descripcion">Descripción:</label>
                <textarea class="form-control" id="descripcion" name="descripcion" required></textarea>
            </div>
            <div class="form-group">
                <label for="imagen">Imagen de la Empresa:</label>
                <input type="file" class="form-control" id="imagen" name="imagen" accept="image/*" required>
                <div id="imagePreview"></div>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Agregar Beneficio</button>
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
