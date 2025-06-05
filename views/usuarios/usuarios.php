<?php
// Crea un objeto del modelo
$usuarioModel = new Usuario();
$usuarios = $usuarioModel->getAllUsuarios(); //Llama a la funcion para mostrar todos los usuarios

// Define la ruta al archivo de contenido específico de la vista actual
$view = __DIR__ . '/usuarios_content.php';

// Incluye el archivo de layout principal, que usa $view para insertar el contenido dinámicamente
include __DIR__ . '/../layout.php';
