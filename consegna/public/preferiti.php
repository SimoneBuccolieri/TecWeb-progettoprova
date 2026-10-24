<?php
require dirname(__DIR__) . '/src/bootstrap.php';
require_post();
$user = require_user();
$id = positive_id(input('hike_id'));
hike($id);
$operation = input('operation');

if ($operation === 'remove') {
    $database->rimuoviPreferito($user['id'], $id);
    flash('Escursione rimossa dai preferiti.');
} elseif ($operation === 'add') {
    if (!$database->preferitoPresente($user['id'], $id)) {
        try {
            $database->aggiungiPreferito($user['id'], $id);
        } catch (PDOException $error) {
            // Un secondo invio contemporaneo non deve creare un duplicato.
            if (substr((string)$error->getCode(), 0, 2) !== '23') {
                throw $error;
            }
        }
    }
    flash('Escursione salvata tra i preferiti.');
} else {
    fail(400, 'Operazione non valida', 'Scegli una delle azioni disponibili nella scheda dell’escursione.');
}
redirect('escursione.php?id=' . $id);
