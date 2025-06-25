<?php
// Incluye el modelo Articulo, que contiene la lógica de acceso a la base de datos
require_once __DIR__ . '/../models/Articulo.php';

// Carga el archivo de configuración general del proyecto, útil para constantes como BASE_URL
require_once __DIR__ . '/../config/config.php';

class ArticuloController {
    private $articulo;

    public function __construct() {
        $this->articulo = new Articulo();
    }

    // Método para las sugerencias de los articulos
    public function buscar()
    {
        header('Content-Type: application/json');

        $q = isset($_POST['q']) ? trim($_POST['q']) : '';

        if ($q === '') {
            echo json_encode([]);
            exit;
        }

        $articulos = $this->articulo->buscarPorCodigoONombre($q);

        $resultado = array_map(function($art) {
            return [
                'codigo' => $art['CODIGO'],
                'nombre' => $art['NOMBRE']
            ];
        }, $articulos);

        echo json_encode($resultado);
    }

    public function store($datos) {
        session_start();
        // Inserta el artículo
        try {
            $this->articulo = new Articulo();
            $this->articulo->insertArticulo($datos);

            $_SESSION['success'] = 'Artículo creado correctamente.';
            header('Location: ' . BASE_URL . '/articulos');
            exit;
        } catch (Exception $e) {
            $_SESSION['error'] = 'Error al insertar el artículo: ' . $e->getMessage();
            header('Location: ' . BASE_URL . '/articulos_crear');
            exit;
        }
    }
}
