# Materiali di sviluppo di Passo Verde

Questa cartella raccoglie strumenti e documenti di lavoro. Il sito e la relazione PDF da consegnare sono in `../consegna/`.

- `docs/`: evidenze, esiti delle validazioni, crediti e confronti.
- `tests/`: controlli funzionali, HTML/CSS e confronto con gli esempi.
- `relazione/`: testo modificabile, immagini, generatori e revisione; il generatore scrive il PDF in `../consegna/`.
- `storage/`: database SQLite della demo locale esistente, conservato senza esportarne i dati nella consegna.
- `bin/serve.php`: router del server PHP locale.
- `Dockerfile`, `compose.yaml`: ambiente facoltativo PHP/MariaDB per sviluppo.
- `README-precedente.md`: istruzioni precedenti allo spostamento, conservate come storico; usare i percorsi aggiornati di questo README.

## Anteprima locale

Dalla cartella `TecWeb-progettoprova`, usando PHP con pdo_sqlite e mbstring:

```sh
DB_DSN="sqlite:$PWD/materiali-sviluppo/storage/passoverde.sqlite" php consegna/bin/setup.php --demo
DB_DSN="sqlite:$PWD/materiali-sviluppo/storage/passoverde.sqlite" php -S 127.0.0.1:8085 -t consegna/public materiali-sviluppo/bin/serve.php
```

Indirizzo: http://127.0.0.1:8085. I dati locali rimangono nella cartella di sviluppo.

## Collaudo

```sh
python3 materiali-sviluppo/tests/http_test.py
python3 materiali-sviluppo/tests/validate_markup.py
python3 materiali-sviluppo/tests/validate_css.py
```

Il test HTTP predefinito usa un database temporaneo separato. La validazione HTML richiede la demo sulla porta 8085 e invia soltanto pagine con dati inventati ai servizi W3C. La validazione CSS invia i fogli del sito al W3C. Non eseguire la variante HTTP `--url` su dati personali: crea e modifica dati di collaudo.

I confronti storici `compare_php_revision.py` e `compare_style_revision.py` richiedono le copie locali in `archivio/`, escluse da Git. `compare_examples.py` richiede anche gli esempi degli anni precedenti nella cartella esterna `esempio progetto anno scorso/`. Questi materiali non sono necessari per avviare il sito o il test HTTP; gli esiti già prodotti restano in `docs/`.

## Ambiente Docker facoltativo

Da questa cartella:

```sh
docker compose -p passoverde-prova up -d --build
docker compose -p passoverde-prova exec web php bin/setup.php --demo
```

Indirizzo: http://127.0.0.1:8086. Database MariaDB separato da quello SQLite. Docker non va installato sul server universitario.

## Relazione

Modificare `relazione/Relazione_Passo_Verde.md`; frontespizio e metadati sono anche nel generatore. Con Python e ReportLab:

```sh
python3 relazione/genera_pdf.py
```

Per rigenerare gli schemi usare `relazione/genera_figure.py` con Pillow. Il PDF finale è in `../consegna/Relazione_Passo_Verde.pdf`.
