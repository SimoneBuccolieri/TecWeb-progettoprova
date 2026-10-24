# Verifiche della prova

**Aggiornamento 16/10/2026:** validazione finale e audit documentati in [ACCESSIBILITA.md](ACCESSIBILITA.md). Le note del 15/10 sotto sono storico e vanno lette insieme all’aggiornamento.

15 ottobre 2026. Nessun tempo personale di studio o progetto contabilizzato.

## Funzioni e sicurezza

39 controlli HTTP superati su PHP locale 8.5/SQLite e, separatamente, su PHP 8.3/MariaDB 10.11 in Docker. Test in tests/http_test.py. Verificati:

- ricerca combinata e assenza di risultati; wildcard letterali, tentativo SQL injection, filtri e ID malformati;
- registrazione valida/non valida, password con hash, login, logout, persistenza dell’account e limitazione dei tentativi;
- esclusione di ospiti e utenti dall’amministrazione; ignorato il ruolo admin inviato nel form di registrazione;
- aggiunta/rimozione preferiti, assenza di duplicati e isolamento tra due account;
- creazione/modifica/eliminazione recensioni, limiti del voto/testo, escaping di stringhe HTML, impossibilità per un altro utente di cancellare;
- creazione/modifica/eliminazione escursioni, validazione numerica, moderazione amministratore e cancellazione a cascata;
- rifiuto token CSRF errati e metodi GET sulle azioni di modifica; richiesta GET della conferma non elimina dati.

Corretto durante il collaudo un conflitto fra il nome del parametro del renderer e i dati del modulo amministrativo, emerso su PHP 8.5. Password conservate integralmente senza rimuovere spazi. I due cicli finali da 39 controlli sono successivi a queste correzioni.

Account artificiali e tentativi di accesso del collaudo MariaDB rimossi; contenitori di collaudo fermati, volume demo conservato. Il test SQLite standard usa un database temporaneo e lo elimina da sé. L’anteprima PHP resta disponibile durante la consultazione.

## Markup e presentazione

- Controllo sintattico PHP su tutti i file iniziali; controllo del JavaScript con Node.
- Nu HTML Checker W3C su 12 pagine/stati: home, catalogo, scheda anonima e autenticata, registrazione, login, progetto, 404, area personale, amministrazione, editor e conferma eliminazione.
- Corrette le tre segnalazioni relative all’opzione iniziale vuota nei select obbligatori. Esito finale in validazione-html.json. Le note informative sui tag vuoti con slash derivano dalla scelta di mantenere la sintassi compatibile con le indicazioni locali; gli attributi sono quotati.
- Browser integrato: fotografie caricate, home desktop osservata (larghezza CSS rilevata 1138 px), scheda osservata a 390 px senza overflow orizzontale (scrollWidth = innerWidth = 390).
- Menu mobile: apertura funzionante; Escape richiude e riporta il focus al pulsante. Percorso di navigazione e nomi dei controlli esposti nell’albero di accessibilità.
- CSS comune, mobile e stampa separati. Dopo la semplificazione: 552 righe totali (410 desktop, 64 mobile, 78 stampa), mantenendo una dichiarazione per riga. Rimossi clamp, calc/max, contatori, proprietà logiche e ritagli decorativi; mantenuti Flexbox, Grid semplice, media query, focus e salto al contenuto.
- Dopo la semplificazione ripetuti i 39 controlli HTTP su SQLite: tutti superati. Home osservata su desktop e a 390 px; nessun overflow orizzontale nella home mobile. La validazione HTML sopra documentata precede questa revisione dei testi e dei template.

## Limiti residui

Non è una certificazione WCAG: restano da fare una prova estesa con lettore di schermo, navigazione completa da tastiera, controlli di contrasto per tutti gli stati, zoom/reflow dell’intero sito, prova della stampa, validazione completa CSS e confronto fra più browser. Le prove HTTP non sostituiscono questi controlli.

Non effettuata installazione sul server del corso. Regolamento e relazione finale di gruppo non fanno parte di questa prova. Su richiesta di Simone sono stati semplificati CSS e impaginazione e sostituiti i testi promozionali con descrizioni delle funzioni.

## Riorganizzazione del 16/10/2026

Separati `consegna/` e `materiali-sviluppo/`. Aggiornati router locale, percorsi dei test, configurazione del contesto Docker, istruzioni e relazione. Ripetuti i 39 controlli HTTP sul database temporaneo: tutti superati. Anteprima locale riavviata sulla porta 8085: home, catalogo, registrazione, CSS e JavaScript rispondono 200; percorso inesistente 404. Database SQLite esistente conservato nei materiali di sviluppo. Dump MariaDB per la consegna generato da un database nuovo con soli due account dimostrativi e sei escursioni; non esportati dati della demo usata durante il lavoro. Non ripetuta in questa occasione l'importazione su MariaDB, né eseguita una nuova installazione Docker. Relazione rigenerata per descrivere i nuovi percorsi.

## Revisione dei testi e leggibilità del 19/10/2026

Riscritte le sei descrizioni iniziali in forma descrittiva, conservando nomi, località, misure e fotografie. Aggiornati seed, dump dimostrativo e soltanto le descrizioni originali ancora invariate del database locale. Semplificati testo dell'area personale, meta description predefinita, conferma di registrazione e messaggio generico 500. Formattati i 14 file di presentazione (pagine e template) senza introdurre nuovi componenti o cambiare CSS e JavaScript. Rivisti alcuni passaggi ripetitivi della relazione, mantenendo assistenza AI dichiarata, crediti, dati mancanti e limiti dei test. PDF rigenerato e controllato visivamente su tutte le 12 pagine.

