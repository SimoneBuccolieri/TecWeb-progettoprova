<header class="detail-heading">
    <p class="eyebrow"><?= e($hike['location']) ?></p>
    <h1><?= e($hike['title']) ?></h1>
    <p><span class="badge <?= e($hike['difficulty']) ?>"><?= e(ucfirst($hike['difficulty'])) ?></span> <a href="#recensioni"><?= $average ? e($average).' su 5 · '.count($reviews).' recensioni' : 'Nessuna recensione: lascia la prima' ?></a></p>
</header>
<div class="detail-grid">
    <img class="detail-photo" src="images/<?= e($hike['image']) ?>" alt="Paesaggio montano illustrativo; non rappresenta un itinerario reale" width="1600" height="1067" />
    <aside class="trail-summary" aria-labelledby="in-breve">
        <h2 id="in-breve">Il percorso in breve</h2>
        <dl>
            <div>
                <dt>Lunghezza</dt>
                <dd><?= decimal($hike['distance']) ?> km</dd>
            </div>
            <div>
                <dt>Durata</dt>
                <dd><?= e(duration((int)$hike['duration'])) ?></dd>
            </div>
            <div>
                <dt>Dislivello positivo</dt>
                <dd><?= (int)$hike['elevation'] ?> m</dd>
            </div>
            <div>
                <dt>Difficoltà</dt>
                <dd><?= e(ucfirst($hike['difficulty'])) ?></dd>
            </div>
        </dl>
        <?php if ($user): ?>
        <form method="post" action="preferiti.php">
            <?= csrf() ?>
            <input type="hidden" name="hike_id" value="<?= (int)$hike['id'] ?>" />
            <input type="hidden" name="operation" value="<?= $saved ? 'remove' : 'add' ?>" />
            <button type="submit"><?= $saved ? 'Rimuovi dai preferiti' : 'Salva tra i preferiti' ?> <span aria-hidden="true"><?= $saved ? '♥' : '♡' ?></span></button>
        </form>
        <?php else: ?><a class="button" href="login.php">Accedi per salvare ♡</a><?php endif; ?>
        <p class="hint">Dati dimostrativi, non indicazioni per un’uscita reale.</p>
        <a href="chi-siamo.php#difficolta">Informazioni sulle difficoltà</a>
    </aside>
</div>
<section class="reading section">
    <h2>Descrizione del percorso</h2>
    <?php foreach (preg_split('/\R\s*\R/', $hike['description']) as $paragraph): ?>
    <p><?= nl2br(e($paragraph)) ?></p>
    <?php endforeach; ?>
</section>
<section class="section reviews" id="recensioni">
    <div class="section-title">
        <div>
            <h2>Le recensioni degli escursionisti</h2>
        </div>
        <p><?= $average ? e($average).' / 5 · '.count($reviews).' recensioni' : 'Ancora nessuna recensione' ?></p>
    </div>
    <div class="review-layout">
        <div>
            <?php if (!$reviews): ?>
            <div class="empty">
                <h3>Nessuna recensione</h3>
                <p>Gli utenti registrati possono scrivere una recensione.</p>
            </div>
            <?php endif; ?><?php foreach ($reviews as $review): ?>
            <article class="review">
                <header>
                    <h3><?= e($review['username']) ?></h3>
                    <p><?= (int)$review['rating'] ?> su 5 <span aria-hidden="true">★</span></p>
                </header>
                <p class="review-body"><?= nl2br(e($review['body'])) ?></p>
                <p class="hint">Aggiornata il <time datetime="<?= e(substr($review['updated_at'],0,10)) ?>"><?= e(date('d/m/Y',strtotime($review['updated_at']))) ?></time></p>
                <?php if ($user && ((int)$review['user_id']===(int)$user['id'] || $user['role']==='admin')): ?>
                <div class="actions">
                    <?php if ((int)$review['user_id']===(int)$user['id']): ?><a href="#recensione-form">Modifica la tua recensione</a><?php endif; ?><a href="elimina-recensione.php?id=<?= (int)$review['id'] ?>">Elimina recensione<span class="sr-only"> di <?= e($review['username']) ?></span></a>
                </div>
                <?php endif; ?>
            </article>
            <?php endforeach; ?>
        </div>
        <div class="form-panel" id="recensione-form">
            <?php if ($user): ?>
            <h3><?= $mine ? 'Modifica la tua recensione' : 'Scrivi una recensione' ?></h3>
            <p>Una recensione per escursione, modificabile in qualsiasi momento. Tutti i campi sono obbligatori.</p>
            <?php error_summary($errors); ?>
            <form method="post" action="escursione.php?id=<?= (int)$hike['id'] ?>#recensione-form">
                <?= csrf() ?>
                <label for="rating">Valutazione</label>
                <select id="rating" name="rating" required="required"<?= field_error($errors,'rating') ?>>
                    <option value="">Scegli una valutazione</option>
                    <?php foreach ([5,4,3,2,1] as $score): ?>
                    <option value="<?= $score ?>"<?= (int)$rating===$score ? ' selected="selected"' : '' ?>><?= $score ?> su 5</option>
                    <?php endforeach; ?>
                </select>
                <?php error_text($errors,'rating'); ?>
                <label for="body">La tua recensione</label>
                <p id="review-hint" class="hint">Da 10 a 2000 caratteri.</p>
                <textarea id="body" name="body" rows="6" required="required" minlength="10" maxlength="2000"<?= field_error($errors,'body','review-hint') ?>><?= e($body) ?></textarea>
                <?php error_text($errors,'body'); ?>
                <button type="submit"><?= $mine ? 'Salva le modifiche' : 'Pubblica recensione' ?></button>
            </form>
            <?php else: ?>
            <h3>Scrivi una recensione</h3>
            <p>Accedi per scrivere una recensione.</p>
            <a class="button" href="login.php">Accedi</a>
            <p>Oppure <a href="registrazione.php">crea un account</a>.</p>
            <?php endif; ?>
        </div>
    </div>
</section>
