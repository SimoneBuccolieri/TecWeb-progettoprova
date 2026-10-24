# Passo Verde - file per la consegna

Portale PHP per consultare escursioni, cercare per nome/località e difficoltà, gestire account, preferiti e recensioni. L'amministratore gestisce le escursioni e rimuove recensioni. HTML5, CSS e JavaScript separati, nessun framework.

## Contenuto

- `public/`: unica cartella da esporre sul web; pagine PHP, CSS, JavaScript, immagini e configurazione Apache.
- `src/`: classe GestioneDati con PDO, funzioni comuni, pagine di presentazione, template PHP e modelli HTML in `src/html/`.
- `database/`: schema MariaDB, dump dimostrativo, dati iniziali e schema SQLite per l'opzione locale.
- `bin/setup.php`: preparazione del database e, con `--demo`, dati iniziali.
- `bin/export.php`: esportazione dei dati del database configurato in SQL per MariaDB.
- `storage/`: vuota; destinazione predefinita dell'eventuale database SQLite, fuori dalla radice pubblica.
- `Relazione_Passo_Verde.pdf`: relazione del prototipo; dati dei componenti e URL pubblico ancora da completare.

## Requisiti e installazione MariaDB

PHP 8.1 o successivo, PDO, pdo_mysql e mbstring. Configurare nell'ambiente del server `DB_DSN` (esempio: `mysql:host=localhost;dbname=nome_database;charset=utf8mb4`), `DB_USER` e `DB_PASSWORD`. Non inserire credenziali personali nei file pubblici.

Importare `database/dump-mariadb.sql` in un database dedicato vuoto: comprende schema, sei escursioni e account dimostrativi. In alternativa, con la connessione configurata, eseguire dalla cartella `consegna`:

```sh
php bin/setup.php --demo
```

Non importare nuovamente il dump su un database già popolato. Il comando setup, invece, non cancella dati né reimposta password di utenti esistenti.

Esporre soltanto `public/`; mantenere `src/`, `database/`, `storage/`, `bin/` e la relazione fuori dalla radice web. I link sono relativi e supportano una sottocartella. La configurazione Apache usa `.htaccess` e mod_rewrite: verificare la configurazione effettiva del server universitario e i permessi assegnati. Installazione sul server del corso ancora da eseguire.

## Account dimostrativi

| Ruolo | Nome utente | Password |
| --- | --- | --- |
| Utente | user | user |
| Amministratore | admin | admin |

Le nuove registrazioni richiedono almeno 8 caratteri. Percorsi e località sono inventati; foto illustrative con crediti nella pagina «Il progetto». Il dump dimostrativo non contiene account personali o dati del collaudo.

## Sviluppo e verifiche

Gli strumenti locali, i risultati dei controlli e i sorgenti della relazione sono conservati separatamente in `../materiali-sviluppo/`. Non occorrono per eseguire il sito sul server MariaDB. Per riavviare l'anteprima locale leggere il README in quella cartella.
