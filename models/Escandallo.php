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
        // 1. Obtener códigos hijos desde la DB MySQL
        $stmt = $this->db->prepare("SELECT codigo_articulo FROM escandallos WHERE codigo_articulo_padre = :codigoPadre");
        $stmt->execute(['codigoPadre' => $codigoPadre]);
        $codigosHijos = $stmt->fetchAll(PDO::FETCH_COLUMN);

        if (empty($codigosHijos)) {
            return [];
        }

        // 2. Instancia de Articulo para filtrar con esos códigos
        require_once __DIR__ . '/Articulo.php';
        $articuloModel = new Articulo();

        // 3. Obtener artículos con datos completos
        $materias = $articuloModel->getArticulosPorCodigos($codigosHijos);

        return $materias;
    }

public function countMateriasPrimas($codigoPadre) {
    $stmt = $this->db->prepare("SELECT COUNT(*) FROM escandallos WHERE codigo_articulo_padre = :codigoPadre");
    $stmt->execute(['codigoPadre' => $codigoPadre]);
    return (int)$stmt->fetchColumn();
}


    // Método para crear un nuevo escandallo
    public function create($data) {    
        // Prepara la consulta SQL de inserción
        $stmt = $this->db->prepare("
            INSERT INTO escandallos 
                (codigo_articulo_padre, codigo_articulo)
            VALUES 
                (:codigo_articulo_padre, :codigo_articulo)
        ");
    
        // Asocia los parámetros con los valores del array $data (protege contra inyecciones SQL)
        $stmt->bindParam(':codigo_articulo_padre', $data['codigo_articulo_padre']);
        $stmt->bindParam(':codigo_articulo', $data['codigo_articulo']);
    
        // Ejecuta la consulta y devuelve true si tuvo éxito, false si falló
        return $stmt->execute();
    }

    
    // Método para actualizar un usuario
    public function update($data) {    
        // Prepara la consulta SQL de inserción
        $stmt = $this->db->prepare("
            UPDATE usuarios 
                SET nombre = :nombre, email= :email, alias= :alias, telefono= :telefono, departamento_id= :departamento_id
            WHERE id= :id
        ");
    
        // Asocia los parámetros con los valores del array $data (protege contra inyecciones SQL)
        $stmt->bindParam(':nombre', $data['nombre']);
        $stmt->bindParam(':email', $data['email']);
        $stmt->bindParam(':alias', $data['alias']);
        $stmt->bindParam(':telefono', $data['telefono']);
        $stmt->bindParam(':departamento_id', $data['departamento_id']);
        $stmt->bindParam(':id', $data['id']);
    
        // Ejecuta la consulta y devuelve true si tuvo éxito, false si falló
        return $stmt->execute();
    }

    // Método para borrar un usuario
    public function delete($data) {    
        // Prepara la consulta SQL de inserción
        $stmt = $this->db->prepare("DELETE FROM usuarios WHERE id= :id");
    
        // Asocia los parámetros con los valores del array $data (protege contra inyecciones SQL)
        $stmt->bindParam(':id', $data['id']);
    
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
