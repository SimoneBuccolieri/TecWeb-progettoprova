<?php
require_once __DIR__ . '/database.php';

date_default_timezone_set('Europe/Rome');
ini_set('session.use_strict_mode', '1');
session_name('passoverde_session');
session_set_cookie_params([
    'httponly' => true,
    'samesite' => 'Lax',
    'secure' => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
    'path' => rtrim(dirname($_SERVER['SCRIPT_NAME']), '/') . '/'
]);
session_start();

header('Content-Type: text/html; charset=UTF-8');
header('X-Content-Type-Options: nosniff');
header('Referrer-Policy: strict-origin-when-cross-origin');
header("Content-Security-Policy: default-src 'self'; img-src 'self'; style-src 'self'; script-src 'self'; base-uri 'none'; form-action 'self'; frame-ancestors 'none'");
header('Cache-Control: no-store');

if (isset($_SESSION['last_seen']) && time() - $_SESSION['last_seen'] > 3600) {
    $_SESSION = [];
    session_regenerate_id(true);
}
$_SESSION['last_seen'] = time();

if (!isset($_SESSION['csrf'])) {
    $_SESSION['csrf'] = bin2hex(random_bytes(32));
}

set_exception_handler(function ($error) {
    error_log($error->getMessage());
    http_response_code(500);
    $title = 'Servizio temporaneamente non disponibile';
    $message = 'Non è stato possibile completare la richiesta. Riprova tra poco.';
    $user = null;
    require __DIR__ . '/templates/header.php';
    require __DIR__ . '/pages/error.php';
    require __DIR__ . '/templates/footer.php';
});

// Protegge il testo inserito nelle pagine e negli attributi HTML.
function e($value)
{
    return htmlspecialchars((string)$value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function input($key, $source = null)
{
    if ($source === null) {
        $source = $_POST;
    }
    if (!isset($source[$key]) || !is_string($source[$key])) {
        return '';
    }
    return trim($source[$key]);
}

// Gli spazi nelle password devono essere conservati.
function password_input($key)
{
    if (!isset($_POST[$key]) || !is_string($_POST[$key])) {
        return '';
    }
    return $_POST[$key];
}

function csrf()
{
    return '<input type="hidden" name="csrf" value="' . e($_SESSION['csrf']) . '" />';
}

function require_post()
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('Allow: POST');
        fail(405, 'Operazione non disponibile', 'Usa il pulsante del modulo per questa operazione.');
    }
    if (!hash_equals($_SESSION['csrf'], input('csrf'))) {
        fail(403, 'Modulo scaduto', 'Ricarica la pagina e riprova: i dati non sono stati modificati.');
    }
}

function current_user()
{
    global $database;
    if (!isset($_SESSION['user_id'])) {
        return null;
    }
    $account = $database->trovaUtente($_SESSION['user_id']);
    if (!$account) {
        return null;
    }
    return $account;
}

function redirect($path)
{
    header('Location: ' . $path, true, 303);
    exit;
}

function flash($message)
{
    $_SESSION['flash'] = $message;
}

function require_user($admin = false)
{
    $user = current_user();
    if (!$user) {
        flash('Accedi per continuare.');
        redirect('login.php');
    }
    if ($admin && $user['role'] !== 'admin') {
        fail(403, 'Accesso riservato', 'Questa pagina è riservata agli amministratori.');
    }
    return $user;
}

function fail($status, $title, $message)
{
    http_response_code($status);
    $user = current_user();
    require __DIR__ . '/templates/header.php';
    require __DIR__ . '/pages/error.php';
    require __DIR__ . '/templates/footer.php';
    exit;
}

function positive_id($raw)
{
    $id = filter_var($raw, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
    if ($id === false) {
        fail(404, 'Pagina non trovata', 'Il contenuto richiesto non è disponibile.');
    }
    return $id;
}

function hike($id)
{
    global $database;
    $hike = $database->trovaEscursione($id);
    if (!$hike) {
        fail(404, 'Escursione non trovata', 'Questa escursione non esiste oppure è stata rimossa.');
    }
    return $hike;
}

function duration($minutes)
{
    $text = intdiv($minutes, 60) . ' h';
    $remaining = $minutes % 60;
    if ($remaining > 0) {
        $text .= ' ' . $remaining . ' min';
    }
    return $text;
}

function decimal($number)
{
    return number_format((float)$number, 1, ',', '.');
}

function difficulty_options($selected, $all = true)
{
    if ($all) {
        $html = '<option value="">Tutte le difficoltà</option>';
    } else {
        $html = '<option value="">Scegli la difficoltà</option>';
    }
    $difficulties = ['facile' => 'Facile', 'media' => 'Media', 'difficile' => 'Difficile'];
    foreach ($difficulties as $value => $label) {
        $attribute = '';
        if ($value === $selected) {
            $attribute = ' selected="selected"';
        }
        $html .= '<option value="' . $value . '"' . $attribute . '>' . $label . '</option>';
    }
    return $html;
}

// Collega il campo alle istruzioni e, se presente, al suo messaggio di errore.
function field_error($errors, $name, $hint = '')
{
    $attributes = '';
    $description = $hint;
    if (isset($errors[$name])) {
        $attributes = ' aria-invalid="true"';
        $description .= ' error-' . $name;
    }
    $description = trim($description);
    if ($description !== '') {
        $attributes .= ' aria-describedby="' . e($description) . '"';
    }
    return $attributes;
}

function error_text($errors, $name)
{
    if (isset($errors[$name])) {
        echo '<p class="field-error" id="error-' . e($name) . '">' . e($errors[$name]) . '</p>';
    }
}

function error_summary($errors)
{
    if (!$errors) {
        return;
    }
    echo '<div class="errors" role="alert" tabindex="-1" id="errors"><h2>Controlla i dati inseriti</h2><ul>';
    foreach ($errors as $key => $error) {
        echo '<li><a href="#' . e($key) . '">' . e($error) . '</a></li>';
    }
    echo '</ul></div>';
}

$database = new GestioneDati();
$user = current_user();
