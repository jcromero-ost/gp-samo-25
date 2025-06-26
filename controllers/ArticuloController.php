<?php
// Incluye el modelo Articulo, que contiene la lógica de acceso a la base de datos
require_once __DIR__ . '/../models/Articulo.php';

// Carga el archivo de configuración general del proyecto, útil para constantes como BASE_URL
require_once __DIR__ . '/../config/config.php';

// Definición del controlador ArticuloController
class ArticuloController {
    private $articulo;

    // Constructor que inicializa la propiedad $articulo con una nueva instancia del modelo Articulo
    public function __construct() {
        $this->articulo = new Articulo();
    }

    // Método para realizar búsquedas de artículos por código o nombre
    public function buscar() {
        // Indica que la respuesta será en formato JSON
        header('Content-Type: application/json');

        // Obtiene el parámetro 'q' del POST, eliminando espacios en blanco
        $q = isset($_POST['q']) ? trim($_POST['q']) : '';

        // Si la consulta está vacía, retorna un arreglo JSON vacío
        if ($q === '') {
            echo json_encode([]);
            exit;
        }

        // Llama al método del modelo para buscar artículos que coincidan con el código o nombre
        $articulos = $this->articulo->buscarPorCodigoONombre($q);

        // Transforma el resultado a un formato más simple (solo código y nombre)
        $resultado = array_map(function($art) {
            return [
                'codigo' => $art['CODIGO'],
                'nombre' => $art['NOMBRE']
            ];
        }, $articulos);

        // Devuelve el resultado como JSON
        echo json_encode($resultado);
    }

    // Método para guardar un nuevo artículo
    public function store($datos) {
        // Inicia la sesión para poder usar variables de sesión
        session_start();

        // Inserta el artículo
        try {
            // Crea una nueva instancia del modelo Articulo
            $this->articulo = new Articulo();

            // Llama al método para insertar el artículo con los datos proporcionados
            $this->articulo->insertArticulo($datos);

            // Guarda un mensaje de éxito en la sesión
            $_SESSION['success'] = 'Artículo creado correctamente.';

            // Redirige a la lista de artículos
            header('Location: ' . BASE_URL . '/articulos');
            exit;
        } catch (Exception $e) {
            // Si ocurre un error, guarda el mensaje en la sesión
            $_SESSION['error'] = 'Error al insertar el artículo: ' . $e->getMessage();

            // Redirige a la vista de creación de artículos
            header('Location: ' . BASE_URL . '/articulos_crear');
            exit;
        }
    }
}
