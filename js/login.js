document.addEventListener('DOMContentLoaded', function() {
    const loginForm = document.getElementById('loginForm');
    if (loginForm) {
        loginForm.addEventListener('submit', function(event) {
            // Aquí puedes agregar lógica de validación de formulario si es necesaria
            // antes de que el formulario se envíe a procesar_login.php
            console.log('Formulario de login enviado.');
        });
    }
});
