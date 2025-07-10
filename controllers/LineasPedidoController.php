<?php
// Incluye el modelo LineasPedido, que contiene la lógica de acceso a la base de datos
require_once __DIR__ . '/../models/LineasPedido.php';

// Carga el archivo de configuración general del proyecto, útil para constantes como BASE_URL
require_once __DIR__ . '/../config/config.php';

// Definición del controlador LineasPedidoController
class LineasPedidoController {

    // Método para visualizar las líneas de un pedido
    public function ver_lineas()
    {
        $orden = $_POST['orden_fabricacion'] ?? '';

        // Verifica que se haya enviado el ID del pedido (CLAPED) por POST
        if (!isset($_POST['CLAPED'])) {
            // Si no se envía, devuelve un error HTTP 400 (Bad Request) con un mensaje en formato JSON
            http_response_code(400);
            echo json_encode(['error' => 'ID de pedido no especificado']);
            return;
        }

        // Obtiene el valor del ID del pedido
        $claped = $_POST['CLAPED'] ?? '';

        // Obtiene el offset (desplazamiento) para paginación, por defecto es 0
        $offset = isset($_POST['offset']) ? (int)$_POST['offset'] : 0;

        // Obtiene el límite de registros a consultar, por defecto es 10
        $limit = isset($_POST['limit']) ? (int)$_POST['limit'] : 10;

        // Registra en el log el valor recibido del ID del pedido (útil para depuración)
        error_log('CLAPED recibido: ' . $_POST['CLAPED']);

        // Crea una instancia del modelo LineasPedido
        $pedidoModel = new LineasPedido();

        // Obtiene las líneas del pedido a partir del modelo, usando paginación
        $lineas = $pedidoModel->getLineasPedido($claped, $offset, $limit);

        // Obtiene el total de líneas del pedido (este método debe existir en el modelo)
        $totalLineas = $pedidoModel->getTotalLineasPedido($claped); // necesitas crear este método

        // Indica que la respuesta será en formato JSON
        header('Content-Type: application/json');

        foreach ($lineas as &$linea) {
            foreach ($linea as $campo => &$valor) {
                if (!mb_check_encoding($valor, 'UTF-8')) {
                    error_log("Error de codificación en línea con CLAPED=$claped, campo=$campo");
                    $valor = ''; // o intenta convertir: $valor = mb_convert_encoding($valor, 'UTF-8', 'CP1252');
                }
            }
        }

        // Devuelve los datos de las líneas del pedido y el total en formato JSON
        echo json_encode([
            'data' => $lineas,
            'total' => $totalLineas
        ]);
    }
}
