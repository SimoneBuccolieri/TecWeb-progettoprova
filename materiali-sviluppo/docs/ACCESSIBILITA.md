# Controlli del 16 ottobre 2026

Ambito: sito locale, senza relazione di consegna né installazione sul server del corso. Nessun tempo personale contabilizzato. Obiettivo tecnico WCAG 2.1 A/AA; non certificazione di conformità né attestazione dei requisiti ufficiali del corso.

## Correzioni applicate

- Link del logo: il nome accessibile include ora tutto il testo visibile, con l’aggiunta «pagina iniziale». La precedente aria-label sostituiva anche il sottotitolo visibile (segnalazione axe label-content-name-mismatch).
- Istruzioni dei campi: username, password e recensione conservano il riferimento alle istruzioni anche quando compare un errore; aggiunto il collegamento alle istruzioni della descrizione nell’editor.
- Cambio desktop/mobile: se il menu viene chiuso mentre contiene il focus, questo torna al pulsante Menu anziché restare su un elemento nascosto.
- Testo ingrandito: ritorno a capo anche per parole lunghe e larghezza massima dei pulsanti. Risolti gli sforamenti dei messaggi di errore e delle azioni a 320 px con testo al 200% e spaziatura aumentata.
- Stampa: proporzioni delle immagini conservate. Bordo del pulsante di eliminazione distinto dal riempimento, eliminando un avviso del validatore.

Nessuna nuova funzione, dipendenza frontend o modifica del database. CSS: 561 righe complessive, mantenendo lo stile semplice.

## Validazione

Nu HTML Checker W3C: 17 pagine/stati, zero errori e zero warning. Inclusi pagina iniziale, catalogo pieno e vuoto, dettaglio ospite/autenticato, accesso, registrazione, progetto, 404, area personale, amministrazione, editor nuovo/modifica, conferma eliminazione ed errori server di registrazione, recensione ed editor. Le note informative sui tag vuoti con slash restano distinte dagli errori. Risultato: validazione-html.json.

W3C CSS Validator, profilo css3: tutti e tre i fogli validi, zero errori. Un warning in mobile.css: il servizio non verifica staticamente i valori delle variabili CSS. La variabile è definita in desktop.css, caricato prima del foglio mobile. Risultato: validazione-css.json. La prima richiesta JSON aveva prodotto HTTP 500; le risposte XML successive sono quelle usate per il risultato.

Script ripetibili, da avviare dalla radice del repository TecWeb-progettoprova con la demo locale attiva sulla porta 8085:

- `python3 materiali-sviluppo/tests/validate_markup.py`: invia HTML dimostrativo al W3C, sostituendo i token CSRF; usa soltanto account demo e POST non validi. Non usare su un sito con dati personali.
- `python3 materiali-sviluppo/tests/validate_css.py`: invia i tre CSS al W3C.
- `python3 materiali-sviluppo/tests/http_test.py`: collaudo funzionale su database temporaneo, 39 controlli superati dopo le correzioni del 16 ottobre; 42 dopo la revisione del 20 ottobre.

Sintassi PHP controllata su 30 file applicativi; sintassi JavaScript verificata con Node.

## Accessibilità automatica

axe-core 4.14.0, scaricato dal registro npm e usato solo nel collaudo temporaneo, non aggiunto al sito. Regole wcag2a, wcag2aa, wcag21a, wcag21aa e best-practice. Copie dell’HTML effettivamente generato, stesso CSS e JavaScript, token sostituiti con segnaposto.

16 stati analizzati nel browser integrato e in Chrome: home, catalogo, ricerca vuota, dettaglio ospite, registrazione, accesso, progetto, 404, area personale vuota, dettaglio autenticato, amministrazione, editor, conferma, errori di registrazione/editor/recensione. Dopo le correzioni: zero violazioni rilevate. Le segnalazioni incomplete di contrasto riguardano simboli decorativi (cuore, stella, menu): verificati separatamente con i colori ereditati; il significato è sempre presente nel testo e i simboli sono aria-hidden.

Tre stati ulteriori con recensioni e preferiti presenti, generati usando un database temporaneo poi eliminato: dettaglio, area personale e moderazione. Inclusa una recensione con una lunga sequenza senza spazi. Zero violazioni rilevate anche a 320 px con testo ingrandito.

## Controlli manuali e geometrici

