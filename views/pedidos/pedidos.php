<?php

require_once __DIR__ . '/../../models/Pedido.php';

// Crea un objeto del modelo
$pedidoModel = new Pedido();
$pedidos = $pedidoModel->getAllPedidos(); //Llama a la funcion para mostrar todos los pedidos


// Define la ruta al archivo de contenido específico de la vista actual
$view = __DIR__ . '/pedidos_content.php';

// Incluye el archivo de layout principal, que usa $view para insertar el contenido dinámicamente
include __DIR__ . '/../layout.php';