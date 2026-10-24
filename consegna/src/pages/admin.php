<header class="page-intro">
    <p class="eyebrow">AREA AMMINISTRATORE</p>
    <h1>Gestisci Passo Verde</h1>
    <p>Aggiungi e aggiorna i percorsi, oppure modera le recensioni della comunità.</p>
</header>
<section class="section">
    <div class="section-title">
        <h2>Escursioni pubblicate (<?= count($hikes) ?>)</h2>
        <a class="button" href="gestisci-escursione.php">Aggiungi escursione +</a>
    </div>
    <?php if (!$hikes): ?>
    <p>Nessuna escursione pubblicata.</p>
    <?php else: ?>
    <div class="table-scroll" role="region" aria-label="Elenco escursioni amministratore" tabindex="0">
        <table>
            <caption>Catalogo delle escursioni</caption>
            <thead>
                <tr>
                    <th scope="col">
                        Escursione
                    </th>
                    <th scope="col">
                        Difficoltà
                    </th>
                    <th scope="col">
                        Località
                    </th>
                    <th scope="col">
                        Azioni
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($hikes as $item): ?>
                <tr>
                    <th scope="row">
                        <a href="escursione.php?id=<?= (int)$item['id'] ?>"><?= e($item['title']) ?></a>
                    </th>
                    <td>
                        <?= e(ucfirst($item['difficulty'])) ?>
                    </td>
                    <td>
                        <?= e($item['location']) ?>
                    </td>
                    <td>
                        <div class="actions">
                            <a href="gestisci-escursione.php?id=<?= (int)$item['id'] ?>">Modifica<span class="sr-only"> <?= e($item['title']) ?></span></a><a href="elimina-escursione.php?id=<?= (int)$item['id'] ?>">Elimina<span class="sr-only"> <?= e($item['title']) ?></span></a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>
</section>
<section class="section">
    <h2>Moderazione recensioni (<?= count($reviews) ?>)</h2>
    <?php if (!$reviews): ?>
    <p>Nessuna recensione da moderare.</p>
    <?php endif; ?><?php foreach ($reviews as $review): ?>
    <article class="review">
        <h3><?= e($review['username']) ?> su <a href="escursione.php?id=<?= (int)$review['hike_id'] ?>#recensioni"><?= e($review['title']) ?></a></h3>
        <p><?= (int)$review['rating'] ?> su 5</p>
        <p class="review-body"><?= nl2br(e($review['body'])) ?></p>
        <a href="elimina-recensione.php?id=<?= (int)$review['id'] ?>">Elimina recensione<span class="sr-only"> di <?= e($review['username']) ?> su <?= e($review['title']) ?></span></a>
    </article>
    <?php endforeach; ?>
</section>
