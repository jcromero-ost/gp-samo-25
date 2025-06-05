<?php
    // Incluye el archivo 'session.php' que probablemente gestiona la sesión del usuario
    require_once __DIR__ . '/../session.php';

    // Asigna a la variable $view la ruta completa de este mismo archivo
    $view = __FILE__;

    // Incluye el layout principal, que usa $view para insertar este contenido dentro de una plantilla
    include __DIR__ . '/layout.php';
?>
