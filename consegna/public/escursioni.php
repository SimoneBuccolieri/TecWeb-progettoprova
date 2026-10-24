<?php
require dirname(__DIR__) . '/src/bootstrap.php';

$q = mb_substr(input('q', $_GET), 0, 100);
$difficulty = input('difficulty', $_GET);
if (!in_array($difficulty, ['', 'facile', 'media', 'difficile'], true)) {
    fail(400, 'Filtro non valido', 'Scegli una delle difficoltà disponibili dalla pagina delle escursioni.');
}

$hikes = $database->cercaEscursioni($q, $difficulty);

$title = 'Escursioni';
require dirname(__DIR__) . '/src/templates/header.php';
require dirname(__DIR__) . '/src/pages/catalog.php';
require dirname(__DIR__) . '/src/templates/footer.php';
