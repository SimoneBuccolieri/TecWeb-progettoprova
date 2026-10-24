<?php
require dirname(__DIR__) . '/src/bootstrap.php';

$id = positive_id($_GET['id'] ?? null);
$hike = hike($id);
$reviews = $database->recensioniEscursione($id);

$mine = null;
foreach ($reviews as $review) {
    if ($user && (int)$review['user_id'] === (int)$user['id']) {
        $mine = $review;
    }
}
$saved = false;
if ($user) {
    $saved = $database->preferitoPresente($user['id'], $id);
}

$rating = '5';
$body = '';
if ($mine) {
    $rating = $mine['rating'];
    $body = $mine['body'];
}
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_post();
    $user = require_user();
    $rating = input('rating');
    $body = input('body');
    if (!in_array($rating, ['1', '2', '3', '4', '5'], true)) {
        $errors['rating'] = 'Scegli una valutazione da 1 a 5.';
    }
    if (mb_strlen($body) < 10 || mb_strlen($body) > 2000) {
        $errors['body'] = 'La recensione deve contenere da 10 a 2000 caratteri.';
    }
    if (!$errors) {
        if ($mine) {
            $database->modificaRecensione($mine['id'], $user['id'], $rating, $body);
            flash('Recensione aggiornata.');
        } else {
            $database->aggiungiRecensione($user['id'], $id, $rating, $body);
            flash('Recensione pubblicata.');
        }
        redirect('escursione.php?id=' . $id . '#recensioni');
    }
    http_response_code(422);
}

$average = null;
if ($reviews) {
    $total = 0;
    foreach ($reviews as $review) {
        $total += $review['rating'];
    }
    $average = number_format($total / count($reviews), 1, ',', '.');
}

$title = $hike['title'];
$active = 'escursioni.php';
$parent = ['escursioni.php', 'Escursioni'];
$description = mb_substr($hike['description'], 0, 160);
require dirname(__DIR__) . '/src/templates/header.php';
require dirname(__DIR__) . '/src/pages/detail.php';
require dirname(__DIR__) . '/src/templates/footer.php';
