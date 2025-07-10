<?php
// Incluye el archivo de conexión a la base de datos
require_once __DIR__ . '/DatabaseLOCAL.php';

// Definición de la clase Pedido, que se encarga de manejar operaciones sobre la tabla 'pedidos'
class Pedido {
    private $db; // Propiedad para manejar el lector de archivos DBF

    // Constructor: inicializa el lector DBF apuntando al archivo 'articulo.dbf'
    public function __construct() {
        $this->db = DatabaseLOCAL::connect(); // Obtiene la conexión a la base de datos desde DatabaseLOCAL
    }

    // Método para obtener todos los pedidos ordenados por nombre ascendentemente
public function getAllPedidos($offset = 0, $limit = 15) {
    $stmt = $this->db->prepare("
        SELECT p.*, (
            SELECT COUNT(*) 
            FROM cg_pedidos_lineas l 
            WHERE l.CLAPED = p.CLAPED 
              AND l.CODIGO IS NOT NULL 
              AND l.CODIGO <> '' 
        ) AS materias_count
        FROM cg_pedidos p
        ORDER BY p.CLAPED ASC 
        LIMIT :offset, :limit
    ");
    $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
    $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


    // Devuelve el total de registros en el archivo DBF
    public function getTotal() {
        $stmt = $this->db->query("SELECT COUNT(*) as total FROM cg_pedidos");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int)$result['total'];
    }

    // Pedidos por ejercicio
public function getPedidosPorEjercicio($claeje, $offset = 0, $limit = 10, $orden_fabricacion = null) {
    $condicionExtra = '';

    if ($orden_fabricacion === 'con') {
        $condicionExtra = "AND l.COMENT IS NOT NULL AND l.COMENT <> ''";
    } elseif ($orden_fabricacion === 'sin') {
        $condicionExtra = "AND (l.COMENT IS NULL OR l.COMENT = '')";
    }

    $sql = "
        SELECT 
            p.NUMERO, p.CLAPED, p.NOMCLI, p.DIRCLI, p.LOCCLI, p.FECHA,
            COUNT(l.CLAPED) AS materias_count
        FROM cg_pedidos p
        LEFT JOIN cg_pedidos_lineas l 
            ON l.CLAPED = p.CLAPED 
            AND l.CODIGO IS NOT NULL 
            AND l.CODIGO <> '' 
            $condicionExtra
        WHERE p.CLAEJE = :claeje
        GROUP BY p.NUMERO, p.CLAPED, p.NOMCLI, p.DIRCLI, p.LOCCLI, p.FECHA
        ORDER BY p.NUMERO DESC
        LIMIT :offset, :limit
    ";

    $stmt = $this->db->prepare($sql);
    $stmt->bindValue(':claeje', (int)$claeje, PDO::PARAM_INT);
    $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
    $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

    public function countLineasPedido($claped) {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM cg_pedidos_lineas WHERE claped = :claped AND CODIGO IS NOT NULL AND CODIGO <> ''");
        $stmt->execute(['claped' => $claped]);
        return (int)$stmt->fetchColumn();
    }

    public function pedidoSinOrden(){
        $stmt = $this->db->prepare("SELECT * FROM cg_pedidos_lineas WHERE COMENT = ''");
        return $stmt->fetchColumn();
    }

    public function getTotalPorEjercicio($claeje) {
        $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM cg_pedidos WHERE CLAEJE = :claeje");
        $stmt->bindValue(':claeje', (int)$claeje, PDO::PARAM_INT);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int)$result['total'];
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
