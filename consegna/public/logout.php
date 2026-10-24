<?php
require dirname(__DIR__) . '/src/bootstrap.php';
require_post();

$_SESSION = [];
session_regenerate_id(true);
$_SESSION['csrf'] = bin2hex(random_bytes(32));
flash('Hai effettuato la disconnessione.');
redirect('index.php');
