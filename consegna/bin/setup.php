<?php
if (PHP_SAPI !== 'cli') {
    http_response_code(404);
    exit;
}
require dirname(__DIR__) . '/src/database.php';

$database = new GestioneDati();
$driver = $database->preparaDatabase();

// Non cancella dati né reimposta password di utenti già esistenti.
$demo = in_array('--demo', $argv, true);
if ($demo) {
    foreach (['admin', 'user'] as $name) {
        if (!$database->utentePerNome($name)) {
            $hash = password_hash($name, PASSWORD_DEFAULT);
            $database->aggiungiUtente($name, $name . '@example.test', $hash, $name);
        }
    }
    if ($database->contaEscursioni() === 0) {
        $hikes = require dirname(__DIR__) . '/database/seed.php';
        foreach ($hikes as $row) {
            $dati = [
                'title' => $row[0],
                'location' => $row[1],
                'difficulty' => $row[2],
                'distance' => $row[3],
                'duration' => $row[4],
                'elevation' => $row[5],
                'description' => str_replace('\n', "\n", $row[6]),
                'image' => $row[7]
            ];
            $database->aggiungiEscursione($dati);
        }
    }
}
echo "Database pronto ($driver).";
if ($demo) {
    echo ' Account dimostrativi: admin/admin e user/user.';
}
echo "\n";
