<?php
$carpetaNombre = isset($_GET['nombre']) ? $_GET['nombre'] : '';
$carpetaRuta = "./descarga/" . $carpetaNombre;

try {
    if (!file_exists($carpetaRuta)) {
        // Si la carpeta no existe, crea la carpeta
        mkdir($carpetaRuta, 0755, true);
        $mensaje = "Carpeta '$carpetaNombre' creada con éxito.";
    } else {
        $mensaje = "La carpeta '$carpetaNombre' ya existe.";
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Manejo de la subida de archivos
        if (isset($_FILES['archivo'])) {
            $archivos = $_FILES['archivo'];
    
            // Itera sobre cada archivo recibido
            foreach ($archivos['tmp_name'] as $indice => $nombreTemporal) {
                $nombreArchivo = $archivos['name'][$indice];
    
                // Modifica el nombre del archivo para reemplazar espacios en blanco por guiones bajos
                $nombreArchivo = str_replace(' ', '_', $nombreArchivo);
    
                if (move_uploaded_file($nombreTemporal, $carpetaRuta . '/' . $nombreArchivo)) {
                    $subido = true;
                    $mensaje = "Archivo '$nombreArchivo' subido con éxito.";
                } else {
                    throw new Exception("Error al subir el archivo '$nombreArchivo'.");
                }
            }
        }
    
        // Manejo de la eliminación de archivos
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
            // Evitar la recarga de la página después de eliminar el archivo
            exit;
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
        <div class="title-container">
        <h1>Compartir archivos <sup class="beta">BETA</sup></h1>
        </div><div class="content">
        <h3>Sube tus archivos y comparte este enlace temporal: <span>ibu.pe/<?php echo $carpetaNombre;?></span></h3>
        <div class="container">
            <div class="drop-area" id="drop-area">
                <form action="" id="form" method="POST" enctype="multipart/form-data">
                    <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" style="fill:#0730c5;transform: ;msFilter:;"><path d="M13 19v-4h3l-4-5-4 5h3v4z"></path><path d="M7 19h2v-2H7c-1.654 0-3-1.346-3-3 0-1.404 1.199-2.756 2.673-3.015l.581-.102.192-.558C8.149 8.274 9.895 7 12 7c2.757 0 5 2.243 5 5v1h1c1.103 0 2 .897 2 2s-.897 2-2 2h-3v2h3c2.206 0 4-1.794 4-4a4.01 4.01 0 0 0-3.056-3.888C18.507 7.67 15.56 5 12 5 9.244 5 6.85 6.611 5.757 9.15 3.609 9.792 2 11.82 2 14c0 2.757 2.243 5 5 5z"></path></svg> <br>
                    <input type="file" class="file-input" name="archivo[]" id="archivo" onchange="document.getElementById('form').submit()" multiple>
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
                        echo "<h3 style='margin-bottom:10px;'>Archivos Subidos:</h3>";

                        foreach ($files as $file) {
                            echo "<div class='archivos_subidos'>
                                    <div class='file-info'>
                                        <a href='$carpetaRuta/$file' download class='boton-descargar'>$file</a>
                                        <div class='progress-container'>
                                            <div class='file-progress'></div>
                                        </div>
                                    </div>
                                    <div class='actions'>
                                        <form action='' method='POST' style='display:inline;' class='delete-form'>
                                            <input type='hidden' name='eliminarArchivo' value='$file'>
                                            <button type='button' class='btn_delete'>
                                                <svg xmlns='http://www.w3.org/2000/svg' class='icon icon-tabler icon-tabler-trash' width='24' height='24' viewBox='0 0 24 24' stroke-width='2' stroke='currentColor' fill='none' stroke-linecap='round' stroke-linejoin='round'>
                                                    <path stroke='none' d='M0 0h24v24H0z' fill='none'/>
                                                    <path d='M4 7l16 0' />
                                                    <path d='M10 11l0 6' />
                                                    <path d='M14 11l0 6' />
                                                    <path d='M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12' />
                                                    <path d='M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3' />
                                                </svg>
                                            </button>
                                        </form>
                                        <div class='upload-status'>
                                            <svg xmlns='http://www.w3.org/2000/svg' class='icon icon-tabler icon-tabler-check' width='24' height='24' viewBox='0 0 24 24' stroke-width='2' stroke='currentColor' fill='none' stroke-linecap='round' stroke-linejoin='round'>
                                                <path stroke='none' d='M0 0h24v24H0z' fill='none'/>
                                                <path d='M5 12l5 5l10 -10' />
                                            </svg>
                                        </div>
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
    <script>
        // Agregar evento clic a los botones de eliminación de archivos
        const deleteButtons = document.querySelectorAll('.delete-form .btn_delete');
        deleteButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const form = this.parentNode;
                const formData = new FormData(form);
                const xhr = new XMLHttpRequest();
                xhr.open(form.getAttribute('method'), form.getAttribute('action'), true);
                xhr.onload = function() {
                    if (xhr.status === 200) {
                        // Manejar la respuesta si es necesario
                        console.log(xhr.responseText);
                        // Actualizar la lista de archivos si es necesario
                        // Puedes recargar la lista de archivos o eliminar el elemento correspondiente del DOM
                        form.parentNode.parentNode.remove(); // Eliminar el elemento correspondiente del DOM
                    } else {
                        console.error('Error al eliminar el archivo.');
                    }
                };
                xhr.send(formData);
            });
        });
    </script>
</body>

</html>
