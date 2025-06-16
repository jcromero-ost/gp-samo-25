<?php
// Incluye el modelo Articulo, que contiene la lógica de acceso a la base de datos
require_once __DIR__ . '/../models/LineasPedido.php';

// Carga el archivo de configuración general del proyecto, útil para constantes como BASE_URL
require_once __DIR__ . '/../config/config.php';

class LineasPedidoController {

    // Método para visualizar las lineas de un pedido
    public function ver_lineas()
    {
        if (!isset($_POST['CLAPED'])) {
            http_response_code(400);
            echo json_encode(['error' => 'ID de pedido no especificado']);
            return;
        }

        $claped = $_POST['CLAPED'] ?? '';
        $offset = isset($_POST['offset']) ? (int)$_POST['offset'] : 0;
        $limit = isset($_POST['limit']) ? (int)$_POST['limit'] : 10;

        error_log('CLAPED recibido: ' . $_POST['CLAPED']);

        $pedidoModel = new LineasPedido();

        $lineas = $pedidoModel->getLineasPedido($claped, $offset, $limit);
        $totalLineas = $pedidoModel->getTotalLineasPedido($claped); // necesitas crear este método

        header('Content-Type: application/json');
        echo json_encode([
            'data' => $lineas,
            'total' => $totalLineas
        ]);
    }
}