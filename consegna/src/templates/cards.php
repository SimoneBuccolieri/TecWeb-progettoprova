<?php
if (!$hikes) {
    echo file_get_contents(__DIR__ . '/../html/nessun-risultato.html');
} else {
    $modello = file_get_contents(__DIR__ . '/../html/card.html');
    $elenco = '';
    foreach ($hikes as $item) {
        $scheda = strtr($modello, [
            '{{id}}' => (string)(int)$item['id'],
            '{{titolo}}' => e($item['title']),
            '{{localita}}' => e($item['location']),
            '{{immagine}}' => e($item['image']),
            '{{difficolta}}' => e($item['difficulty']),
            '{{nome_difficolta}}' => e(ucfirst($item['difficulty'])),
            '{{lunghezza}}' => decimal($item['distance']),
            '{{durata}}' => e(duration((int)$item['duration'])),
            '{{dislivello}}' => (string)(int)$item['elevation']
        ]);
        $elenco .= $scheda;
    }
    echo '<ul class="cards">' . $elenco . '</ul>';
}
