<?php
require dirname(__DIR__) . '/src/bootstrap.php';
$user = require_user();
$id = positive_id($_GET['id'] ?? null);
$review = $database->trovaRecensione($id);

if (!$review) {
    fail(404, 'Recensione non trovata', 'Questa recensione è già stata eliminata oppure non esiste.');
}
if ((int)$review['user_id'] !== (int)$user['id'] && $user['role'] !== 'admin') {
    fail(403, 'Operazione non consentita', 'Puoi eliminare soltanto le tue recensioni.');
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_post();
    $database->eliminaRecensione($id);
    flash('Recensione eliminata.');
    redirect('escursione.php?id=' . $review['hike_id'] . '#recensioni');
}

$title = 'Elimina recensione';
$message = 'Vuoi eliminare questa recensione di “' . $review['title'] . '”? Non sarà possibile recuperarla.';
$preview = $review['body'];
$action = 'elimina-recensione.php?id=' . $id;
$back = 'escursione.php?id=' . $review['hike_id'] . '#recensioni';
require dirname(__DIR__) . '/src/templates/header.php';
require dirname(__DIR__) . '/src/pages/confirm.php';
require dirname(__DIR__) . '/src/templates/footer.php';
