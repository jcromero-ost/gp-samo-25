<?php

require_once __DIR__ . '/../../models/Articulo.php';

// Crea un objeto del modelo
$articuloModel = new Articulo();
$articulos = $articuloModel->getAllArticulos(); //Llama a la funcion para mostrar todos los articulos

// Define la ruta al archivo de contenido específico de la vista actual
$view = __DIR__ . '/articulos_content.php';

// Incluye el archivo de layout principal, que usa $view para insertar el contenido dinámicamente
include __DIR__ . '/../layout.php';