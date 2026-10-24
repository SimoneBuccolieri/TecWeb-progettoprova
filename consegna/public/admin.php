<?php
require dirname(__DIR__) . '/src/bootstrap.php';
$user = require_user(true);

$hikes = $database->tutteEscursioni();
$reviews = $database->recensioniDaModerare();

$title = 'Amministrazione';
require dirname(__DIR__) . '/src/templates/header.php';
require dirname(__DIR__) . '/src/pages/admin.php';
require dirname(__DIR__) . '/src/templates/footer.php';
