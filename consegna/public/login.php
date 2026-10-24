<?php
require dirname(__DIR__) . '/src/bootstrap.php';

if ($user) {
    redirect('area-personale.php');
}
$errors = [];
$username = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_post();
    $username = mb_strtolower(input('username'));
    $password = password_input('password');

    $key = hash('sha256', $_SERVER['REMOTE_ADDR'] ?? 'local');
    $attempt = $database->tentativiAccesso($key);
    if ($attempt && time() - (int)$attempt['last_attempt'] > 900) {
        $database->eliminaTentativi($key);
        $attempt = false;
    }

    if ($attempt && (int)$attempt['attempts'] >= 10) {
        http_response_code(429);
        $errors['username'] = 'Troppi tentativi. Attendi 15 minuti prima di riprovare.';
    } else {
        $account = $database->utentePerNome($username);

        // Verifica comunque un hash se l'account non esiste.
        $hash = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2uheWG/igi.';
        if ($account) {
            $hash = $account['password_hash'];
        }
        $valid = password_verify($password, $hash);

        if (!$account || !$valid) {
            if ($attempt) {
                $database->incrementaTentativi($key);
            } else {
                $database->primoTentativo($key);
            }
            $errors['username'] = 'Nome utente o password non corretti.';
            http_response_code(422);
        } else {
            $database->eliminaTentativi($key);
            session_regenerate_id(true);
            $_SESSION['user_id'] = (int)$account['id'];
            $_SESSION['csrf'] = bin2hex(random_bytes(32));
            flash('Bentornato, ' . $account['username'] . '!');
            redirect('area-personale.php');
        }
    }
}

$title = 'Accedi';
require dirname(__DIR__) . '/src/templates/header.php';
require dirname(__DIR__) . '/src/pages/login.php';
require dirname(__DIR__) . '/src/templates/footer.php';
