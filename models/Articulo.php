<?php
// Incluye el archivo de conexión a la base de datos
require_once __DIR__ . '/Database.php';

// Definición de la clase Articulo, que se encarga de manejar operaciones sobre la tabla 'articulos'
class Articulo {
    private $reader;

    public function __construct() {
        $ruta = "C:\\SAMO\\ClasGes6SP26\\DATOS\\articulo.dbf";
        $this->reader = new DBFReader($ruta);
    }

    public function getAllArticulos($offset = 0, $limit = 10) {
        return $this->reader->getRecords($offset, $limit);
    }

    public function buscarPorCodigoONombre($busqueda) {
        $busqueda = mb_strtolower($busqueda);
        $todos = $this->reader->getRecords();

        $coincidentes = array_filter($todos, function ($art) use ($busqueda) {
            return (
                isset($art['CODIGO']) && stripos($art['CODIGO'], $busqueda) !== false
            ) || (
                isset($art['NOMBRE']) && stripos($art['NOMBRE'], $busqueda) !== false
            );
        });

        return array_values($coincidentes);
    }

    public function getArticulosPorCodigos(array $codigos) {
        $todos = $this->reader->getRecords();
        $filtrados = array_filter($todos, function($art) use ($codigos) {
            return in_array($art['CODIGO'], $codigos);
        });
        return array_values($filtrados);
    }


    public function getTotal() {
        return $this->reader->getRecordCount();
    }

    public function insertArticulo($data) {
        // Generar nuevo CLAART
        $ultimo = $this->obtenerUltimoCodigoCLAART();
        $nuevoCLAART = $ultimo + 1;
        $data['CLAART'] = $nuevoCLAART;

        $this->completarCamposPorDefecto($data);
        return $this->reader->insertRecord($data);
    }

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

        foreach ($porDefecto as $campo => $valor) {
            if (!isset($datos[$campo]) || $datos[$campo] === '') {
                $datos[$campo] = $valor;
            }
        }

        // Si no hay padre, igualamos al código del propio artículo
        if (empty($datos['CODPADRE']) && isset($datos['CODIGO'])) {
            $datos['CODPADRE'] = $datos['CODIGO'];
        }

        // Si no hay descripción corta, usa el nombre
        if (empty($datos['SHORTDESC']) && isset($datos['NOMBRE'])) {
            $datos['SHORTDESC'] = substr($datos['NOMBRE'], 0, 50);
        }

        // Longdesc también opcionalmente
        if (empty($datos['LONGDESC']) && isset($datos['NOMBRE'])) {
            $datos['LONGDESC'] = substr($datos['NOMBRE'], 0, 254);
        }
    }

}
