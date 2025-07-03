<?php
require_once './models/DatabaseLOCAL.php';
require_once './models/Database.php';

$pdo = DatabaseLOCAL::connect();
$reader = new DBFReader('C:\\SAMO\\ClasGes6SP26\\DATOS\\articulo.dbf');

// Obtener columnas reales en la tabla MySQL
$existingCols = $pdo->query("DESCRIBE articulo")->fetchAll(PDO::FETCH_COLUMN);

// Filtrar solo campos existentes
$fields = $reader->getFields();
$fieldNames = array_filter(
    array_map(fn($f) => $f['name'], $fields),
    fn($name) => in_array($name, $existingCols)
);

// Generar placeholders y columnas
$columns = array_map(fn($f) => "`{$f}`", $fieldNames);
$placeholders = array_fill(0, count($columns), '?');
$updates = array_map(fn($f) => "`{$f}` = VALUES(`{$f}`)", $fieldNames);

$sql = "INSERT INTO articulo (" . implode(',', $columns) . ")
        VALUES (" . implode(',', $placeholders) . ")
        ON DUPLICATE KEY UPDATE " . implode(',', $updates);

$stmt = $pdo->prepare($sql);

$records = $reader->getRecords(0, $reader->getRecordCount());

foreach ($records as $record) {
    if ($record['_deleted']) continue;

    $values = [];
    foreach ($fieldNames as $name) {
        $values[] = $record[$name];
    }

    $stmt->execute($values);
}

echo "Importación completada con DBFReader y campos filtrados.";
