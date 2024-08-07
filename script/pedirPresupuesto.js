document.getElementById('miFormulario').addEventListener('submit', function(event) {
    event.preventDefault(); // Evita el envío real del formulario
    document.getElementById('mensajeExito').classList.remove('hidden'); // Muestra el mensaje de éxito
    // Aquí puedes agregar código adicional para enviar el formulario mediante AJAX, si es necesario
});