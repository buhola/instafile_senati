// URL actual
const urlActual = window.location.href;

// Extrae el segmento final de la URL como nombre de la carpeta
var carpetaNombre = window.location.pathname.split('/').pop();

if (!carpetaNombre) {
    // Genera un nombre aleatorio si no existe
    carpetaNombre = generarCadenaAleatoria();
    // Redirige a la nueva URL con el nombre generado
    window.location.href = `${window.location.origin}${window.location.pathname}${carpetaNombre}`;
}

// Función para generar una cadena aleatoria de 3 caracteres
function generarCadenaAleatoria() {
    const caracteres = 'abcdefghijklmnopqrstuvwxyz0123456789';
    let cadenaAleatoria = '';
    for (let i = 0; i < 3; i++) {
        const caracterAleatorio = caracteres.charAt(Math.floor(Math.random() * caracteres.length));
        cadenaAleatoria += caracterAleatorio;
    }
    return cadenaAleatoria;
}


function handleFile(files) {
    if (files.length > 0) {
        for (let i = 0; i < files.length; i++) {
            const files = e.dataTransfer.files;
            handleFile(files);  
        }
    }


    fetch('index.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(data => {
        console.log(data);
        // Aquí puedes mostrar un mensaje de éxito o realizar otras acciones después de la subida
    })
    .catch(error => {
        console.error('Error al subir los archivos:', error);
        // Aquí puedes mostrar un mensaje de error
    });
}