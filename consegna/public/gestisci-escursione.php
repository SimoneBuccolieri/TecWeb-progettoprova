<?php
require dirname(__DIR__) . '/src/bootstrap.php';
$user = require_user(true);

$id = null;
if (isset($_GET['id'])) {
    $id = positive_id($_GET['id']);
}
if ($id) {
    $data = hike($id);
} else {
    $data = [
        'title' => '',
        'location' => '',
        'difficulty' => 'facile',
        'distance' => '',
        'duration' => '',
        'elevation' => '',
        'description' => '',
        'image' => 'montagne.jpg'
    ];
}

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_post();
    foreach (['title', 'location', 'difficulty', 'distance', 'duration', 'elevation', 'description', 'image'] as $key) {
        $data[$key] = input($key);
    }

    if (mb_strlen($data['title']) < 3 || mb_strlen($data['title']) > 100) {
        $errors['title'] = 'Nome dell’escursione: inserisci da 3 a 100 caratteri.';
    }
    if (mb_strlen($data['location']) < 2 || mb_strlen($data['location']) > 100) {
        $errors['location'] = 'Località: inserisci da 2 a 100 caratteri.';
    }
    if (mb_strlen($data['description']) < 30 || mb_strlen($data['description']) > 5000) {
        $errors['description'] = 'Descrizione: inserisci da 30 a 5000 caratteri.';
    }
    if (!in_array($data['difficulty'], ['facile', 'media', 'difficile'], true)) {
        $errors['difficulty'] = 'Seleziona una difficoltà valida.';
    }
    if (!is_numeric($data['distance']) || (float)$data['distance'] < 0.1 || (float)$data['distance'] > 100 || round((float)$data['distance'], 1) !== (float)$data['distance']) {
        $errors['distance'] = 'La lunghezza deve essere tra 0,1 e 100 km, con al massimo un decimale.';
    }
    if (filter_var($data['duration'], FILTER_VALIDATE_INT, ['options' => ['min_range' => 1, 'max_range' => 1440]]) === false) {
        $errors['duration'] = 'La durata deve essere un numero intero tra 1 e 1440.';
    }
    if (filter_var($data['elevation'], FILTER_VALIDATE_INT, ['options' => ['min_range' => 0, 'max_range' => 5000]]) === false) {
        $errors['elevation'] = 'Il dislivello deve essere un numero intero tra 0 e 5000.';
    }
    if (!in_array($data['image'], ['montagne.jpg', 'cime.jpg', 'prati.jpg'], true)) {
        $errors['image'] = 'Scegli una delle immagini disponibili.';
    }

    if (!$errors) {
        if ($id) {
            $database->modificaEscursione($id, $data);
        } else {
            $id = $database->aggiungiEscursione($data);
        }
        flash('Escursione salvata.');
        redirect('escursione.php?id=' . $id);
    }
    http_response_code(422);
}

if ($id) {
    $title = 'Modifica escursione';
} else {
    $title = 'Aggiungi escursione';
}
$active = 'admin.php';
$parent = ['admin.php', 'Amministrazione'];
require dirname(__DIR__) . '/src/templates/header.php';
require dirname(__DIR__) . '/src/pages/hike-form.php';
require dirname(__DIR__) . '/src/templates/footer.php';