- Salto al contenuto: attivazione da tastiera porta il focus a main#contenuto; contorno di focus visibile.
- Menu mobile: apertura da tastiera, Escape chiude e riporta il focus al pulsante; aria-expanded corrisponde allo stato. Verificato anche il cambio da 1280 a 320 px con focus su un link del menu.
- Moduli: invio vuoto dell’editor bloccato dal browser, focus sul campo titolo; accesso errato restituisce il riepilogo con focus, aria-invalid e collegamento all’errore. Ordine username → email verificato nella registrazione in Chrome.
- Albero di accessibilità: esaminati titoli, regioni, nomi dei controlli, messaggi e navigazione. Nessun campo senza etichetta nelle pagine pubbliche controllate; axe estende il controllo ai moduli amministrativi.
- Contrasti calcolati sui colori CSS: colori del testo dichiarati nel CSS almeno 6,61:1 sulle combinazioni di sfondo usate; testo bianco sui pulsanti almeno 7,96:1; focus almeno 4,77:1 sugli sfondi; bordi dei campi almeno 3,16:1. Dati in contrasto.json. I bordi decorativi delle schede non sono indicatori di controlli.
- Reflow: pagine pubbliche e amministrative a 320 px senza scorrimento orizzontale dell’intero documento. La tabella amministrativa mantiene uno scorrimento interno focalizzabile, con caption e intestazioni scope.
- Stress test su 16 stati e 3 stati popolati: larghezza 320 px, font radice al 200%, interlinea 1,5, spazio lettere 0,12 em, parole 0,16 em, spazio dopo i paragrafi 2 em. Dopo la correzione scrollWidth = innerWidth = 320 in tutti i casi. È un test di ingrandimento del testo e spaziatura, distinto dallo zoom nativo del browser.
- Foglio di stampa applicato a una copia del dettaglio e ispezionato visivamente: contenuti leggibili, comandi rimossi, immagine proporzionata. Non verificata la paginazione su carta/PDF.

## Limiti precisi

Non verificati tutti i flussi tramite sintesi vocale: la prova reale parziale con VoiceOver è descritta sotto. L’albero di accessibilità e axe non sostituiscono una sessione completa d’uso. Non provati NVDA, Firefox, dispositivi fisici o tutte le combinazioni di impostazioni assistive. Zoom nativo e anteprima di stampa sono descritti sotto. Nessuna dichiarazione di conformità universale: questi risultati documentano l’ambito effettivamente verificato.

## Estensione dei controlli: zoom nativo e stampa

Chrome: zoom nativo portato tramite il menu dal 100% al 400%, con il livello 400% osservato nell’interfaccia. Larghezza utile risultante 300 pixel CSS. Registrazione, home, catalogo, dettaglio, progetto, accesso, 404, area personale, amministrazione, editor e conferma eliminazione: scrollWidth = innerWidth = 300, senza sforamento dell’intero documento. Zoom ripristinato al 100% e verificato nel controllo nativo.

Anteprima di stampa nativa Chrome: conferma eliminazione su una pagina e catalogo completo su quattro pagine, con tutte le sei escursioni presenti e relativi dati; azioni e moduli esclusi. Nessun invio a stampanti fisiche. L’anteprima del catalogo ha segnalato indisponibilità dei file di estrazione del testo di Chrome; questo non è stato interpretato come esito di validazione dell’accessibilità del PDF. Aggiunti ritorno a capo delle parole lunghe anche in stampa e vincolo per tenere i titoli con il contenuto successivo. Rimossa una dichiarazione duplicata del logo.

Queste prove sostituiscono le precedenti indicazioni «zoom nativo al 400% non provato» e «paginazione non verificata» per gli stati sopra elencati. Non costituiscono prova su stampante fisica o certificazione del PDF generato.

## Prova reale parziale con VoiceOver e Chrome

VoiceOver attivato dall’utente su macOS. Chrome in primo piano; testo pronunciato osservato nel pannello Didascalia mediante catture dello schermo autorizzate dall’utente. Non si tratta di una deduzione dall’albero di accessibilità. Per evitare che le istruzioni generiche sostituissero gli annunci nel pannello, disattivata temporaneamente l’opzione «Comunica a voce alta le istruzioni per l’utilizzo dell’elemento nel cursore VoiceOver», poi ripristinata al valore iniziale attivo.

Annunci effettivamente osservati:

- Registrazione: «Nome utente», «Da 3 a 30 lettere, numeri o trattini bassi», «richiesto» e campo modificabile. Evidenza: voiceover-nome-utente.png.
- Registrazione: «Email», «richiesto» e tipo email; «Ripeti la password», «richiesto», «campo testo sicuro» e regione «Crea il tuo account».
- Accesso: attivando «Vai al contenuto», focus sul contenuto principale e annuncio «main».
- Accesso con credenziali inventate e non valide: focus sul riepilogo e annuncio «Nome utente o password non corretti». Attivando il collegamento dell’errore, focus sul nome utente; letti nome del campo, messaggio associato e «dati non validi». Evidenza: voiceover-errore-accesso.png. Nessun account creato né password salvata.
- Catalogo: filtro annunciato con nome «Difficoltà», valore «Tutte le difficoltà» e ruolo di menu a comparsa contratto.

Nessun nuovo difetto del sito dimostrato in questo campione, quindi nessuna modifica aggiuntiva al codice applicativo. Il suggerimento automatico di password di Chrome ha intercettato il focus: la lettura completa delle istruzioni del campo Password non è stata confermata. I comandi speciali di VoiceOver inviati dallo strumento non hanno prodotto esiti affidabili: rotore, lettura continua, esplorazione per intestazioni e completamento autonomo di tutti i flussi pubblici/amministrativi restano non verificati. La disponibilità delle opzioni nel filtro è stata osservata, ma il suo uso completo con i comandi VoiceOver non è dichiarato superato. VoiceOver lasciato attivo per l’utente.
