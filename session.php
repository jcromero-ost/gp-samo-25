<?php
// Verifica si no se ha iniciado ninguna sesión aún
if (session_status() === PHP_SESSION_NONE) {
    session_start(); // Inicia la sesión si aún no está activa
}

// Verifica si no existe una variable de sesión 'user' (es decir, el usuario no ha iniciado sesión)
if (!isset($_SESSION['user'])) {
    // Redirige al usuario a la página de login si no está autenticado
    header('Location: /login');
    exit(); // Detiene la ejecución del script para evitar que continúe cargando contenido protegido
}
