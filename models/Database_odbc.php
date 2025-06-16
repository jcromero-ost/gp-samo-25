<?php
class Database {
    public static function connect() {
        try {
            $dsn = "odbc:dbf_samo"; // El nombre que pusiste en el paso anterior
            $username = ""; // No requiere usuario
            $password = ""; // No requiere contraseña
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ];
            return new PDO($dsn, $username, $password, $options);
        } catch (PDOException $e) {
            die("Error de conexión ODBC: " . $e->getMessage());
        }
    }
}
