<header class="page-intro">
    <h1>Ciao, <?= e($user['username']) ?>.</h1>
    <p>Qui trovi le escursioni salvate e le tue recensioni.</p>
    <?php if ($user['role']==='admin'): ?><a class="button" href="admin.php">Vai all’amministrazione</a><?php endif; ?>
</header>
<section class="section">
    <div class="section-title">
        <h2>Le tue escursioni preferite</h2>
        <p><?= count($hikes) ?> salvate</p>
    </div>
    <?php if ($hikes): ?><?php require __DIR__ . '/../templates/cards.php'; ?><?php else: ?>
    <div class="empty">
        <h3>Non hai ancora salvato escursioni</h3>
        <p>Apri un’escursione e scegli “Salva tra i preferiti” per ritrovarla qui.</p>
        <a class="button" href="escursioni.php">Consulta le escursioni</a>
    </div>
    <?php endif; ?>
</section>
<section class="section">
    <h2>Le tue recensioni</h2>
    <?php if (!$reviews): ?>
    <p>Non hai ancora scritto recensioni.</p>
    <?php endif; ?><?php foreach ($reviews as $review): ?>
    <article class="review">
        <h3><a href="escursione.php?id=<?= (int)$review['hike_id'] ?>#recensioni"><?= e($review['title']) ?></a></h3>
        <p><?= (int)$review['rating'] ?> su 5</p>
        <p class="review-body"><?= nl2br(e($review['body'])) ?></p>
        <div class="actions">
            <a href="escursione.php?id=<?= (int)$review['hike_id'] ?>#recensione-form">Modifica<span class="sr-only"> la recensione di <?= e($review['title']) ?></span></a><a href="elimina-recensione.php?id=<?= (int)$review['id'] ?>">Elimina<span class="sr-only"> la recensione di <?= e($review['title']) ?></span></a>
        </div>
    </article>
    <?php endforeach; ?>
</section>
