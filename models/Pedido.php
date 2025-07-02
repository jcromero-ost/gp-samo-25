<?php
// Incluye el archivo de conexión a la base de datos
require_once __DIR__ . '/Database.php';
require_once __DIR__ . '/DatabaseLOCAL.php';

// Definición de la clase Pedido, que se encarga de manejar operaciones sobre la tabla 'pedidos'
class Pedido {
    private $reader;

    public function __construct() {
        $this->reader = new DBFReader('C:\\SAMO\\ClasGes6SP26\\DATOS\\pedido.dbf', 'CP1252');
    }

    // Método que devuelve todos los pedidos
    public function getAllPedidos($offset = 0, $limit = 10) {
        $pedidos = $this->reader->getRecords($offset, $limit);

        // Ordenar por 'NUMERO' descendente si el campo existe (puede ser una fecha)
        usort($pedidos, function ($a, $b) {
            return (int)$b['NUMERO'] <=> (int)$a['NUMERO'];
        });

        return $pedidos;
    }

    public function getTotal() {
        return $this->reader->getRecordCount();
    }

    // Pedidos por ejercicio
    public function getPedidosPorEjercicio($claeje, $offset = 0, $limit = 10) {
        $pedidos = array_filter($this->reader->getRecords(), function ($p) use ($claeje) {
            return isset($p['CLAEJE']) && (int)$p['CLAEJE'] === (int)$claeje;
        });

        // Ordenar por NUMERO descendente
        usort($pedidos, function ($a, $b) {
            return (int)$b['NUMERO'] <=> (int)$a['NUMERO'];
        });

        return array_slice($pedidos, $offset, $limit);
    }

    public function getTotalPorEjercicio($claeje) {
        $pedidos = array_filter($this->reader->getRecords(), function ($p) use ($claeje) {
            return isset($p['CLAEJE']) && (int)$p['CLAEJE'] === (int)$claeje;
        });

        return count($pedidos);
    }

    public function actualizarEjercicioPredeterminado($userId, $claeje) {
        try {
            $reader = DatabaseLOCAL::connect(); // Asume que tienes Database::connect()
            $stmt = $reader->prepare("UPDATE usuarios SET ejercicio_predeterminado = ? WHERE id = ?");
            return $stmt->execute([(int)$claeje, (int)$userId]);
        } catch (PDOException $e) {
            error_log("Error al actualizar ejercicio predeterminado: " . $e->getMessage());
            return false;
        }
    }

}
