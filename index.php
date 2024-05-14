<?php
$carpetaNombre = isset($_GET['nombre']) ? $_GET['nombre'] : '';
$carpetaRuta = "./descarga/" . $carpetaNombre;

try {
    if (!file_exists($carpetaRuta)) {
        mkdir($carpetaRuta, 0755, true);
        $mensaje = "Carpeta '$carpetaNombre' creada con éxito.";
    } else {
        $mensaje = "La carpeta '$carpetaNombre' ya existe.";
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        if (isset($_FILES['archivo'])) {
            $archivos = $_FILES['archivo'];

            foreach ($archivos['name'] as $key => $name) {
                if (move_uploaded_file($archivos['tmp_name'][$key], $carpetaRuta . '/' . str_replace(' ', '_', $name))) {
                    $subido = true;
                    $mensaje = "Archivos subidos con éxito.";
                } else {
                    throw new Exception("Error al subir el archivo $name.");

        if (isset($_FILES['archivos']) && is_array($_FILES['archivos']['name'])) {
            foreach ($_FILES['archivos']['name'] as $key => $nombre) {
                $nuevoNombreArchivo = str_replace(' ', '_', $nombre);
                $rutaTemp = $_FILES['archivos']['tmp_name'][$key];
                $rutaDestino = $carpetaRuta . '/' . $nuevoNombreArchivo;
    
                if (move_uploaded_file($rutaTemp, $rutaDestino)) {
                    $subido = true;
                    $mensaje = "Archivos subidos con éxito.";
                } else {
                    $mensaje = "Error al subir el archivo: $nombre";

                }
            }
        }
    }

    if (isset($_POST['eliminarArchivo'])) {
        $archivoAEliminar = $_POST['eliminarArchivo'];
        $archivoRutaAEliminar = $carpetaRuta . '/' . $archivoAEliminar;

        if (file_exists($archivoRutaAEliminar)) {
            if (unlink($archivoRutaAEliminar)) {
                $mensaje = "Archivo '$archivoAEliminar' eliminado con éxito.";
            } else {
                throw new Exception("Error al eliminar el archivo.");
            }
        } else {
            throw new Exception("El archivo '$archivoAEliminar' no existe.");
        }
    }
} catch (Exception $e) {
    $mensaje = "Error: " . htmlspecialchars($e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Compartir archivos</title>
    <link rel="stylesheet" href="estilo.css">
</head>

<body>
    <h1>Compartir archivos <sup class="beta">BETA</sup></h1>
    <div class="content">

        <h3>Sube tus archivos y comparte este enlace temporal: <span>ibu.pe/<?php echo $carpetaNombre; ?></span></h3>
        <div class="container">
            <div class="drop-area" id="drop-area">
                <form action="" id="form" method="POST" enctype="multipart/form-data">
                    <svg xmlns="http://www.w3.org/2000/svg" width="200" height="200" viewBox="0 0 24 24" style="fill:#0730c5;transform: ;msFilter:;"><path d="M13 19v-4h3l-4-5-4 5h3v4z"></path><path d="M7 19h2v-2H7c-1.654 0-3-1.346-3-3 0-1.404 1.199-2.756 2.673-3.015l.581-.102.192-.558C8.149 8.274 9.895 7 12 7c2.757 0 5 2.243 5 5v1h1c1.103 0 2 .897 2 2s-.897 2-2 2h-3v2h3c2.206 0 4-1.794 4-4a4.01 4.01 0 0 0-3.056-3.888C18.507 7.67 15.56 5 12 5 9.244 5 6.85 6.611 5.757 9.15 3.609 9.792 2 11.82 2 14c0 2.757 2.243 5 5 5z"></path></svg> <br>
                    <input type="file" class="file-input" name="archivo[]" id="archivo" multiple onchange="document.getElementById('form').submit()">
                    <label style="font-size: 24px;">Arrastra tus archivos aquí</label>
        <h3>Sube tus archivos y comparte este enlace temporal: <span>ibu.pe/<?php echo $carpetaNombre;?></span></h3>
        <div class="container">
            <div class="drop-area" id="drop-area">
                <form action="" id="form" method="POST" enctype="multipart/form-data">
                    <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" style="fill:#0730c5;transform: ;msFilter:;"><path d="M13 19v-4h3l-4-5-4 5h3v4z"></path><path d="M7 19h2v-2H7c-1.654 0-3-1.346-3-3 0-1.404 1.199-2.756 2.673-3.015l.581-.102.192-.558C8.149 8.274 9.895 7 12 7c2.757 0 5 2.243 5 5v1h1c1.103 0 2 .897 2 2s-.897 2-2 2h-3v2h3c2.206 0 4-1.794 4-4a4.01 4.01 0 0 0-3.056-3.888C18.507 7.67 15.56 5 12 5 9.244 5 6.85 6.611 5.757 9.15 3.609 9.792 2 11.82 2 14c0 2.757 2.243 5 5 5z"></path></svg> <br>
                    <input type="file" class="file-input" name="archivos[]" id="archivo" multiple onchange="document.getElementById('form').submit()">
                    <label> Arrastra tus archivos aquí<br>o</label>
                    <p><b>Abre el explorador</b></p> 
                    
                </form>
            </div>

            <div class="container2">
                <div id="file-list" class="pila">
                    <?php
                    $targetDir = $carpetaRuta;

                    $files = scandir($targetDir);
                    $files = array_diff($files, array('.', '..'));

                    if (count($files) > 0) {
                        echo " <h3 style='margin-bottom:10px;'>Archivos Subidos:</h3>";

                        foreach ($files as $file) {
                            echo "<div class='archivos_subidos'>
                            <div><svg xmlns='http://www.w3.org/2000/svg' class='icon icon-tabler icon-tabler-folder' width='24' height='24' viewBox='0 0 24 24' stroke-width='2' stroke='currentColor' fill='none' stroke-linecap='round' stroke-linejoin='round'><path stroke='none' d='M0 0h24v24H0z' fill='none'/><path d='M3 4v16a2 2 0 0 0 2 2h14a2 2 0 0 0 2 -2v-10l-6 -4l-6 4z' /></svg></div>
                            <div><a href='$carpetaRuta/$file' download class='boton-descargar'>$file</a></div>
                            <div>
                            <form action='' method='POST' style='display:inline;'>
                                <input type='hidden' name='eliminarArchivo' value='$file'>
                                <button type='submit' class='btn_delete'>Eliminar</button>
                            </form>
                        </div>
                        </div>";
                        }
                    } else {
                        echo "No se han subido archivos.";
                    }
                    ?>
                </div>
                <!-- Botón "Agregar" en la esquina inferior derecha -->
                <button class="btn-abrir-directorio" onclick="document.getElementById('archivo').click()">Agregar</button>
            </div>
        </div>
    </div>

    <!-- <script src="parametro.js"></script> -->

</body>

</html>