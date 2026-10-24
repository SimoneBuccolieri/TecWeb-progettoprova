<?php
// Router opzionale per il server PHP locale; Apache usa .htaccess.
$path = rawurldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?: '/');
$root = realpath(dirname(__DIR__,2).'/consegna/public');
$file = realpath($root.$path);
if ($path==='/' || ($file && str_starts_with($file, $root.DIRECTORY_SEPARATOR) && is_file($file))) { return false; }
require $root.'/404.php';
