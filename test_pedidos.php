<?php
require_once 'models/Database.php';

try {
    $ruta = "C:\\SAMO\\ClasGes6SP26\\DATOS\\pedido.dbf";
    $reader = new DBFReader($ruta);

    echo "<h3>Filtrando registros con CLAEJE = 38</h3>";

    $registros = $reader->getFilteredRecordsPaginado('CLAEJE', 38, 0, 100, true);

    $contador = 0;
    echo "<table border='1' cellpadding='4'>";
    echo "<tr>
            <th>#</th>
            <th>CLAEJE</th>
            <th>NUMERO</th>
            <th>FECHA</th>
            <th>CLACLI</th>
            <th>CODCLI</th>
            <th>BIMPO</th>
            <th>IMPORTE</th>
        </tr>";

    foreach ($registros as $i => $row) {
        if (isset($row['CLAEJE']) && (int)$row['CLAEJE'] === 38) {
            $contador++;
            echo "<tr>
                <td>{$contador}</td>
                <td>{$row['CLAEJE']}</td>
                <td>{$row['NUMERO']}</td>
                <td>{$row['FECHA']}</td>
                <td>{$row['CLACLI']}</td>
                <td>{$row['CODCLI']}</td>
                <td>{$row['BIMPO']}</td>
                <td>{$row['IMPORTE']}</td>
            </tr>";
        }
    }

    echo "</table>";

    if ($contador === 0) {
        echo "<p><strong>No se encontraron registros con CLAEJE = 38</strong></p>";
    } else {
        echo "<p><strong>Total encontrados: {$contador}</strong></p>";
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
