# EMERALS RP — sito

Pagina unica "OPS – destroyed by RGA": terminale compromesso, scritta ASCII, messaggio e sfondo RGA che si alternano.

- `public/index.html` — tutta la pagina (HTML, CSS e JS in un solo file)
- `public/404.html` — stessa pagina, mostrata su qualsiasi indirizzo inesistente
- `public/assets/rga-bg.jpg` — sfondo; `public/assets/favicon.png` — icona
- `public/_headers` — intestazioni di sicurezza e cache (gli `assets` restano in cache 30 giorni: se cambi l'immagine, cambia anche il nome del file o il `?v=` nel codice)

## Pubblicazione (Cloudflare Pages da GitHub)
Framework preset: **None** · Build command: *(vuoto)* · Build output directory: **public**
Ogni push sul branch `main` pubblica in automatico. Non servono variabili d'ambiente: il vecchio modulo contatti e le API (`_worker.js`) sono stati rimossi.
