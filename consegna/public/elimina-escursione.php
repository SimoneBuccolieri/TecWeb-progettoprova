<?php
require dirname(__DIR__) . '/src/bootstrap.php';
$user = require_user(true);
$id = positive_id($_GET['id'] ?? null);
$hike = hike($id);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_post();
    $database->eliminaEscursione($id);
    flash('Escursione eliminata insieme ai preferiti e alle recensioni associate.');
    redirect('admin.php');
}

$title = 'Elimina escursione';
$active = 'admin.php';
$message = 'Vuoi eliminare “' . $hike['title'] . '”? Saranno eliminati anche tutti i preferiti e le recensioni associati. L’operazione non può essere annullata.';
$action = 'elimina-escursione.php?id=' . $id;
$back = 'admin.php';
require dirname(__DIR__) . '/src/templates/header.php';
require dirname(__DIR__) . '/src/pages/confirm.php';
require dirname(__DIR__) . '/src/templates/footer.php';
