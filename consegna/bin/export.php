<?php
// Esporta i dati del database corrente in SQL per MariaDB, senza credenziali DB.
if (PHP_SAPI !== 'cli') {
    http_response_code(404);
    exit;
}
require dirname(__DIR__) . '/src/database.php';
$database = new GestioneDati();

echo "-- Passo Verde: dati per uno schema MariaDB vuoto.\nSET NAMES utf8mb4;\n";
foreach (['users', 'hikes', 'favorites', 'reviews'] as $table) {
    $rows = $database->datiTabella($table);
    foreach ($rows as $row) {
        $values = [];
        foreach ($row as $value) {
            if ($value === null) {
                $values[] = 'NULL';
            } else {
                // I valori testuali non dipendono dalle regole di escape del server.
                $values[] = 'CONVERT(0x' . bin2hex((string)$value) . ' USING utf8mb4)';
            }
        }
        $columns = implode('`,`', array_keys($row));
        echo 'INSERT INTO `' . $table . '` (`' . $columns . '`) VALUES (' . implode(',', $values) . ");\n";
    }
}
