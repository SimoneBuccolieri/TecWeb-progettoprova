<?php
require dirname(__DIR__) . '/src/bootstrap.php';
$user = require_user();

$hikes = $database->escursioniPreferite($user['id']);
$reviews = $database->recensioniUtente($user['id']);

$title = 'I miei sentieri';
require dirname(__DIR__) . '/src/templates/header.php';
require dirname(__DIR__) . '/src/pages/account.php';
require dirname(__DIR__) . '/src/templates/footer.php';
