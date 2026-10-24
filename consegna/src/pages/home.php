<section class="hero">
    <div>
        <h1>Escursioni e trekking</h1>
        <p>Passo Verde è un portale per consultare escursioni e cercare percorsi per nome, località o difficoltà.</p>
        <p>Ogni scheda contiene una descrizione, un’immagine, la lunghezza, la durata e il dislivello.</p>
        <a class="button" href="escursioni.php">Consulta le escursioni</a>
    </div>
    <img src="images/montagne.jpg" alt="Cime rocciose illuminate dal sole, con un escursionista in lontananza" width="1600" height="1067" />
</section>
<section class="quick-search" aria-labelledby="ricerca-titolo">
    <h2 id="ricerca-titolo">Cerca un’escursione</h2>
    <form action="escursioni.php" method="get">
        <div>
            <label for="q">Nome o località</label>
            <input id="q" name="q" type="search" maxlength="100" placeholder="Es. valle, prati, rifugio" />
        </div>
        <div>
            <label for="difficulty">Difficoltà</label>
            <select id="difficulty" name="difficulty">
                <?= difficulty_options('') ?>
            </select>
        </div>
        <button type="submit">Cerca</button>
    </form>
</section>
<section class="section">
    <h2>Alcune escursioni del catalogo</h2>
    <?php require __DIR__ . '/../templates/cards.php'; ?>
    <p><a href="escursioni.php">Consulta tutto il catalogo</a></p>
</section>
<section class="section">
    <h2>Funzioni per gli utenti registrati</h2>
    <ul>
        <li>
            Salvare le escursioni tra i preferiti e consultarle nell’area personale.
        </li>
        <li>
            Scrivere una recensione con una valutazione da 1 a 5.
        </li>
        <li>
            Modificare o eliminare le proprie recensioni.
        </li>
    </ul>
    <p><a href="registrazione.php">Registrati</a> oppure <a href="login.php">accedi al tuo account</a>.</p>
</section>
