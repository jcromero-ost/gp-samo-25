<?php
// Incluye el archivo de conexión a la base de datos
require_once __DIR__ . '/DatabaseLOCAL.php';

// Definición de la clase Escandalllo, que se encarga de manejar operaciones sobre la tabla 'escandallos'
class Escandallo {
    private $db; // Propiedad privada que almacenará la conexión a la base de datos

    // Constructor: se ejecuta al instanciar la clase
    public function __construct() {
        $this->db = DatabaseLOCAL::connect(); // Obtiene la conexión a la base de datos desde DatabaseLOCAL
    }

    // Método para obtener todos los escandallos ordenados por nombre ascendentemente
    public function getAllEscandallos() {
        $stmt = $this->db->prepare("SELECT * FROM escandallos ORDER BY id ASC"); // Prepara la consulta SQL
        $stmt->execute(); // Ejecuta la consulta
        return $stmt->fetchAll(PDO::FETCH_ASSOC); // Retorna todos los resultados como un array asociativo
    }

    // Método para obtener todos los escandallos por código padre
    public function getAllEscandallosByCodigoPadre($codigo_padre) {
        $stmt = $this->db->prepare("SELECT * FROM escandallos WHERE codigo_articulo_padre = :codigo_padre ORDER BY id ASC");
        $stmt->bindParam(':codigo_padre', $codigo_padre, PDO::PARAM_STR); // Usa STR si el código no es estrictamente numérico
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getMateriasPrimasConDatosDelArticulo($codigoPadre) {
        // 1. Obtener códigos hijos Y cantidades desde la DB MySQL
        $stmt = $this->db->prepare("SELECT codigo_articulo, cantidad FROM escandallos WHERE codigo_articulo_padre = :codigoPadre");
        $stmt->execute(['codigoPadre' => $codigoPadre]);
        $filas = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (empty($filas)) {
            return [];
        }

        // 2. Extraer códigos y mapear cantidades
        $codigosHijos = array_column($filas, 'codigo_articulo');
        $cantidades = [];
        foreach ($filas as $fila) {
            $cantidades[$fila['codigo_articulo']] = $fila['cantidad'];
        }

        // 3. Obtener artículos desde el modelo Articulo
        require_once __DIR__ . '/Articulo.php';
        $articuloModel = new Articulo();
        $materias = $articuloModel->getArticulosPorCodigos($codigosHijos);

        // 4. Agregar cantidad al resultado final
        foreach ($materias as &$materia) {
            $codigo = trim($materia['CODIGO'] ?? '');
            $materia['CANTIDAD'] = $cantidades[$codigo] ?? 0;
        }

        return $materias;
    }

    // Obtener paginado
    public function getMateriasPrimasConPaginacion($codigoPadre, $limit, $offset) {
        $stmt = $this->db->prepare("SELECT codigo_articulo, cantidad FROM escandallos WHERE codigo_articulo_padre = :codigoPadre LIMIT :limit OFFSET :offset");
        $stmt->bindValue(':codigoPadre', $codigoPadre, PDO::PARAM_STR);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        $stmt->execute();
        $filas = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (empty($filas)) return [];

        $codigosHijos = array_column($filas, 'codigo_articulo');
        $cantidades = [];
        foreach ($filas as $fila) {
            $cantidades[$fila['codigo_articulo']] = $fila['cantidad'];
        }

        require_once __DIR__ . '/Articulo.php';
        $articuloModel = new Articulo();
        $materias = $articuloModel->getArticulosPorCodigos($codigosHijos);

        foreach ($materias as &$materia) {
            $codigo = trim($materia['CODIGO'] ?? '');
            $materia['CANTIDAD'] = $cantidades[$codigo] ?? 0;
        }

        return $materias;
    }

    public function countMateriasPrimas($codigoPadre) {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM escandallos WHERE codigo_articulo_padre = :codigoPadre");
        $stmt->execute(['codigoPadre' => $codigoPadre]);
        return (int)$stmt->fetchColumn();
    }



    // Método para crear un nuevo escandallo
    public function create($data) {
        // Verifica si ya existe el registro con ese padre y artículo
        $check = $this->db->prepare("
            SELECT COUNT(*) as total FROM escandallos 
            WHERE codigo_articulo_padre = :codigo_articulo_padre 
            AND codigo_articulo = :codigo_articulo
        ");
        $check->bindParam(':codigo_articulo_padre', $data['codigo_articulo_padre']);
        $check->bindParam(':codigo_articulo', $data['codigo_articulo']);
        $check->execute();

        $result = $check->fetch(PDO::FETCH_ASSOC);
        if ($result['total'] > 0) {
            // Ya existe, no insertar
            return false;
        }

        // Si no existe, inserta el nuevo escandallo
        $stmt = $this->db->prepare("
            INSERT INTO escandallos 
                (codigo_articulo_padre, codigo_articulo, cantidad)
            VALUES 
                (:codigo_articulo_padre, :codigo_articulo, :cantidad)
        ");
        $stmt->bindParam(':codigo_articulo_padre', $data['codigo_articulo_padre']);
        $stmt->bindParam(':codigo_articulo', $data['codigo_articulo']);
        $stmt->bindParam(':cantidad', $data['cantidad']);
        return $stmt->execute();
    }


    // Método para borrar un usuario
    public function delete($data) {    
        // Prepara la consulta SQL de inserción
        $stmt = $this->db->prepare("DELETE FROM escandallos WHERE codigo_articulo_padre = :codigo_padre AND codigo_articulo = :codigo_articulo");
    
        // Asocia los parámetros con los valores del array $data (protege contra inyecciones SQL)
        $stmt->bindParam(':codigo_padre', $data['codigo_articulo_padre']);
        $stmt->bindParam(':codigo_articulo', $data['codigo_articulo']);
    
        // Ejecuta la consulta y devuelve true si tuvo éxito, false si falló
        return $stmt->execute();
    }
    
    // Método opcional para obtener un usuario por su ID
    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM usuarios WHERE id = :id"); // Prepara la consulta
        $stmt->bindParam(':id', $id, PDO::PARAM_INT); // Asocia el parámetro :id con el valor $id
        $stmt->execute(); // Ejecuta la consulta
        return $stmt->fetch(PDO::FETCH_ASSOC); // Devuelve un único resultado como array asociativo
    }    
}
