<?php
// Incluye el modelo Articulo, que contiene la lógica de acceso a la base de datos
require_once __DIR__ . '/../models/MateriasPrimas.php';

// Carga el archivo de configuración general del proyecto, útil para constantes como BASE_URL
require_once __DIR__ . '/../config/config.php';

class MateriasPrimasController {

    // Método para visualizar las materias de un pedido
    public function ver_materias()
    {
        if (!isset($_POST['CODPADRE'])) {
            http_response_code(400);
            echo json_encode(['error' => 'ID de pedido no especificado']);
            return;
        }

        $codpadre = $_POST['CODPADRE'] ?? '';
        $offset = isset($_POST['offset']) ? (int)$_POST['offset'] : 0;
        $limit = isset($_POST['limit']) ? (int)$_POST['limit'] : 10;

        error_log('CODPADRE recibido: ' . $_POST['CODPADRE']);

        $pedidoModel = new MateriasPrimas();

        $materias = $pedidoModel->getMateriasPrimas($codpadre, $offset, $limit);
        $totalMaterias = $pedidoModel->getTotalMateriasPrimas($codpadre); // necesitas crear este método

        header('Content-Type: application/json');
        echo json_encode([
            'data' => $materias,
            'total' => $totalMaterias
        ]);
    }
}