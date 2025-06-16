<?php
try {
    // OJO: Asegúrate que el nombre coincide con el DSN exacto: "dbf_samo"
    $pdo = new PDO("odbc:dbf_samo");
    echo "✅ Conexión ODBC exitosa.";

    // Opcional: mostrar una tabla como prueba
    $stmt = $pdo->query("SELECT * FROM clientes");
    $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo "<pre>";
    print_r($resultados);
    echo "</pre>";

} catch (PDOException $e) {
    echo "❌ Error de conexión ODBC: " . $e->getMessage();
}