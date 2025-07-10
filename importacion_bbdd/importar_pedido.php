<?php
require_once __DIR__ . '/../models/DatabaseLOCAL.php';
require_once __DIR__ . '/../models/Database.php';

set_time_limit(0); // sin límite de ejecución

$pdo = DatabaseLOCAL::connect();
$reader = new DBFReader('C:\\SAMO\\ClasGes6SP26\\DATOS\\pedido.dbf');

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
if (in_array('CLAPED', $columns)) {
    $fieldDefs[] = "PRIMARY KEY (`CLAPED`)";
}

// Crear tabla si no existe
$tableSQL = "CREATE TABLE IF NOT EXISTS cg_pedidos (
" . implode(",\n", $fieldDefs) . "
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
$pdo->exec($tableSQL);

// Obtener columnas reales existentes
$existingCols = $pdo->query("DESCRIBE cg_pedidos")->fetchAll(PDO::FETCH_COLUMN);
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

        $sql = "INSERT INTO cg_pedidos (" . implode(',', $insertCols) . ")
                VALUES " . implode(',', $insertValues) . "
                ON DUPLICATE KEY UPDATE " . implode(', ', $updates);

        $stmt = $pdo->prepare($sql);
        $stmt->execute($bindValues);
    }

    // Mostrar progreso cada 1000 registros
    if ($i % (10 * $batchSize) === 0) {
        echo "Procesados: $i / $totalRecords\n";
        flush();
    }
}

$ids_dbf = array_column($reader->getRecords(0, $reader->getRecordCount()), 'CLAPED');
$stmt = $pdo->query("SELECT CLAPED FROM cg_pedidos");
$ids_mysql = $stmt->fetchAll(PDO::FETCH_COLUMN);

$ids_a_eliminar = array_diff($ids_mysql, $ids_dbf);

if (!empty($ids_a_eliminar)) {
    $in = implode(',', array_fill(0, count($ids_a_eliminar), '?'));
    $del = $pdo->prepare("DELETE FROM cg_pedidos WHERE CLAPED IN ($in)");
    $del->execute(array_values($ids_a_eliminar));
    echo "🗑 Eliminados de MySQL: " . count($ids_a_eliminar) . "\n";
}

echo "Importación completada. Total insertados: $success | Omitidos (eliminados): $skipped\n";
?>
