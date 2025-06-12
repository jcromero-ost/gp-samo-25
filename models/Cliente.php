<?php
// Incluye el archivo de conexión a la base de datos
require_once __DIR__ . '/Database.php';

// Definición de la clase Cliente, que se encarga de manejar operaciones sobre la tabla 'clientes'
class Cliente {
    private $reader;
    private $ruta;

    public function __construct() {
        $this->ruta = "C:\\SAMO\\ClasGes6SP26\\DATOS\\clientes.dbf";
        $this->reader = new DBFReader($this->ruta);
    }

    public function getAllClientes() {
        $clientes = $this->reader->getRecords();

        // Ordenar alfabéticamente por el campo 'nombre' (ajusta el nombre del campo si es diferente)
        usort($clientes, function ($a, $b) {
            return strcmp($b['FALTA'], $a['FALTA']);
        });
        return $clientes;
    }
/*
    public function create($data){
        return $this->reader->insertRecord($data);
    }

public function getClienteVacio() {
    $campos = $this->reader->getFields();
    $cliente = [];

    foreach ($campos as $campo) {
        $nombre = $campo['name'];
        $tipo = $campo['type'];
        $longitud = $campo['length'];

        switch ($tipo) {
            case 'C': // Character
                $valor = str_repeat(' ', $longitud);
                // Convertimos a Windows-1252
                $valor = mb_convert_encoding($valor, 'Windows-1252', 'UTF-8');
                break;
            case 'N': // Numeric
            case 'F': // Float
                $valor = str_repeat('0', $longitud);
                break;
            case 'D': // Date
                $valor = str_repeat(' ', 8); // YYYYMMDD
                break;
            case 'L': // Logical
                $valor = ' ';
                break;
            default:
                $valor = str_repeat(' ', $longitud);
                break;
        }

        $cliente[$nombre] = $valor;
    }

    return $cliente;
}
*/

    // Método para crear un nuevo usuario
    public function create($data) {
        $clacli = strtoupper(substr($data['nombre'] ?? 'CLI', 0, 3)) . rand(1000, 9999); // ej: CLI4892
        $ultimoCodigo = $this->getLastCodigo(); // Debes implementar este método
        $codigo = strval($ultimoCodigo + 1);

        $record = [
            'CLACLI' => (int)rand(1, 9999),
            'CODIGO' => $data['CODIGO'] ?? '',
            'NOMBRE' => $data['NOMBRE'] ?? '',
            'DIRECCION' => $data['DIRECCION'] ?? '',
            'LOCALIDAD' => $data['LOCALIDAD'] ?? '',
            'PROVINCIA' => $data['PROVINCIA'] ?? '',
            'POSTAL' => $data['POSTAL'] ?? '',
            'PAIS' => $data['PAIS'] ?? '',
            'TELEFONO' => $data['TELEFONO'] ?? '',
            'FAX' => '',
            'EMAIL' => '',
            'CONTACTO' => '',
            'FALTA' => date('Ymd'), // campo tipo fecha
            'CIF' => '24456567L',
            'NOMBAN' => '',
            'DIRBAN' => '',
            'LOCBAN' => '',
            'PROVBAN' => '',
            'OFIBAN' => '',
            'ENTI' => '',
            'OFIC' => '',
            'NCTA' => '',
            'DC' => '',
            'PAGO1' => 0,
            'PAGO2' => 0,
            'PAGO3' => 0,
            'PAGO4' => 0,
            'DTOPP' => 0,
            'DTOCIAL' => 0,
            'NCTACON' => 0,
            'RIESGO' => 900000,
            'REGIMENIVA' => 1,
            'DPAGO1' => 0,
            'DPAGO2' => 0,
            'CONTACT1' => '',
            'CARGO1' => '',
            'TELCONT1' => '',
            'CONTACT2' => '',
            'CARGO2' => '',
            'TELCONT2' => '',
            'CONTACT3' => '',
            'CARGO3' => '',
            'TELCONT3' => '',
            'CONTACT4' => '',
            'CARGO4' => '',
            'TELCONT4' => '',
            'NOTAS' => '',
            'PORTES' => 0,
            'PORTESDESD' => 0,
            'AGRUPALB' => 'VERDADERO',
            'NOALB' => 'VERDADERO',
            'CLAAGE' => 0,
            'CLAFPA' => 0,
            'CLATIP' => 0,
            'CLATRA' => 0,
            'CLAZON' => 0,
            'CLATAR' => 0,
            'NOMBRECOM' => '',
            'NCODPROV' => '',
            'IBAN' => '',
            'GPSLAT' => 0,
            'GPSLON' => 0,
            'EMAILFE' => '',
            'CONSUMIDOR' => 'FALSO',
            'NOMUSER' => '',
            'PASSWORD' => '',
            'BIC' => '',
            'REFUNICTO' => '',
            'FECHACTO' => '',
            'FIRSTRCBO' => 'VERDADERO',
            'ADMPUBLI' => 'FALSO',
            'CLAADMON' => 0,
            'NOVALESTPV' => 'FALSO',
            'ALTAONLINE' => 'FALSO',
            'BAJAONLINE' => 'FALSO',
            'MSG_ONLINE' => '',
            'EMAILCONF' => 'FALSO',
            'NOMCNT' => '',
            'APELLCNT' => '',
            'RESETCOUNT' => '',
            'RESETDATE' => '',
            'CLAPAIS' => 72,
            'CLAUN' => 2988,
            'NOPUBLI' => 'FALSO',
            'CODREGIVA' => '',
            'FTIPFACVEN' => 'FALSO',
            'TIPOCIF' => 1,
            'CODPAIS' => 'ES',
            'TIPOCOBRO' => 1,
            'APLITARTPV' => '',
            'BAJA' => 'FALSO',
            'WEB' => '',
            'OBSERVA' => '',
            'REGIMENIVB' => 0,
            'PARTIDA' => 1334,
            'CLAFPAB' => 0,
            'PAGO1B' => 0,
            'PAGO2B' => 0,
            'PAGO3B' => 0,
            'PAGO4B' => 0,
            'DPAGO1B' => 0,
            'DPAGO2B' => 0,
            'CPOSTALEX' => '',
            'ENMAIL' => 'FALSO'
        ];
            return $this->reader->insertRecord($record);
    }

