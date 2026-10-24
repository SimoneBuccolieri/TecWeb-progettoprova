# Revisione della relazione - 16 ottobre 2026

Confrontati testo, controller PHP, template, JavaScript, CSS, schema MariaDB, dati iniziali e file di esito delle validazioni. Non modificato il codice del sito e non ripetuti test applicativi per sole modifiche documentali.

## Coerenza con il sito

- Ruoli, query di ricerca, preferiti e recensioni corrispondono ai controller. Precisati ritorno all'area personale dopo il login, rimozione dei preferiti nel dettaglio, conferma delle cancellazioni e moderazione senza approvazione preventiva.
- Schema e diagramma derivano da database/mariadb.sql: chiavi primarie/esterne, unicità, cardinalità, tabella di supporto separata, indice e tipi effettivi. Date di aggiornamento descritte come VARCHAR(19), non come colonne SQL DATETIME.
- Palette, 1100 px di larghezza massima e soglie 800/550 px verificati nei CSS. Confermate 412/64/85 righe.
- Confermati i risultati JSON: 17 stati HTML senza errori o avvisi; tre CSS validi con un avviso sulle variabili; contrasti coerenti con i valori riportati. Gli esiti dei controlli precedenti non sono presentati come nuovi collaudi.
- VoiceOver resta una prova parziale. Non attribuiti test a utenti esterni, Firefox, NVDA o dispositivi fisici; non dichiarata un'installazione sul server.

## Confronto del dettaglio

| Riferimento | Caratteristiche riscontrate | Riscontro in Passo Verde |
| --- | --- | --- |
| Biblioteca Luzzatti, 14 pagine | Ruoli, catalogo, schema organizzativo, frontend/backend, database e accessibilità | Stesse aree coperte; maggior precisione su condizioni dei test e loro limiti. |
| LuzzAuto, 11 pagine | Analisi utente/ricerche, convenzioni, schema DB, CSS/JS/PHP, input e test | Copertura paragonabile, con esempio del flusso trekking, mappa, diagramma DB, palette e breakpoint effettivi. |
| Casa Otium, 16 pagine | Mappa, schema DB, metadati, layout, form, test e contributi | Copertura essenziale degli stessi temi; non ripresi flussi di adozione o funzioni assenti. |
| Edil Scavi, 20 pagine | Descrizione estesa di dati, sviluppo e test, organizzazione del gruppo e committente | Passo Verde è più compatto: non ha un committente o flussi di preventivo; non inventate attività per uniformare la lunghezza. |

La valutazione riguarda argomenti e precisione delle spiegazioni, non l'uguaglianza del numero di pagine. Aggiunti mappa e diagramma specifici del prototipo, dettagli SQL, palette, soglie del layout, esempio sui dati iniziali e comportamento degli errori. Rimossi due paragrafi ripetitivi di autodifesa sulla copiatura: la relazione descrive ora le scelte concrete del portale.

## Somiglianze testuali

Confronto esaustivo di sequenze di 12 parole nel corpo della relazione con il testo estratto dai quattro PDF: zero sequenze condivise. Normalizzazione in minuscolo, Unicode NFKC e token alfanumerici. Titoli, nomi tecnici e forma accademica possono naturalmente somigliarsi. Dati in confronto-relazioni.json.

L'estrazione PDF può unire o spezzare parole. Il confronto non misura parafrasi, equivalenza semantica o somiglianza grafica e non garantisce il giudizio della docente. Il testo è specifico per l'implementazione presente; non è una relazione precedente con il solo cambio di nomi.

Rimangono intenzionalmente incompleti matricole, email, URL pubblico e contributi reali dei quattro nomi segnaposto.


## Aggiornamento del 19 ottobre: PHP procedurale

Aggiornata la descrizione dell'organizzazione del codice alle inclusioni dirette con require e alle variabili preparate dalle pagine. Descritti controlli dell'editor e calcolo della media con condizioni e cicli ordinari. Riportati i nuovi 39 controlli HTTP su SQLite, il confronto di 28 pagine/stati e la verifica sintattica dei 33 file PHP della consegna. Gli esiti MariaDB, W3C, axe e delle prove manuali rimangono riferiti ai collaudi precedenti. Conservati formato della relazione, contributi da completare, crediti, dichiarazione di assistenza AI e limiti del test con VoiceOver.

Il confronto delle sequenze di 12 parole descritto sopra riguarda la versione del 16 ottobre e non è stato ripetuto sulle revisioni successive.


## Aggiornamento del 20 ottobre: accesso ai dati e modelli HTML

Aggiornate le sezioni 4.1, 6.1, 6.2 e 7.1 per descrivere GestioneDati, i modelli HTML condivisi, i 42 controlli funzionali e il confronto di 28 stati. Il riferimento ad Arte Per Tutti è successivo all'implementazione iniziale; non viene presentato come fonte esaminata prima di essa. Gli esiti MariaDB e delle tecnologie assistive conservano le rispettive date. Rimangono contributi da completare, crediti e dichiarazione di assistenza AI. Copertina aggiornata al 20 ottobre 2026.
