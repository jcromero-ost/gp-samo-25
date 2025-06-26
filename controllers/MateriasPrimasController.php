<?php
// Incluye el modelo MateriasPrimas, que contiene la lógica de acceso a la base de datos
require_once __DIR__ . '/../models/MateriasPrimas.php';

// Carga el archivo de configuración general del proyecto, útil para constantes como BASE_URL
require_once __DIR__ . '/../config/config.php';

// Definición del controlador MateriasPrimasController
class MateriasPrimasController {

    // Método para visualizar las materias primas asociadas a un producto padre
    public function ver_materias()
    {
        // Verifica que se haya enviado el código del producto padre (CODPADRE) mediante POST
        if (!isset($_POST['CODPADRE'])) {
            // Si no se proporciona, retorna un error HTTP 400 y un mensaje en JSON
            http_response_code(400);
            echo json_encode(['error' => 'ID de pedido no especificado']);
            return;
        }

        // Obtiene el valor de CODPADRE desde POST, con valor por defecto vacío si no está
        $codpadre = $_POST['CODPADRE'] ?? '';

        // Obtiene el valor del offset para paginación; por defecto, 0
        $offset = isset($_POST['offset']) ? (int)$_POST['offset'] : 0;

        // Obtiene el límite de registros por página; por defecto, 10
        $limit = isset($_POST['limit']) ? (int)$_POST['limit'] : 10;

        // Escribe en el log el valor recibido para depuración
        error_log('CODPADRE recibido: ' . $_POST['CODPADRE']);

        // Crea una instancia del modelo MateriasPrimas
        $pedidoModel = new MateriasPrimas();

        // Llama al método del modelo para obtener las materias primas del producto padre
        $materias = $pedidoModel->getMateriasPrimas($codpadre, $offset, $limit);

        // Llama al método que devuelve el total de materias primas (debe estar implementado)
        $totalMaterias = $pedidoModel->getTotalMateriasPrimas($codpadre); // necesitas crear este método

        // Establece el tipo de contenido de la respuesta como JSON
        header('Content-Type: application/json');

        // Devuelve la respuesta con los datos y el total en formato JSON
        echo json_encode([
            'data' => $materias,
            'total' => $totalMaterias
        ]);
    }
}
