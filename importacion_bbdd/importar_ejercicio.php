<?php
require_once __DIR__ . '/../models/DatabaseLOCAL.php';
require_once __DIR__ . '/../models/Database.php';

set_time_limit(0); // eliminar límite de tiempo

$pdo = DatabaseLOCAL::connect();
$reader = new DBFReader('C:\\SAMO\\ClasGes6SP26\\DATOS\\EJERCIC.dbf');

// Obtener campos del DBF
$fields = $reader->getFields();
$fieldDefs = [];
$columns = [];

foreach ($fields as $f) {
    $name = $f['name'];
    $type = match($f['type']) {
        'C' => "VARCHAR({$f['length']})",
        'I' => "INT",
        'B' => "DOUBLE",
        'L' => "BOOLEAN",
        'D' => "DATE",
        'T' => "DATETIME",
        'M' => "TEXT",
        default => "TEXT"
    };
    $fieldDefs[] = "`$name` $type";
    $columns[] = $name;
}

// Añadir clave primaria si existe
if (in_array('CLAEJE', $columns)) {
    $fieldDefs[] = "PRIMARY KEY (`CLAEJE`)";
}

// Crear tabla si no existe
$tableSQL = "CREATE TABLE IF NOT EXISTS cg_ejercicios (
" . implode(",\n", $fieldDefs) . "
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
$pdo->exec($tableSQL);

// Obtener columnas válidas existentes
$existingCols = $pdo->query("DESCRIBE cg_ejercicios")->fetchAll(PDO::FETCH_COLUMN);
$fieldNames = array_filter($columns, fn($name) => in_array($name, $existingCols));
$insertCols = array_map(fn($f) => "`$f`", $fieldNames);

$totalRecords = $reader->getRecordCount();
$batchSize = 500;

$success = 0;
$skipped = 0;

for ($i = 0; $i < $totalRecords; $i += $batchSize) {
    $records = $reader->getRecords($i, $batchSize);
    $insertValues = [];
    $bindValues = [];

    foreach ($records as $record) {
        if ($record['_deleted']) {
            $skipped++;
            continue;
        }

        $insertValues[] = '(' . implode(',', array_fill(0, count($fieldNames), '?')) . ')';

        foreach ($fieldNames as $name) {
            $bindValues[] = $record[$name];
        }

        $success++;
    }

    if (!empty($insertValues)) {
        $updates = array_map(fn($f) => "`$f` = VALUES(`$f`)", $fieldNames);

        $sql = "INSERT INTO cg_ejercicios (" . implode(',', $insertCols) . ")
                VALUES " . implode(',', $insertValues) . "
                ON DUPLICATE KEY UPDATE " . implode(', ', $updates);

        $stmt = $pdo->prepare($sql);
        $stmt->execute($bindValues);
    }

    if ($i % (10 * $batchSize) === 0) {
        echo "Procesados: $i / $totalRecords registros...\n";
        flush();
    }
}

$ids_dbf = array_column($reader->getRecords(0, $reader->getRecordCount()), 'CLAEJE');
$stmt = $pdo->query("SELECT CLAEJE FROM cg_ejercicios");
$ids_mysql = $stmt->fetchAll(PDO::FETCH_COLUMN);

$ids_a_eliminar = array_diff($ids_mysql, $ids_dbf);

if (!empty($ids_a_eliminar)) {
    $in = implode(',', array_fill(0, count($ids_a_eliminar), '?'));
    $del = $pdo->prepare("DELETE FROM cg_ejercicios WHERE CLAEJE IN ($in)");
    $del->execute(array_values($ids_a_eliminar));
    echo "🗑 Eliminados de MySQL: " . count($ids_a_eliminar) . "\n";
}

echo "Importación completada. Total insertados: $success | Omitidos (eliminados): $skipped\n";
?>
