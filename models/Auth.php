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
    public function authenticate($email, $password) {
        // Prepara una consulta segura (evita SQL Injection)
        $stmt = $this->db->prepare("SELECT * FROM usuarios WHERE email = :email LIMIT 1");
        // Asocia el valor del email al parámetro :email
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute(); // Ejecuta la consulta

        // Obtiene el primer (y único) registro coincidente como un array asociativo
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Validación básica (actualmente solo verifica que haya usuario y contraseña no vacía)
        if ($user && $password) {
            return $user; // Retorna los datos del usuario si se encuentra y hay password (aunque no la verifica aún)
        }

        // forma segura de verificar contraseñas con hash
        // if ($user && password_verify($password, $user['password'])) {
        //     return $user; // Retorna los datos si la contraseña en texto plano coincide con el hash
        // }

        return false; // Retorna false si el usuario no se encuentra o la contraseña no coincide
    }
}
