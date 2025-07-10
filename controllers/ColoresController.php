<?php
// Incluye el modelo Escandalllo, que contiene la lógica de acceso a la base de datos
require_once __DIR__ . '/../models/Colores.php';

// Carga el archivo de configuración general del proyecto, útil para constantes como BASE_URL
require_once __DIR__ . '/../config/config.php';

class ColoresController {

    // Método para crear un nuevo colot (usualmente al enviar un formulario)
    public function obtenerColoresPorCodigoPadre() {
        header('Content-Type: application/json');

        $codigo = $_POST['codigo'] ?? '';
        $page = isset($_POST['page']) ? max(1, intval($_POST['page'])) : 1;
        $limit = 5;
        $offset = ($page - 1) * $limit;

        if (!$codigo) {
            echo json_encode(["error" => "Código no proporcionado"]);
            exit;
        }

        $coloresModel = new Colores();
        $colores = $coloresModel->getColoresConPaginacion($codigo, $limit, $offset);
        $total = $coloresModel->countColores($codigo);
        $totalPaginas = max(1, ceil($total / $limit));

        echo json_encode([
            'colores' => $colores,
            'page' => $page,
            'totalPaginas' => $totalPaginas
        ]);
    }

}