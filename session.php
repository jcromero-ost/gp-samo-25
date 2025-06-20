<?php
// Carga el archivo de configuración general del proyecto, útil para constantes como BASE_URL
require_once __DIR__ . '/config/config.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Detecta el URI actual, por ejemplo '/login'
$currentUri = $_SERVER['REQUEST_URI'] ?? '';

// Si el usuario no está logueado y no está en la página de login, redirige
if (!isset($_SESSION['user']) && strpos($currentUri, '/login') === false) {
    header('Location: ' . BASE_URL . '/login');
    exit();
}