Verifiche successive: sintassi PHP valida; 39 controlli HTTP superati su database temporaneo; confronto strutturale prima/dopo su 17 pagine e stati, inclusi moduli con errori. Elementi e attributi HTML invariati, escludendo token CSRF e meta description riscritta. La formattazione modifica soltanto spazi e ritorni a capo; i cambiamenti testuali sono descritti sopra. Non ripetuti W3C, axe o VoiceOver in questa revisione; gli esiti precedenti restano datati al rispettivo collaudo.

Versione precedente conservata in archivio/prima-revisione-testi-2026-10-19.zip. L'intervento riguarda chiarezza e manutenzione: non è una verifica di riconoscibilità dell'AI e non ne garantisce l'assenza di indizi.

## Semplificazione PHP del 19/10/2026

Pagine riscritte con variabili, condizioni e cicli espliciti. Rimosso il renderer basato su extract/compact: i controller includono direttamente intestazione, pagina e piè di pagina. Eliminati dichiarazioni di tipo nelle funzioni applicative, funzioni freccia, callback per comporre i valori SQL e validazione generica per gruppi di campi. La validazione dell'escursione è nella pagina dell'editor; la media delle recensioni usa un ciclo. Connessione e query preparate restano condivise, come controllo di accesso, token CSRF, escaping e associazioni accessibili degli errori. Semplificati anche gli script di preparazione ed esportazione.

Il codice è stato riscritto sul prototipo, senza prelevare sorgenti da Biblioteca Luzzatti. Il riferimento riguarda il livello di programmazione procedurale; nomi, flussi e modello dei dati rimangono quelli di Passo Verde. La maggiore lunghezza dei file deriva dai passaggi espliciti e dalle istruzioni distribuite su più righe.

Verifiche successive:

- Sintassi valida su tutti i 33 file PHP della consegna.
- Tutti i 39 controlli HTTP superati su PHP 8.5/SQLite e database temporaneo: ricerca, account, autorizzazioni, preferiti, recensioni, amministrazione, validazione, escaping, CSRF, limitazione dei tentativi e cancellazioni.
- Confronto tra archivio precedente e revisione su 28 pagine/stati: stessi codici HTTP, elementi, attributi e testi HTML, esclusi i token CSRF casuali e gli spazi di formattazione. Comprende account con dati, preferiti, recensioni, conferme dell'autore e dell'amministratore, errori e accessi negati. Script ripetibile in tests/compare_php_revision.py; esito in docs/confronto-php-2026-10-19.json.
- Preparazione ripetuta del database dimostrativo senza variazioni dei dati; esportazione SQL identica, sullo stesso database temporaneo, a quella dello script precedente.
- CSS, JavaScript, immagini, schema e dump non modificati. Non ripetuti MariaDB, W3C, axe, VoiceOver, zoom o stampa: la verifica HTML confronta l'output delle due versioni, non costituisce un nuovo collaudo con tecnologie assistive.

Aggiornate le sezioni 4.1, 6.1, 6.2 e 6.4 della relazione. PDF rigenerato su 12 pagine e controllato visivamente su tutte le pagine: indice, tabelle, figure e testo senza sovrapposizioni o righe finali isolate. La versione precedente è conservata in archivio/prima-semplificazione-php-2026-10-19.zip. Nessun dato del database della demo è stato modificato dal collaudo.


## Organizzazione ispirata ad Arte Per Tutti - 20/10/2026

Le query sono state raccolte nella classe GestioneDati in src/database.php, con metodi per catalogo, utenti, preferiti, recensioni e tentativi di accesso. I controller mantengono validazione e autorizzazioni e chiamano metodi specifici; preparazione ed esportazione usano la stessa classe. La connessione PDO resta aperta soltanto quando serve e tutti i valori esterni passano per parametri preparati. Esportazione limitata ai quattro nomi di tabella previsti.

Intestazione, scheda del catalogo, risultato vuoto e footer sono ora modelli in src/html. I file PHP dei componenti leggono i modelli; i valori testuali vengono sottoposti a escaping e sostituiti con strtr in un solo passaggio. Questo conserva come testo eventuali segnaposto presenti nei dati utente. Le altre pagine di presentazione restano in PHP e usano condizioni e cicli ordinari. L'organizzazione riprende tecniche comuni osservate nel riferimento, con implementazione e contenuti propri di Passo Verde.

Controlli eseguiti dopo la riorganizzazione:

- Sintassi valida per i 33 file PHP della consegna.
- Tutti i 42 controlli HTTP superati su PHP 8.5/SQLite e database temporaneo. Ai 39 precedenti sono aggiunti escaping del titolo nelle schede, segnaposto letterali in titolo/metadati e segnaposto letterali nelle schede.
- Confronto della versione precedente con quella attuale su 28 pagine/stati: identici codici di risposta, elementi, attributi e testi HTML, esclusi token CSRF casuali e spazi di formattazione. Inclusi dati presenti, autenticazione, errori, conferme e accessi negati. Script tests/compare_style_revision.py; risultato docs/confronto-struttura-2026-10-20.json.
- Preparazione ripetuta del database senza modifiche ai dati preesistenti. Esportazione SQL identica a quella precedente sullo stesso database temporaneo.
- CSS, JavaScript, immagini, schema, dump e dati iniziali invariati rispetto all'archivio di partenza. Il nuovo controllo sui template non è una prova ulteriore con VoiceOver, axe, W3C, zoom o stampa. MariaDB non ricollaudato in questa occasione.

La versione precedente è in archivio/prima-riorganizzazione-2026-10-20.zip. Aggiornati README, descrizione del codice, riferimenti ed esiti nella relazione. PDF rigenerato su 12 pagine e controllato visivamente in tutte le pagine, senza sovrapposizioni o una pagina finale isolata. I test usano dati artificiali temporanei; il database della demo non è stato modificato dal collaudo.
