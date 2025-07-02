<?php
session_start();

// Crea un objeto del modelo
$usuarioModel = new Usuario();
$usuario = $usuarioModel->getById($_SESSION['id']); //Llama a la funcion para mostrar todos los usuarios

// Crea un objeto del modelo de departamentos
$departamentoModel = new Departamento();
$departamentos = $departamentoModel->getAllDepartamentos(); //Llama a la funcion para mostrar todos los departamentos

// Define la ruta al archivo de contenido específico de la vista actual
$view = __DIR__ . '/perfil_editar_content.php';

// Incluye el archivo de layout principal, que usa $view para insertar el contenido dinámicamente
include __DIR__ . '/../layout.php';
