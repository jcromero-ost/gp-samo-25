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
            echo json_encode(["error" => "Código no proporcionado"]);
            exit;
        }

        $escandalloModel = new Escandallo();
        $materias = $escandalloModel->getMateriasPrimasConDatosDelArticulo($codigo);

        if (!is_array($materias)) {
            echo json_encode(["error" => "No se pudo obtener materias"]);
            exit;
        }

        echo json_encode($materias);
    }

    public function obtenerMateriasPorCodigoPadrePaginadas() {
        header('Content-Type: application/json');

        $codigo = $_POST['codigo'] ?? '';
        $page = isset($_POST['page']) ? max(1, intval($_POST['page'])) : 1;
        $limit = isset($_POST['limit']) ? intval($_POST['limit']) : 5;
        $offset = ($page - 1) * $limit;

        if (!$codigo) {
            echo json_encode(["error" => "Código no proporcionado"]);
            exit;
        }

        $escandalloModel = new Escandallo();
        $materias = $escandalloModel->getMateriasPrimasConPaginacion($codigo, $limit, $offset);
        $total = $escandalloModel->countMateriasPrimas($codigo);
        $totalPaginas = max(1, ceil($total / $limit));

        echo json_encode([
            'materias' => $materias,
            'page' => $page,
            'totalPaginas' => $totalPaginas
        ]);
    }

    // Método para crear un nuevo escandallo (usualmente al enviar un formulario)
    public function store() {
        session_start();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $codigo_articulo_padre = $_POST['codigo_articulo_padre'] ?? '';
            $codigo_articulo = $_POST['codigo_articulo'] ?? '';
            $cantidad = $_POST['cantidad'] ?? '';

            $escandallo = new Escandallo();
            $exito = $escandallo->create([
                'codigo_articulo_padre' => $codigo_articulo_padre,
                'codigo_articulo' => $codigo_articulo,
                'cantidad' => $cantidad
            ]);

            // Si es una solicitud AJAX, respondemos con JSON
            if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
                header('Content-Type: application/json');

                if ($exito) {
                    echo json_encode([
                        'success' => true,
                        'message' => 'Materia asignada correctamente'
                    ]);
                } else {
                    http_response_code(409); // Conflicto
                    echo json_encode([
                        'success' => false,
                        'message' => 'Esta materia ya está asignada al escandallo'
                    ]);
                }
                exit;
            }
        }
    }

    // Método para crear un nuevo escandallo
    public function delete() {
        session_start();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $codigo_articulo_padre = $_POST['codigo_articulo_padre'] ?? '';
            $codigo_articulo = $_POST['codigo_articulo'] ?? '';

            $escandallo = new Escandallo();
            $exito = $escandallo->delete([
                'codigo_articulo_padre' => $codigo_articulo_padre,
                'codigo_articulo' => $codigo_articulo
            ]);

            // Si es una solicitud AJAX, respondemos con JSON
            if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
                header('Content-Type: application/json');

                if ($exito) {
                    echo json_encode([
                        'success' => true,
                        'message' => 'Materia eliminada correctamente'
                    ]);
                } else {
                    echo json_encode([
                        'success' => false,
                        'message' => 'Error al eliminar la materia'
                    ]);
                }
                exit;
            }
        }
    }


}