# EMERALS RP — sito

- `public/` — sito pronto per **Cloudflare Pages** (HTML statico + `_worker.js` per le API)
- `php-source/` — sorgente PHP originale (funziona anche su un hosting con PHP)
- `tools/build.sh` — rigenera `public/` dal sorgente PHP

## Deploy su Cloudflare Pages (da GitHub)
Workers & Pages → Create → Pages → Connect to Git → scegli questo repo, poi:
- Framework preset: **None**
- Build command: *(vuoto)*
- Build output directory: **public**

Settings → Variables and Secrets (Production):
- `EMERALS_WEBHOOK` — URL webhook Discord per il modulo contatti
- `ROBLOX_UNIVERSE_ID` — per i giocatori in gioco (opzionale)
- `SITE_URL` — URL pubblico del sito (es. `https://nome.pages.dev`), per l'anteprima sui social (opzionale)

Ogni push sul branch `main` pubblica in automatico.

## Modificare il sito
Modifica `php-source/`, poi `./tools/build.sh`, poi commit e push.
(Per ritocchi rapidi si possono anche modificare direttamente i file in `public/assets/`; ricordati di rifletterli nel sorgente.)
