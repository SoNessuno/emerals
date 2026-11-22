# EMERALS RP — sito

Sito "OPS – destroyed by RGA": terminale compromesso, scritta ASCII, messaggio, avviso, logo e sfondo RGA che si alternano. Link al canale Telegram https://t.me/RedGhostArmyV4.

- `public/index.html` — pagina principale (HTML, CSS e JS in un solo file); `public/404.html` è la stessa pagina
- `public/registro.html` — pagina Registro; i record si leggono da `public/registro.json`
- `public/registro.json` — elenco dei record, per ora vuoto (`[]`)
- `public/assets/` — `rga-bg.jpg` (sfondo), `rga-logo.jpg` (logo), `favicon.png`
- `public/_headers` — sicurezza e cache (`assets` 30 giorni; `registro.json` mai in cache)

## Come aggiungere un record al Registro
Modifica `public/registro.json` e inserisci un elemento per ogni account, per esempio:

```json
[
  {
    "username": "nomeutente",
    "nome": "Nome visualizzato",
    "id": "123456789012345678",
    "violazione": "Tentativo di raid e nuke",
    "data": "22/11/2026"
  }
]
```
Vengono mostrati solo questi campi: username, nome visualizzato, ID Discord, violazione, data. Il file va salvato come JSON valido (virgolette doppie, virgole tra i record).

## Pubblicazione (Cloudflare Pages da GitHub)
Framework preset: **None** · Build command: *(vuoto)* · Build output directory: **public**. Ogni push su `main` pubblica in automatico. Nessuna variabile d'ambiente necessaria.
Se cambi un'immagine, cambia anche il `?v=` nel codice (gli `assets` restano 30 giorni in cache).
