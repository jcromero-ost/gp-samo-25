<?php
// Incluye el archivo de conexión a la base de datos
require_once __DIR__ . '/DatabaseLOCAL.php';

class Auth {
    private $db; // Propiedad privada para guardar la conexión a la base de datos

    public function __construct() {
        // Al instanciar la clase, se realiza la conexión a la base de datos
        $this->db = DatabaseLOCAL::connect();
    }

    // Método que verifica si el usuario existe y la contraseña es válida
    public function authenticate($identifier, $password) {
        // Prepara una consulta segura para buscar por email o alias
        $stmt = $this->db->prepare("SELECT * FROM usuarios WHERE email = :identifier OR alias = :identifier LIMIT 1");
        // Asocia el valor del identificador (email o alias) al parámetro :identifier
        $stmt->bindParam(':identifier', $identifier, PDO::PARAM_STR);
        $stmt->execute();

        // Obtiene el primer registro coincidente
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }

        return false;
    }

}
