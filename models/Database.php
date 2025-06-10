<?php
// Clase para leer archivos DBF (formato dBASE)
class DBFReader {
    private $file;               // Manejador del archivo DBF abierto
    private $fields = [];        // Arreglo de campos definidos en el archivo
    private $recordCount = 0;    // Número total de registros
    private $recordSize = 0;     // Tamaño de cada registro en bytes
    private $headerSize = 0;     // Tamaño del encabezado en bytes

    // Constructor: abre el archivo y analiza el encabezado
    public function __construct($filepath) {
        if (!file_exists($filepath)) {
            throw new Exception("Archivo no encontrado: $filepath"); // Verifica que el archivo exista
        }

        $this->file = fopen($filepath, "r+b"); // permite lectura y escritura

        $this->parseHeader(); // Llama a método para leer el encabezado
    }

    // Método privado para analizar el encabezado del archivo DBF
    private function parseHeader() {
        fseek($this->file, 4); // Salta los primeros 4 bytes (fecha de actualización y tipo de archivo)
        $this->recordCount = unpack("V", fread($this->file, 4))[1]; // Lee cantidad de registros (4 bytes, little endian)
        $this->headerSize = unpack("v", fread($this->file, 2))[1];  // Lee tamaño del encabezado (2 bytes, little endian)
        $this->recordSize = unpack("v", fread($this->file, 2))[1];  // Lee tamaño de cada registro (2 bytes, little endian)

        fseek($this->file, 32); // Salta a la posición donde empiezan las descripciones de los campos (después de los primeros 32 bytes)
        while (true) {
            $field = fread($this->file, 32); // Lee 32 bytes que definen un campo
            if (ord($field[0]) == 0x0D) break; // Si el primer byte es 0x0D, termina la lista de campos

            $name = rtrim(substr($field, 0, 11)); // Obtiene el nombre del campo (hasta 11 bytes, sin espacios a la derecha)
            $type = $field[11];                  // Tipo de campo (C, N, D, L, etc.)
            $length = ord($field[16]);           // Longitud del campo en bytes

            // Agrega el campo al arreglo de campos
            $this->fields[] = ['name' => $name, 'type' => $type, 'length' => $length];
        }

        fseek($this->file, $this->headerSize); // Posiciona el puntero en el inicio de los registros de datos
    }

    // Método público para obtener todos los registros del archivo DBF
    public function getRecords() {
        $records = []; // Arreglo que contendrá todos los registros

        for ($i = 0; $i < $this->recordCount; $i++) {
            $deleted = fread($this->file, 1); // Lee el primer byte para saber si el registro está marcado como eliminado
            if ($deleted === '*') {
                fseek($this->file, $this->recordSize - 1, SEEK_CUR); // Si está eliminado, salta el resto del registro
                continue;
            }

            $record = []; // Arreglo para almacenar un registro
            foreach ($this->fields as $field) {
                $raw = fread($this->file, $field['length']); // Lee los bytes correspondientes al campo
                $record[$field['name']] = trim(mb_convert_encoding($raw, 'UTF-8', 'CP1252')); // Elimina espacios en blanco y asigna el valor al campo
       // Elimina espacios en blanco y asigna el valor al campo
            }

            $records[] = $record; // Agrega el registro al arreglo de resultados
        }

        return $records; // Devuelve todos los registros leídos
    }

    private function normalizarDato($valor, $tipo, $longitud) {
        $valor = trim($valor);

        switch ($tipo) {
            case 'C': // Texto
                $valor = substr($valor, 0, $longitud);
                return str_pad($valor, $longitud, ' ', STR_PAD_RIGHT);

            case 'N': // Numérico
            case 'F': // Float
                $valor = preg_replace('/[^0-9.\-]/', '', $valor);
                $valor = substr($valor, 0, $longitud);
                return str_pad($valor, $longitud, ' ', STR_PAD_LEFT);

            case 'D': // Fecha en formato YYYYMMDD
                if (preg_match('/^\d{8}$/', $valor)) {
                    return $valor;
                }
                return str_repeat('0', 8);

            case 'L': // Lógico (Y/N/T/F)
                $v = strtoupper(substr($valor, 0, 1));
                return in_array($v, ['Y','N','T','F']) ? $v : ' ';

            default:
                return str_pad('', $longitud, ' ');
        }
    }


    public function insertRecord($data) {
        $record = ' '; // Primer byte: espacio indica que el registro está activo (no eliminado)

        foreach ($this->fields as $field) {
            $name = $field['name'];
            $type = $field['type'];
            $length = $field['length'];

            $value = isset($data[$name]) ? $data[$name] : '';

            // Usar la función de normalización
            $normalized = $this->normalizarDato($value, $type, $length);

            // Convertir a CP1252 solo si es texto (C)
            if ($type === 'C') {
                $normalized = mb_convert_encoding($normalized, 'CP1252', 'UTF-8');
            }

            $record .= $normalized;
        }

        // Escribir el registro
        fseek($this->file, 0, SEEK_END);
        fwrite($this->file, $record);

        // Actualizar el número de registros
        $this->recordCount++;
        fseek($this->file, 4);
        fwrite($this->file, pack("V", $this->recordCount));

        fflush($this->file);
        return true;
    }



    public function deleteRecord(int $recordNumber): bool {
        if ($recordNumber < 0 || $recordNumber >= $this->recordCount) {
            echo "<script>console.error('Número de registro inválido para eliminar.');</script>";
            return false;
        }

        // Posicionar puntero al inicio del registro:
        // El primer registro comienza justo después del header, en $this->headerSize
        $pos = $this->headerSize + ($recordNumber * $this->recordSize);
        fseek($this->file, $pos);

        // Escribir '*' para marcar eliminado
        $written = fwrite($this->file, '*');

        fflush($this->file);

        return ($written === 1);
    }

    public function getFields() {
        return $this->fields;
    }


    // Destructor: cierra el archivo cuando se destruye el objeto
    public function __destruct() {
        fclose($this->file);
    }
}