# Passo Verde — sito di prova TecWeb

Portale di escursioni realizzato dopo l’esame dei riferimenti locali. PHP, HTML5, CSS puro e JavaScript; nessun framework. Funzioni analoghe soprattutto a Biblioteca Luzzatti, con escursioni al posto dei libri. Il confronto puntuale è in [docs/CONFRONTO.md](docs/CONFRONTO.md).

## Provalo

Anteprima locale già avviata: http://127.0.0.1:8085

| Ruolo | Nome utente | Password |
| --- | --- | --- |
| Utente dimostrativo | user | user |
| Amministratore dimostrativo | admin | admin |

Oppure crea un nuovo account. Le credenziali semplici sono esclusivamente per la prova locale. Registrazioni nuove richiedono una password di almeno 8 caratteri.

Funzioni: elenco, ricerca per nome/località e difficoltà, dettaglio con immagine e descrizione, registrazione/accesso/uscita, preferiti personali, recensioni con voto (creazione/modifica/eliminazione), gestione amministrativa delle escursioni e moderazione recensioni.

Percorsi e località sono inventati. Foto illustrative con crediti nella pagina “Il progetto” e in docs/foto.json. Nessuna informazione escursionistica verificata per uso reale.

## Avvio rapido con PHP locale

Dalla cartella `sitoprova`, con PHP 8.1+ e le estensioni PDO, pdo_sqlite e mbstring:

```sh
php bin/setup.php --demo
php -S 127.0.0.1:8085 -t public bin/serve.php
```

Il database locale viene conservato in storage/passoverde.sqlite, fuori dalla cartella pubblica e ignorato da Git. Il comando di preparazione è ripetibile: non cancella dati né reimposta password esistenti. La prima esecuzione aggiunge sei escursioni e i due account di prova.

## PHP e MariaDB, come nei riferimenti

Il codice applicativo è lo stesso. Per avviare l’ambiente Docker facoltativo:

```sh
docker compose -p passoverde-prova up -d --build
docker compose -p passoverde-prova exec web php bin/setup.php --demo
```

Apri http://127.0.0.1:8086. Questa istanza usa un database **separato** dall’anteprima rapida: registrazioni e preferiti non sono condivisi. Per fermarla, conservando i dati:

```sh
docker compose -p passoverde-prova stop
```

Docker non è necessario per il sito e non va caricato sul server universitario. L’ambiente di prova usa PHP 8.3 e MariaDB 10.11, senza attribuire queste versioni al server del corso.

Per un server PHP/MariaDB già disponibile, configurare `DB_DSN` (per esempio `mysql:host=localhost;dbname=nome_database;charset=utf8mb4`), `DB_USER`, `DB_PASSWORD` nell’ambiente del server. Importare database/mariadb.sql e inizializzare i dati con bin/setup.php --demo, oppure usare un dump preparato. Esporre soltanto public/; mantenere src/, database/ e storage/ fuori dalla radice pubblica. Link applicativi relativi, installazione ammessa anche in una sottocartella. Verificare con l’ambiente effettivo del corso.

Esportazione dati del database configurato, da usare insieme allo schema su un database vuoto:

```sh
php bin/export.php > storage/dati-mariadb.sql
```

Il dump contiene anche gli hash delle password e gli eventuali dati degli utenti: conservarlo come il database, senza pubblicarlo nella cartella public/.

## Dove leggere il codice

- public/: una pagina PHP per ogni funzione, CSS, JavaScript e immagini.
- src/bootstrap.php: sessione, autorizzazioni, token, rendering e piccole funzioni comuni.
- src/database.php: connessione PDO e query preparate.
- src/pages/ e src/templates/: presentazione HTML con inserimento dei dati PHP; nessuna query nei template.
- database/: schemi relazionali e contenuti iniziali.
- bin/, tests/, Dockerfile e compose.yaml: strumenti facoltativi per installazione e collaudo; non aumentano le funzioni del portale.

Le entità del dominio sono quattro: utenti, escursioni, preferiti e recensioni. La tabella aggiuntiva login_attempts limita i tentativi di accesso. Preferiti e recensioni sono unici per coppia utente/escursione; la cancellazione di un’escursione rimuove i dati collegati.

## Verifiche

```sh
python3 tests/http_test.py
```

Avvia un server e un database SQLite temporanei, verifica i flussi HTTP e poi li rimuove senza modificare i dati della demo. La variante `--url` è riservata a un’istanza di collaudo separata: crea account artificiali e prova il blocco dei tentativi di accesso. Non usarla su dati personali o sul server consegnato.

Esiti e limiti in docs/VERIFICHE.md. Questa prova non è una relazione finale né una certificazione di accessibilità.

## Relazione

La relazione del prototipo è in [PDF](relazione/output/pdf/Relazione_Passo_Verde.pdf) e in [testo modificabile](relazione/Relazione_Passo_Verde.md). Riprende il formato accademico delle relazioni esaminate, con contenuti specifici del sito e risultati di verifica documentati. I quattro nomi sono segnaposto richiesti; matricole, email, URL sul server e contributi effettivi restano da completare. Il sigillo universitario del frontespizio proviene dall'asset istituzionale già presente nei materiali di esempio. Il PDF si rigenera con relazione/genera_pdf.py e ReportLab; dopo modifiche al frontespizio occorre aggiornare anche i dati espliciti nel generatore.
