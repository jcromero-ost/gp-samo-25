<?php
// Incluye el modelo Escandalllo, que contiene la lógica de acceso a la base de datos
require_once __DIR__ . '/../models/Escandallo.php';

// Carga el archivo de configuración general del proyecto, útil para constantes como BASE_URL
require_once __DIR__ . '/../config/config.php';

class EscandalloController {

    // Método para crear un nuevo escandallo (usualmente al enviar un formulario)
    public function obtenerMateriasPorCodigoPadre() {
        header('Content-Type: application/json');

        $codigo = $_POST['codigo'] ?? '';

        if (!$codigo) {
            echo json_encode([]);
            exit;
        }

        $escandalloModel = new Escandallo();
        $materias = $escandalloModel->getMateriasPrimasConDatosDelArticulo($codigo);

        echo json_encode($materias);
    }

    // Método para crear un nuevo escandallo (usualmente al enviar un formulario)
    public function store() {
        session_start(); // Inicia o reanuda la sesión (necesario para usar $_SESSION)

        // Solo procesa si la solicitud es por POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Obtiene los datos enviados por el formulario, con valores por defecto si no existen
            $codigo_articulo_padre = $_POST['codigo_articulo_padre'] ?? '';
            $codigo_articulo = $_POST['codigo_articulo'] ?? '';

            // Crea un nuevo escandallo usando el modelo Escandallo
            $escandallo = new Escandallo();
            $escandallo->create([
                'codigo_articulo_padre' => $codigo_articulo_padre,
                'codigo_articulo' => $codigo_articulo
            ]);

            // Mensaje de éxito y redirección
            $_SESSION['success'] = 'Escandallo creado correctamente.';
            header('Location: ' . BASE_URL . '/escandallos_crear');
            exit;
        }

        // Si no es POST, redirige a la página de creación
        header('Location: ' . BASE_URL . '/escandallos_crear');
        exit;
    }
}