    public function getLastCodigo()
    {
        $records = $this->reader->getRecords(); // o una consulta ordenada por CODIGO
        if (empty($records)) return 100000; // Código base si no hay registros

        $codigos = array_column($records, 'CODIGO');
        $codigosNumericos = array_map('intval', $codigos);
        return max($codigosNumericos);
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

    // Método para borrar un cliente por su CODIGO, usando deleteRecord()
    public function delete($codigo) {
        try {
            $fields = $this->reader->getFields();
            $codigoField = 'CODIGO';
            $codigoFieldIndex = null;

            foreach ($fields as $i => $field) {
                if ($field['name'] === $codigoField) {
                    $codigoFieldIndex = $i;
                    break;
                }
            }

            if ($codigoFieldIndex === null) {
                throw new Exception("Campo $codigoField no encontrado en el archivo DBF.");
            }

            $records = $this->reader->getRecords();
            $recordPos = 0;
            foreach ($records as $record) {
                if (trim((string)$record[$codigoField]) == (string)$codigo) {
                    error_log("Registro encontrado en posición $recordPos. Marcando borrado.");

                    // Aquí se llama al método deleteRecord para marcar el registro como eliminado
                    if ($this->reader->deleteRecord($recordPos)) {
                        return true;
                    } else {
                        error_log("No se pudo marcar el registro como eliminado.");
                        return false;
                    }
                }
                $recordPos++;
            }

            error_log("No se encontró el registro con codigo $codigo.");
            return false;

        } catch (Exception $e) {
            error_log("Error al eliminar cliente: " . $e->getMessage());
            return false;
        }
    }


    // Método opcional para obtener un usuario por su ID
    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM usuarios WHERE id = :id"); // Prepara la consulta
        $stmt->bindParam(':id', $id, PDO::PARAM_INT); // Asocia el parámetro :id con el valor $id
        $stmt->execute(); // Ejecuta la consulta
        return $stmt->fetch(PDO::FETCH_ASSOC); // Devuelve un único resultado como array asociativo
    }    
}
