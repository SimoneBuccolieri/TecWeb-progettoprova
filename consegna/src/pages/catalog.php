<header class="page-intro">
    <h1>Le escursioni</h1>
    <p>Consulta il catalogo oppure cerca un percorso usando i filtri.</p>
</header>
<form class="filters" action="escursioni.php" method="get">
    <div>
        <label for="q">Cerca per nome o località</label>
        <input type="search" name="q" id="q" value="<?= e($q) ?>" maxlength="100" placeholder="Es. valle, prati, rifugio" />
    </div>
    <div>
        <label for="difficulty">Difficoltà</label>
        <select id="difficulty" name="difficulty">
            <?= difficulty_options($difficulty) ?>
        </select>
    </div>
    <button type="submit">Cerca escursioni</button>
    <a href="escursioni.php">Azzera filtri</a>
</form>
<section class="section results" aria-labelledby="risultati">
    <div class="section-title">
        <h2 id="risultati"><?= count($hikes) ?> <?= count($hikes)===1 ? 'percorso trovato' : 'percorsi trovati' ?></h2>
        <a href="chi-siamo.php#difficolta">Come leggere le difficoltà</a>
    </div>
    <?php require __DIR__ . '/../templates/cards.php'; ?>
</section>
