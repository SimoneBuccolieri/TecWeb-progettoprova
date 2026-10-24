<?php
if (!isset($title)) {
    $title = 'Passo Verde';
}
if (!isset($description)) {
    $description = 'Catalogo di escursioni con ricerca per nome, località e difficoltà. Schede dei percorsi, preferiti e recensioni.';
}
if (!isset($active)) {
    $active = basename($_SERVER['SCRIPT_NAME']);
}

$menu = '';
$voci = [
    'index.php' => 'Home',
    'escursioni.php' => 'Escursioni',
    'chi-siamo.php' => 'Il progetto',
    'area-personale.php' => 'I miei sentieri'
];
if ($user && $user['role'] === 'admin') {
    $voci['admin.php'] = 'Amministrazione';
}
foreach ($voci as $url => $etichetta) {
    $corrente = '';
    if ($active === $url) {
        $corrente = ' aria-current="page"';
    }
    $menu .= '<li><a href="' . e($url) . '"' . $corrente . '>' . e($etichetta) . '</a></li>';
}
if ($user) {
    $menu .= '<li><form action="logout.php" method="post" class="logout">' . csrf();
    $menu .= '<button type="submit">Esci<span class="sr-only"> dall’account ' . e($user['username']) . '</span></button>';
    $menu .= '</form></li>';
} else {
    $corrente = '';
    if ($active === 'login.php') {
        $corrente = ' aria-current="page"';
    }
    $menu .= '<li><a class="nav-login" href="login.php"' . $corrente . '>Accedi</a></li>';
}

$percorso = '';
$nomePagina = $title;
if ($active !== 'index.php') {
    $percorso .= '<li><a href="index.php">Home</a></li>';
} else {
    $nomePagina = 'Home';
}
if (isset($parent)) {
    $percorso .= '<li><a href="' . e($parent[0]) . '">' . e($parent[1]) . '</a></li>';
}
$percorso .= '<li aria-current="page">' . e($nomePagina) . '</li>';

$avviso = '';
if (!empty($_SESSION['flash'])) {
    $avviso = '<p class="notice" role="status">' . e($_SESSION['flash']) . '</p>';
}
unset($_SESSION['flash']);

$intestazione = file_get_contents(__DIR__ . '/../html/header.html');
// Una sola sostituzione: i segnaposto contenuti nei dati non vengono rielaborati.
echo strtr($intestazione, [
    '{{titolo}}' => e($title),
    '{{descrizione}}' => e($description),
    '{{menu}}' => $menu,
    '{{percorso}}' => $percorso,
    '{{avviso}}' => $avviso
]);
