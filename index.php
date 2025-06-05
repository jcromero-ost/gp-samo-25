<?php
require_once 'config/config.php'; 
// Incluye la clase Router, que contiene la lógica para definir y manejar rutas
require_once 'Router.php';

// Crea una instancia del enrutador
$router = new Router();

// Carga el archivo donde se definen las rutas de la aplicación
require_once 'routes/web.php'; 

// Ejecuta el despachador del enrutador: determina qué ruta se ha solicitado y ejecuta su acción correspondiente
$router->dispatch();
         
