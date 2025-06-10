<?php
require_once __DIR__ . '/../../models/Cliente.php';

    $clienteModel = new Cliente();
    $ultimo_codigo = $clienteModel->getLastCodigo() + 1;

// Define la ruta al archivo de contenido específico de la vista actual
$view = __DIR__ . '/clientes_crear_content.php';

// Incluye el archivo de layout principal, que usa $view para insertar el contenido dinámicamente
include __DIR__ . '/../layout.php';

