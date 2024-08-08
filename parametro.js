// Obtén la URL actual
const urlActual = window.location.href;

// Extrae la última parte de la URL para determinar si ya hay un nombre de carpeta
const urlPartes = urlActual.split('/');
let carpetaNombre = urlPartes[urlPartes.length - 1];

// Si no hay un nombre de carpeta, genera uno nuevo y redirige
if (!carpetaNombre || carpetaNombre.includes("?")) {
    carpetaNombre = generarCadenaAleatoria();
    const nuevaUrl = urlActual.endsWith('/') ? `${urlActual}${carpetaNombre}` : `${urlActual}/${carpetaNombre}`;
    window.location.href = nuevaUrl;
}

// Función para generar una cadena aleatoria
function generarCadenaAleatoria() {
    const caracteres = 'abcdefghijklmnopqrstuvwxyz0123456789';
    let cadenaAleatoria = '';
    for (let i = 0; i < 3; i++) {
        const caracterAleatorio = caracteres.charAt(Math.floor(Math.random() * caracteres.length));
        cadenaAleatoria += caracterAleatorio;
    }
    return cadenaAleatoria;
}

// Zona de arrastre de archivos y manejo de eventos
const dropArea = document.getElementById('drop-area');
const form = document.getElementById('form');

dropArea.addEventListener('dragover', (e) => {
    e.preventDefault();
    dropArea.classList.add('drag-over');
});

dropArea.addEventListener('dragleave', () => {
    dropArea.classList.remove('drag-over');
});

dropArea.addEventListener('drop', (e) => {
    e.preventDefault();
    dropArea.classList.remove('drag-over');
    const file = e.dataTransfer.files[0];
    handleFile(file);
});

// Función para manejar el archivo seleccionado
function handleFile(file) {
    if (file) {
        console.log('Archivo seleccionado:', file.name);
        const progressBar = document.querySelector('.file-progress');
        const xhr = new XMLHttpRequest();

        xhr.upload.addEventListener('progress', (event) => {
            if (event.lengthComputable) {
                const percentComplete = (event.loaded / event.total) * 100;
                progressBar.value = percentComplete;
            }
        });
    }
}

// Maneja el envío del formulario para subir archivos
form.addEventListener('submit', (e) => {
    e.preventDefault();
    const fileInput = form.querySelector('#archivo');
    const file = fileInput.files[0];
    if (file) {
        console.log('Subir archivo:', file.name);
    } else {
        alert('Por favor, seleccione un archivo primero.');
    }
});
