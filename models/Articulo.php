<?php
// Incluye el archivo de conexión a la base de datos (presumiblemente contiene la clase DBFReader)
require_once __DIR__ . '/Database.php';

// Definición de la clase Articulo, que se encarga de manejar operaciones sobre la tabla 'articulos'
class Articulo {
    private $reader; // Propiedad para manejar el lector de archivos DBF

    // Constructor: inicializa el lector DBF apuntando al archivo 'articulo.dbf'
    public function __construct() {
        $ruta = "C:\\SAMO\\ClasGes6SP26\\DATOS\\articulo.dbf";
        $this->reader = new DBFReader($ruta);
    }

    // Método para obtener todos los artículos con paginación (offset y limit)
    public function getAllArticulos($offset = 0, $limit = 10) {
        return $this->reader->getRecords($offset, $limit);
    }

    // Método para buscar artículos por código o nombre (búsqueda insensible a mayúsculas)
    public function buscarPorCodigoONombre($busqueda) {
        $busqueda = mb_strtolower($busqueda); // Convierte la búsqueda a minúsculas
        $todos = $this->reader->getRecords(); // Obtiene todos los registros

        // Filtra los artículos cuyos códigos o nombres coincidan parcialmente
        $coincidentes = array_filter($todos, function ($art) use ($busqueda) {
            return (
                isset($art['CODIGO']) && stripos($art['CODIGO'], $busqueda) !== false
            ) || (
                isset($art['NOMBRE']) && stripos($art['NOMBRE'], $busqueda) !== false
            );
        });

        // Reindexa el array filtrado y lo devuelve
        return array_values($coincidentes);
    }

    // Devuelve solo los artículos cuyos códigos estén en el array proporcionado
    public function getArticulosPorCodigos(array $codigos) {
        $todos = $this->reader->getRecords();
        $filtrados = array_filter($todos, function($art) use ($codigos) {
            return in_array($art['CODIGO'], $codigos);
        });
        return array_values($filtrados);
    }

    // Devuelve el total de registros en el archivo DBF
    public function getTotal() {
        return $this->reader->getRecordCount();
    }

    /*
    // Método para insertar un nuevo artículo (actualmente comentado)
    public function insertArticulo($data) {
        // Generar nuevo CLAART (clave del artículo)
        $ultimo = $this->obtenerUltimoCodigoCLAART();
        $nuevoCLAART = $ultimo + 1;
        $data['CLAART'] = $nuevoCLAART;

        // Rellena los campos que no estén definidos con valores por defecto
        $this->completarCamposPorDefecto($data);

        // Inserta el nuevo registro en el archivo DBF
        return $this->reader->insertRecord($data);
    }

    // Obtiene el valor máximo actual del campo CLAART para asignar el siguiente
    public function obtenerUltimoCodigoCLAART() {
        $registros = $this->reader->getRecords(0, $this->reader->getRecordCount());
        $max = 0;
        foreach ($registros as $registro) {
            if (isset($registro['CLAART']) && is_numeric($registro['CLAART'])) {
                $max = max($max, intval($registro['CLAART']));
            }
        }
        return $max;
    }

    // Rellena los campos faltantes con valores por defecto antes de guardar
    private function completarCamposPorDefecto(array &$datos) {
        $porDefecto = [
            'TYC' => 'T',
            'LOTES' => 'F',
            'CADUCA' => 'F',
            'ESCANDALLO' => 'F',
            'SERVICIO' => 'F',
            'MULTILIN' => 'T',
            'COMPONENTE' => 'F',
            'SERGAR' => 'F',
            'MULTIUNI' => 'F',
            'BAJA' => 'F',
            'OBLMODIF' => 'F',
            'CONIVA' => 'F',
            'AGRUPATASA' => 'F',
            'CLAPRINTER' => 0,
            'CLAMENUH' => 0,
            'CLAGCOCINA' => 0,
            'COMBINAR' => 'F',
            'NOAGRUPAR' => 'F',
            'CLATEMP' => 0,
            'CLATASA' => 0,
            'CLAMARCA' => 0,
            'CLAPV' => 0,
            'CLAFAM' => 0,
            'TIPOCOSTE' => 0,
            'PORCENT' => 0,
            'ORDEN' => 0,
            'PVPIVACOM1' => 0,
            'PVPIVACOM2' => 0,
            'PVPONLINE' => 0,
            'PVPOFERTA' => 0,
            'OFERTA' => 0,
            'PMP' => 0,
            'PVP1IVA' => 0,
            'PVP2IVA' => 0,
            'PVP3IVA' => 0,
            'PVP4IVA' => 0,
            'PVP5IVA' => 0,
            'PVP6IVA' => 0,
            'PVP7IVA' => 0,
            'PVP8IVA' => 0,
            'PVP9IVA' => 0,
            'PVP10IVA' => 0,
            'POSIMG' => 0,
            'CLAUNI' => 0,
            'GARMONTH' => 0,
            'CLAEQUIVA' => 0,
            'CLAPADRE' => 0,
            'CODPADRE' => '',
        ];

        // Asigna los valores por defecto a los campos faltantes
        foreach ($porDefecto as $campo => $valor) {
            if (!isset($datos[$campo]) || $datos[$campo] === '') {
                $datos[$campo] = $valor;
            }
        }

        // Si no hay código padre, se asigna el mismo código del artículo
        if (empty($datos['CODPADRE']) && isset($datos['CODIGO'])) {
            $datos['CODPADRE'] = $datos['CODIGO'];
        }

        // Si no hay descripción corta, se toma del nombre
        if (empty($datos['SHORTDESC']) && isset($datos['NOMBRE'])) {
            $datos['SHORTDESC'] = substr($datos['NOMBRE'], 0, 50);
        }

        // Si no hay descripción larga, también se genera a partir del nombre
        if (empty($datos['LONGDESC']) && isset($datos['NOMBRE'])) {
            $datos['LONGDESC'] = substr($datos['NOMBRE'], 0, 254);
        }
    }
    */
}
