<?php
// Clase para leer archivos DBF (formato dBASE)
class DBFReader {
    private $file;               // Manejador del archivo DBF abierto
    private $filepath;
    private $fields = [];        // Arreglo de campos definidos en el archivo
    private $recordCount = 0;    // Número total de registros
    private $recordSize = 0;     // Tamaño de cada registro en bytes
    private $headerSize = 0;     // Tamaño del encabezado en bytes

    // Constructor: abre el archivo y analiza el encabezado
    public function __construct($filepath) {
        if (!file_exists($filepath)) {
            throw new Exception("Archivo no encontrado: $filepath"); // Verifica que el archivo exista
        }
        $this->filepath = $filepath;
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

    // Mas rapido
    
    public function getRecords($offset = 0, $limit = 100) {
        $records = [];
        $start = $this->headerSize + ($offset * $this->recordSize);

        fseek($this->file, $start);

        $max = min($limit, $this->recordCount - $offset);

        for ($i = 0; $i < $max; $i++) {
            $deleted = fread($this->file, 1);
            if ($deleted === '*') {
                fseek($this->file, $this->recordSize - 1, SEEK_CUR);
                continue;
            }

            $rawRecord = fread($this->file, $this->recordSize - 1);
            $record = [];
            $pos = 0;

            foreach ($this->fields as $field) {
                $raw = substr($rawRecord, $pos, $field['length']);
                $record[$field['name']] = trim(mb_convert_encoding($raw, 'UTF-8', 'CP1252'));
                $pos += $field['length'];
            }

            $records[] = $record;
        }

        return $records;
    }


/*
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
*/
    public function getRecordCount() {
        return $this->recordCount;
    }

public function getFilteredRecordsPaginado($campo, $valor, $offset = 0, $limit = 10) {
    $results = [];

    // Validación rápida
    $campoEncontrado = false;
    foreach ($this->fields as $field) {
        if ($field['name'] === $campo) {
            $campoEncontrado = true;
            break;
        }
    }
    if (!$campoEncontrado) return [];

    // Reinicia puntero
    fseek($this->file, $this->headerSize);

    $matchCount = 0; // Cuántos registros coinciden
    $added = 0;      // Cuántos se han agregado al array

    for ($i = 0; $i < $this->recordCount; $i++) {
        $deleted = fread($this->file, 1);
        if ($deleted === '*') {
            fseek($this->file, $this->recordSize - 1, SEEK_CUR);
            continue;
        }

        $rawRecord = fread($this->file, $this->recordSize - 1);
        $record = [];
        $pos = 0;

        foreach ($this->fields as $field) {
            $raw = substr($rawRecord, $pos, $field['length']);
            $record[$field['name']] = trim(mb_convert_encoding($raw, 'UTF-8', 'CP1252'));
            $pos += $field['length'];
        }

        if ($record[$campo] == $valor) {
            if ($matchCount >= $offset && $added < $limit) {
                $results[] = $record;
                $added++;
            }
            $matchCount++;
        }

        if ($added >= $limit) break;
    }

    return $results;
}

    private $currentRecordIndex = 0; // Índice para llevar la posición actual del registro leído

    public function nextRecord() {
        // Si ya leímos todos los registros, devolvemos false para indicar fin
        if ($this->currentRecordIndex >= $this->recordCount) {
            return false; // No quedan registros
        }

        // Calculamos la posición en el archivo donde comienza el registro actual
        $pos = $this->headerSize + ($this->currentRecordIndex * $this->recordSize);
        fseek($this->file, $pos); // Posicionamos el puntero del archivo en esa posición

        // Leemos el primer byte del registro que indica si está eliminado o no
        $deleted = fread($this->file, 1);
        if ($deleted === '*') {
            // Si el registro está marcado como eliminado ('*')
            $this->currentRecordIndex++; // Avanzamos al siguiente índice
            return $this->nextRecord();  // Llamamos recursivamente para saltar el eliminado
        }

        $record = []; // Array para guardar los datos del registro

        // Leemos cada campo del registro según la definición en $this->fields
        foreach ($this->fields as $field) {
            $raw = fread($this->file, $field['length']); // Leemos la cantidad de bytes que ocupa el campo
            // Convertimos la codificación a UTF-8 y limpiamos espacios en blanco
            $record[$field['name']] = trim(mb_convert_encoding($raw, 'UTF-8', 'CP1252'));
        }

        $this->currentRecordIndex++; // Avanzamos al siguiente registro para la próxima llamada

        return $record; // Devolvemos el registro leído como un arreglo asociativo
    }


    private function normalizarDato($valor, $tipo, $longitud, $decimales = 0) {
        switch ($tipo) {
            case 'C': // Character
                $valor = (string)$valor;
                $valor = mb_convert_encoding($valor, 'CP1252', 'UTF-8');
                return str_pad(substr($valor, 0, $longitud), $longitud, ' ', STR_PAD_RIGHT);

            case 'N': // Numeric
            case 'F': // Float
                $valor = is_numeric($valor) ? $valor : 0;
                $formato = number_format($valor, $decimales, '.', '');
                return str_pad(substr($formato, 0, $longitud), $longitud, ' ', STR_PAD_LEFT);

            case 'D': // Date (YYYYMMDD)
                if ($valor instanceof DateTime) {
                    $fecha = $valor->format('Ymd');
                } elseif (preg_match('/^\d{4}-\d{2}-\d{2}$/', $valor)) {
                    $fecha = str_replace('-', '', $valor);
                } elseif (preg_match('/^\d{8}$/', $valor)) {
                    $fecha = $valor;
                } else {
                    $fecha = str_repeat(' ', 8); // Campo vacío
                }
                return $fecha;

            case 'L': // Logical (T/F o espacio)
                $valor = strtolower(trim($valor));
                if (in_array($valor, ['1', 'y', 'yes', 't', 'true'])) {
                    return 'T';
                } elseif (in_array($valor, ['0', 'n', 'no', 'f', 'false'])) {
                    return 'F';
                } else {
                    return ' ';
                }

            case 'M': // Memo (puntero a memo.dbt, si no se usa: 0)
                return str_pad('0', $longitud, ' ', STR_PAD_LEFT);

            case 'I': // Integer (4 bytes binarios)
                $valor = intval($valor);
                return pack("V", $valor); // Little endian 4 bytes

            case 'B': // Double (8 bytes binarios)
                $valor = floatval($valor);
                return pack("d", $valor); // 8 bytes, double, little endian

            case 'T': // DateTime (8 bytes binarios)
                try {
                    $dt = new DateTime(is_string($valor) ? $valor : 'now');
                } catch (Exception $e) {
                    $dt = new DateTime(); // fallback
                }
                $julian = gregoriantojd($dt->format('m'), $dt->format('d'), $dt->format('Y'));
                $msec = ($dt->format('H') * 3600 + $dt->format('i') * 60 + $dt->format('s')) * 1000;
                return pack("V", $julian) . pack("V", $msec);

            default:
                return str_repeat(' ', $longitud);
        }
    }



    public function insertRecord($data) {
        $record = ' '; // Primer byte: registro activo

        foreach ($this->fields as $field) {
            $name = $field['name'];
            $type = $field['type'];
            $length = $field['length'];

            $value = isset($data[$name]) ? $data[$name] : '';

            // Normaliza el valor
            $normalized = $this->normalizarDato($value, $type, $length, $field['decimals'] ?? 0);

            // Solo convierte a CP1252 si es texto (C)
            if ($type === 'C') {
                $normalized = mb_convert_encoding($normalized, 'CP1252', 'UTF-8');
            }

            $record .= $normalized;
        }

        // Verifica el byte EOF (0x1A) al final
        $fileSize = filesize($this->filepath);
        $lastByte = '';
        if ($fileSize > 0) {
            fseek($this->file, -1, SEEK_END);
            $lastByte = fread($this->file, 1);
        }

        // Sitúa el puntero correctamente para escribir
        if ($lastByte === chr(0x1A)) {
            fseek($this->file, -1, SEEK_END); // Reescribe el EOF
        } else {
            fseek($this->file, 0, SEEK_END); // Añade al final
        }

        // Escribe el registro
        fwrite($this->file, $record);

        // Añade EOF
        fwrite($this->file, chr(0x1A));

        // Actualiza contador de registros en el encabezado (byte 4-7)
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