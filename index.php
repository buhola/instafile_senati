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
    <script src="parametro.js"></script>
    <link rel="stylesheet" href="estilo.css">
</head>

<body>
    <div class="header-container">
        <h1>Compartir archivos BY:<span class="jlb"><b>JLBENYA</b></span> <sup class="beta">BETA</sup></h1>
    </div>
    <div class="content">
        <h3>Sube tus archivos y comparte este enlace temporal: <span>grupo2caso05.store/<?php echo $carpetaNombre;?></span></h3>
        <div class="container">
            <div class="drop-area" id="drop-area">
                <form action="" id="form" method="POST" enctype="multipart/form-data">
                    <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" style="fill:#00000;">
                        <path d="M5 20h14v-2H5v2zm7-16l-5 5h3v7h4v-7h3l-5-5z"/>
                    </svg>
                    <br>
                    <input type="file" class="file-input" name="archivos[]" id="archivo" multiple onchange="document.getElementById('form').submit()">
                    <label>Arrastra tus archivos aquí<br>o</label>
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
                                <div><a href='$carpetaRuta/$file' download class='boton-descargar'>$file</a></div>
                                <div>
                                <form action='' method='POST' style='display:inline;'>
                                    <input type='hidden' name='eliminarArchivo' value='$file'>
                                    <button type='submit' class='btn_delete'>
                                        <svg xmlns='http://www.w3.org/2000/svg' class='icon icon-tabler icon-tabler-x' width='24' height='24' viewBox='0 0 24 24' stroke-width='2' stroke='currentColor' fill='none' stroke-linecap='round' stroke-linejoin='round'>
                                            <path stroke='none' d='M0 0h24v24H0z' fill='none'/>
                                            <path d='M18 6L6 18' />
                                            <path d='M6 6l12 12' />
                                        </svg>
                                    </button>
                                </form>
                                </div>
                            </div>";
                        }                        
                    } else {
                        echo "No se han subido archivos.";
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>

    <!-- <script src="parametro.js"></script> -->

</body>

</html>


