<section class="form-panel confirm">
    <h1><?= e($title) ?></h1>
    <p><?= e($message) ?></p>
    <?php if (!empty($preview)): ?><blockquote><?= e($preview) ?></blockquote><?php endif; ?>
    <form method="post" action="<?= e($action) ?>">
        <?= csrf() ?>
        <div class="actions">
            <button class="danger" type="submit">Conferma eliminazione</button>
            <a href="<?= e($back) ?>">Annulla</a>
        </div>
    </form>
</section>
