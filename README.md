# Passo Verde - progetto di prova

Portale dimostrativo per escursioni e trekking, condiviso come base di partenza per il gruppo di Tecnologie Web. Questa è una versione di lavoro: il gruppo può provarla, discuterne le scelte e modificarla.

Il sito permette di cercare escursioni per nome, località e difficoltà, consultarne i dettagli, registrarsi e accedere, salvare preferiti e pubblicare, modificare o eliminare recensioni. L'amministratore gestisce il catalogo e rimuove recensioni.

## Cartelle

- [`consegna/`](consegna/): sito PHP, immagini, database SQL, istruzioni di installazione e relazione PDF.
- [`materiali-sviluppo/`](materiali-sviluppo/): test, risultati dei controlli, sorgenti della relazione e ambiente Docker facoltativo.

Il contenuto proviene dalla precedente cartella locale `sitoprova`, rinominata `TecWeb-progettoprova`. Database locali, cache, file di sistema e archivi di backup restano sul computer e sono esclusi da Git. Gli esempi degli anni precedenti non fanno parte di questo repository.

## Avvio rapido

Occorrono PHP 8.1 o successivo con PDO, pdo_sqlite e mbstring. Non servono framework né Composer. Su macOS o Linux, dopo aver scaricato il repository:

```sh
git clone https://github.com/SimoneBuccolieri/TecWeb-progettoprova.git
cd TecWeb-progettoprova
php consegna/bin/setup.php --demo
php -S 127.0.0.1:8085 -t consegna/public materiali-sviluppo/bin/serve.php
```

Aprire http://127.0.0.1:8085. La preparazione crea il database SQLite in `consegna/storage/`, con sei escursioni inventate e gli account dimostrativi:

| Ruolo | Nome utente | Password |
| --- | --- | --- |
| Utente | user | user |
| Amministratore | admin | admin |

Le foto sono illustrative; i percorsi non sono itinerari reali da seguire. Per MariaDB e per l'installazione sul server leggere [`consegna/README.md`](consegna/README.md). Per usare Docker seguire [`materiali-sviluppo/README.md`](materiali-sviluppo/README.md).

## Controlli

Con Python 3 e PHP disponibili, dalla radice del repository:

```sh
python3 materiali-sviluppo/tests/http_test.py
```

Il test verifica 42 casi su un database temporaneo e non modifica i dati della demo. I risultati e i limiti degli altri controlli sono in [`materiali-sviluppo/docs/VERIFICHE.md`](materiali-sviluppo/docs/VERIFICHE.md) e [`ACCESSIBILITA.md`](materiali-sviluppo/docs/ACCESSIBILITA.md).

## Stato della base di partenza

Il codice comprende le funzioni elencate; la relazione descrive l'implementazione e le prove svolte. Prima di una consegna restano da completare i dati dei componenti e i contributi effettivi, installare e collaudare la versione attuale sul server con MariaDB e completare la prova con lettore di schermo.

La relazione modificabile è in [`materiali-sviluppo/relazione/`](materiali-sviluppo/relazione/); il PDF è in [`consegna/Relazione_Passo_Verde.pdf`](consegna/Relazione_Passo_Verde.pdf).
