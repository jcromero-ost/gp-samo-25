<?php
// Incluye el archivo de conexión a la base de datos
require_once __DIR__ . '/Database.php';

// Definición de la clase Ejercicio, que se encarga de manejar operaciones sobre la tabla 'ejercicios'
class Ejercicio {
    private $reader;
    private $ruta;

    public function __construct() {
        $this->ruta = "C:\\SAMO\\ClasGes6SP26\\DATOS\\EJERCIC.dbf";
        $this->reader = new DBFReader($this->ruta);
    }

    public function getAllEjercicios() {
        $ejercicios = $this->reader->getRecords();

        // Ordenar por 'CLAEJE' descendente si el campo existe
        usort($ejercicios, function ($a, $b) {
            return strcmp($b['CLAEJE'], $a['CLAEJE']);
        });

        return $ejercicios;
    } 
}
