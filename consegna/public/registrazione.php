<?php
require dirname(__DIR__) . '/src/bootstrap.php';

if ($user) {
    redirect('area-personale.php');
}
$errors = [];
$username = '';
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_post();
    $username = mb_strtolower(input('username'));
    $email = mb_strtolower(input('email'));
    $password = password_input('password');
    $confirm = password_input('confirm');

    if (!preg_match('/^[a-z0-9_]{3,30}$/D', $username)) {
        $errors['username'] = 'Nome utente: usa da 3 a 30 lettere, numeri o trattini bassi.';
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > 254) {
        $errors['email'] = 'Inserisci un indirizzo email valido.';
    }
    if (mb_strlen($password) < 8 || strlen($password) > 72) {
        $errors['password'] = 'La password deve contenere almeno 8 caratteri e non superare 72 byte.';
    }
    if ($password !== $confirm) {
        $errors['confirm'] = 'Le due password non coincidono.';
    }

    if (!$errors) {
        if ($database->utentePerNome($username)) {
            $errors['username'] = 'Questo nome utente è già in uso.';
        }
        if ($database->utentePerEmail($email)) {
            $errors['email'] = 'Questa email è già registrata.';
        }
    }

    if (!$errors) {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        try {
            $nuovoUtente = $database->aggiungiUtente($username, $email, $hash);
        } catch (PDOException $error) {
            // Gestisce anche due registrazioni contemporanee con gli stessi dati.
            if (substr((string)$error->getCode(), 0, 2) === '23') {
                $errors['username'] = 'Nome utente o email già registrati.';
            } else {
                throw $error;
            }
        }
        if (!$errors) {
            session_regenerate_id(true);
            $_SESSION['user_id'] = $nuovoUtente;
            $_SESSION['csrf'] = bin2hex(random_bytes(32));
            flash('Account creato.');
            redirect('area-personale.php');
        }
    }
    http_response_code(422);
}

$title = 'Registrazione';
require dirname(__DIR__) . '/src/templates/header.php';
require dirname(__DIR__) . '/src/pages/register.php';
require dirname(__DIR__) . '/src/templates/footer.php';
