<?php
require dirname(__DIR__) . '/src/bootstrap.php';
$hikes = $database->ultimeEscursioni();

$title = 'Escursioni e trekking';
require dirname(__DIR__) . '/src/templates/header.php';
require dirname(__DIR__) . '/src/pages/home.php';
require dirname(__DIR__) . '/src/templates/footer.php';
