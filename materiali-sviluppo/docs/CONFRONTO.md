# Riferimenti esaminati prima dell’implementazione

15 ottobre 2026. Sito di prova richiesto da Simone, indipendente dal progetto finale del gruppo. Sorgenti originali, senza copiare codice o contenuti dei colleghi.

| Riferimento locale | File esaminati | Scelte riprese |
| --- | --- | --- |
| Biblioteca Luzzatti, TecWeb-main.zip | html/index.html, html/templates/header.html, html/catalogo.html, style.css, catalogo.php, recensione.php, utils.php | Catalogo → dettaglio; area personale, preferiti e recensioni; template separati e PDO |
| Casa Otium, Progetto_TecWeb | src/html/index.html, src/css/desktop.css, struttura src | Verde/crema, navigazione persistente, breadcrumb, introduzione testo/immagine, schede, CSS desktop/mobile/stampa |
| LuzzAuto, website-master.zip | docs/index.html, docs/assets/css/desktop.css, struttura | Ricerca GET, pagine PHP distinte, navigazione principale, aiuti al salto e stampa |
| Edil Scavi, Edil_Scavi.zip | PHP/template/header.php, struttura | Header/footer condivisi, percorso corrente, area autenticata |
| Vapor, TecWeb-Unipd-2025-main.zip | src/gestione_recensioni.php, struttura | Moderazione delle recensioni nell’area amministratore |
| Farmacia Archimede, FarmaciaArchimede-master.zip | src/Core/Template.php, public/css/main.css, struttura | Separazione public/src, catalogo a griglia e filtri; riferimento parziale, non modello di completezza |

## Criteri applicati

- HTML5 semantico, attributi quotati, tag vuoti chiusi; CSS puro e JavaScript separato, nessun framework o pacchetto frontend.
- Pagine PHP lato server con template di presentazione, link relativi, menu, breadcrumb, salto al contenuto, footer e stampa. Font di sistema per evitare dipendenze esterne.
- PHP/PDO e database relazionale: utenti, escursioni, preferiti, recensioni, tentativi di accesso. Schema MariaDB disponibile; SQLite è solo l’opzione immediata per la prova locale.
- CRUD escursioni per amministratore e CRUD recensioni per autore; moderazione da amministratore. Preferiti personali persistenti.
- Password con hash, token CSRF, sessioni, autorizzazioni server, query preparate e escaping all’uscita. Gli errori e le credenziali presenti nei riferimenti non sono replicati.
- Form con etichette, istruzioni, validazione client/server ed errori; operazioni principali disponibili anche senza JavaScript.

## Direzione visiva

Sito universitario tradizionale a pagine: intestazione con logo e menu, barra del percorso, contenuto a larghezza limitata, immagini e schede, pulsanti espliciti. Verde bosco e crema come Casa Otium, senza riprodurne marchio, fotografie o testi. Il nome originale è **Passo Verde**. I percorsi sono dimostrativi, con nomi e dati inventati e fotografie illustrative.

## Limiti della fedeltà

I riferimenti non sono identici fra loro: sono ripresi convenzioni, stack e organizzazione comuni, non un clone di un singolo sito. Fonte requisiti: Appunti/Regole generali progetti.txt ed ESAME.md. Questo documento non certifica conformità al regolamento, né sostituisce relazione di gruppo, collaudo con lettore di schermo o installazione sul server universitario.

## Semplificazione richiesta

CSS ridotto da 1363 a 552 righe leggibili: dimensioni dei titoli fisse, griglie a colonne semplici, bordi ordinari e font Arial. Eliminate decorazioni e sezioni promozionali; home, login e registrazione usano testi descrittivi. Restano menu, breadcrumb, catalogo, moduli, layout mobile e stampa. Non sono cambiati controller, database o funzioni applicative.

## Controllo del codice del 16/10/2026

Confrontati 37 file PHP, HTML, CSS, JS e SQL applicativi con 286 file dei sei progetti locali (ZIP e Casa Otium), escludendo vendor/node_modules. Nessuna finestra di 60 token esatti consecutivi condivisa, ignorando gli spazi ma conservando gli identificatori. Dati in confronto-codice.json; script tests/compare_examples.py.

È un controllo di blocchi letterali, non un rilevatore completo di plagio: non misura equivalenza semantica, codice rinominato o somiglianza visiva. Catalogo, login, preferiti, recensioni, PDO, menu e breadcrumb sono convenzioni comuni; non dimostrano da soli copiatura. Tema escursionistico, modello dei dati dei percorsi, testi e logo sono specifici di questa prova; le foto provengono da Unsplash e hanno crediti, non dai siti di riferimento. Non sono state introdotte rinomine o anomalie allo scopo di eludere controlli. Il codice di questa prova è stato realizzato con assistenza AI: il confronto con i riferimenti non dimostra un contributo autonomo degli studenti né garantisce il giudizio della docente.


## Arte Per Tutti - riferimento aggiunto il 20/10/2026

Esaminato Progetto-Modifiche.zip: classe DBAccess, controller, modelli HTML con segnaposto e tre fogli di stile. Analisi separata in guida_progetto/ANALISI_PROGETTO_MODIFICHE.md del workspace. Per Passo Verde sono stati adottati metodi dedicati in una classe di accesso ai dati e modelli HTML per i componenti condivisi. Non sono stati importati file o dati dal riferimento. Restano PDO con parametri preparati, escaping al punto di output, CSRF, controllo delle autorizzazioni e struttura HTML accessibile. Il confronto dei 28 stati è in confronto-struttura-2026-10-20.json; la modifica riguarda l'organizzazione del codice.
