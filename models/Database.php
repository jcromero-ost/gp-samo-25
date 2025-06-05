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

        $this->file = fopen($filepath, "rb"); // Abre el archivo en modo binario solo lectura

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
                $record[$field['name']] = trim($raw);        // Elimina espacios en blanco y asigna el valor al campo
            }

            $records[] = $record; // Agrega el registro al arreglo de resultados
        }

        return $records; // Devuelve todos los registros leídos
    }

    // Destructor: cierra el archivo cuando se destruye el objeto
    public function __destruct() {
        fclose($this->file);
    }
}
