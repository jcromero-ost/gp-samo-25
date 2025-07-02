<?php
class DBFReader {
    private $file;
    private $filepath;
    private $fields = [];
    private $recordCount = 0;
    private $recordSize = 0;
    private $headerSize = 0;
    private $encoding = 'CP850'; // Puedes cambiar a CP1252 o ISO-8859-1 si lo necesitas
    private $currentRecordIndex = 0;

    public function __construct($filepath, $encoding = 'CP850') {
        if (!file_exists($filepath)) {
            throw new Exception("Archivo no encontrado: $filepath");
        }
        $this->filepath = $filepath;
        $this->file = fopen($filepath, "r+b");
        $this->encoding = $encoding;
        $this->parseHeader();
    }

    private function parseHeader() {
        fseek($this->file, 4);
        $this->recordCount = unpack("V", fread($this->file, 4))[1];
        $this->headerSize = unpack("v", fread($this->file, 2))[1];
        $this->recordSize = unpack("v", fread($this->file, 2))[1];

        fseek($this->file, 32);
        while (true) {
            $field = fread($this->file, 32);
            if (ord($field[0]) == 0x0D) break;

            $name = rtrim(substr($field, 0, 11));
            $type = $field[11];
            $length = ord($field[16]);

            $this->fields[] = ['name' => $name, 'type' => $type, 'length' => $length];
        }

        fseek($this->file, $this->headerSize);
    }

    private function sanitizeString($value) {
        // 1. Convierte desde la codepage original a UTF-8
        $converted = mb_convert_encoding($value, 'UTF-8', $this->encoding);

        // 2. Tabla de reemplazos para caracteres erróneos comunes
        $correcciones = [
            '║' => 'º',
            '¥' => 'ñ',
            'Ð' => 'Ñ',
            'Æ' => 'Ç',
            'þ' => 'á',
            'ý' => 'é',
            'û' => 'í',
            'ü' => 'ó',
            'ù' => 'ú',
            '╔' => 'É',
            'æ' => 'ç',
            'Ö' => 'Ö',
            'Ë' => 'Ó',
            // etc.
        ];

        return strtr($converted, $correcciones);
    }

    public function getRecords($offset = 0, $limit = 100, $incluirEliminados = false) {
        $records = [];
        $start = $this->headerSize + ($offset * $this->recordSize);
        fseek($this->file, $start);
        $max = min($limit, $this->recordCount - $offset);

        for ($i = 0; $i < $max; $i++) {
            $deleted = fread($this->file, 1);
            if ($deleted === '*') {
                if (!$incluirEliminados) {
                    fseek($this->file, $this->recordSize - 1, SEEK_CUR);
                    continue;
                }
            }

            $rawRecord = fread($this->file, $this->recordSize - 1);
            $record = [];
            $pos = 0;

            foreach ($this->fields as $field) {
                $raw = substr($rawRecord, $pos, $field['length']);
                $value = $this->parseFieldValue($raw, $field['type']);

                if (in_array($field['type'], ['C', 'M']) && is_string($value)) {
                    $value = $this->sanitizeString($value);
                }

                $record[$field['name']] = $value;
                $pos += $field['length'];
            }

            $record['_deleted'] = ($deleted === '*');
            $records[] = $record;
        }

        return $records;
    }

    public function getFilteredRecordsPaginado($campo, $valor, $offset = 0, $limit = 10, $incluirEliminados = false) {
        $results = [];

        if (!in_array($campo, array_column($this->fields, 'name'))) {
            return [];
        }

        fseek($this->file, $this->headerSize);
        $matchCount = 0;
        $added = 0;

        for ($i = 0; $i < $this->recordCount; $i++) {
            $deleted = fread($this->file, 1);
            if ($deleted === '*') {
                if (!$incluirEliminados) {
                    fseek($this->file, $this->recordSize - 1, SEEK_CUR);
                    continue;
                }
            }

            $rawRecord = fread($this->file, $this->recordSize - 1);
            $record = [];
            $pos = 0;

            foreach ($this->fields as $field) {
                $raw = substr($rawRecord, $pos, $field['length']);
                $value = $this->parseFieldValue($raw, $field['type']);
                $record[$field['name']] = $value;
                $pos += $field['length'];
            }

            $record['_deleted'] = ($deleted === '*');

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
    
public function getFilteredTotal($campo, $valor, $incluirEliminados = false) {
    $total = 0;

    if (!in_array($campo, array_column($this->fields, 'name'))) {
        return 0;
    }

    fseek($this->file, $this->headerSize);

    for ($i = 0; $i < $this->recordCount; $i++) {
        $deleted = fread($this->file, 1);
        if ($deleted === '*' && !$incluirEliminados) {
            fseek($this->file, $this->recordSize - 1, SEEK_CUR);
            continue;
        }

        $rawRecord = fread($this->file, $this->recordSize - 1);
        $record = [];
        $pos = 0;

        foreach ($this->fields as $field) {
            $raw = substr($rawRecord, $pos, $field['length']);
            $record[$field['name']] = $this->parseFieldValue($raw, $field['type']);
            $pos += $field['length'];
        }

        if ($record[$campo] == $valor) {
            $total++;
        }
    }

    return $total;
}


    public function nextRecord() {
        if ($this->currentRecordIndex >= $this->recordCount) return false;

        $pos = $this->headerSize + ($this->currentRecordIndex * $this->recordSize);
        fseek($this->file, $pos);

        $deleted = fread($this->file, 1);
        if ($deleted === '*') {
            $this->currentRecordIndex++;
            return $this->nextRecord();
        }

        $record = [];
        foreach ($this->fields as $field) {
            $raw = fread($this->file, $field['length']);
            $record[$field['name']] = $this->parseFieldValue($raw, $field['type']);
        }

        $this->currentRecordIndex++;
        return $record;
    }

    private function parseFieldValue(string $raw, string $type) {
        switch ($type) {
            case 'C':
                return trim($raw);
            case 'I':
                return unpack('V', $raw)[1];
            case 'B':
                return unpack('d', $raw)[1];
            case 'L':
                $val = strtoupper(trim($raw));
                return in_array($val, ['Y', 'T']) ? true : false;
            case 'D':
                $val = trim($raw);
                return preg_match('/^\d{8}$/', $val) ? substr($val, 0, 4) . '-' . substr($val, 4, 2) . '-' . substr($val, 6, 2) : null;
            case 'T':
                $julian = unpack("V", substr($raw, 0, 4))[1];
                $msec = unpack("V", substr($raw, 4, 4))[1];
                $timestamp = ($julian - 2440588) * 86400 + ($msec / 1000);
                return date("Y-m-d H:i:s", $timestamp);
            case 'M':
                return '(memo)'; // Placeholder, real value en archivo .fpt
            default:
                return trim($raw);
        }
    }

    public function getFields() {
        return $this->fields;
    }

    public function getRecordCount() {
        return $this->recordCount;
    }

    public function __destruct() {
        fclose($this->file);
    }
}
