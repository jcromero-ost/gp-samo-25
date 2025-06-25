<?php

require_once __DIR__ . '/../../models/Articulo.php';

// Crea un objeto del modelo
$articuloModel = new Articulo();

// Obtiene el total de registros disponibles
$totalRegistros = $articuloModel->getTotal();

$ultimoCLAART = $articuloModel->obtenerUltimoCodigoCLAART();
$nuevoCLAART = $ultimoCLAART + 1;


// Define la ruta al archivo de contenido específico de la vista actual
$view = __DIR__ . '/articulos_crear_content.php';

// Incluye el archivo de layout principal, que usa $view para insertar el contenido dinámicamente
include __DIR__ . '/../layout.php';