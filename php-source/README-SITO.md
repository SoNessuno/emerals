# Sito EMERALS RP

Sito vetrina in **PHP 8 + HTML + CSS + JavaScript** (nessuna dipendenza, nessuna build).

## Struttura
- `index.php` — pagina principale
- `includes/` — `config.php` (impostazioni), `data.php` (distretti, staff, FAQ), `header.php`, `footer.php`
- `api/stats.php` — membri Discord e giocatori Roblox, con cache su file (60 s)
- `api/contact.php` — modulo contatti (CSRF, honeypot, rate limit, webhook Discord opzionale)
- `assets/` — CSS, JS, logo
- `cache/` — cache e messaggi di riserva (non accessibile dal web)

## Configurazione
1. **Giocatori Roblox live**: imposta le variabili d'ambiente `ROBLOX_UNIVERSE_ID` e `ROBLOX_PLACE_ID`
   (oppure creane le costanti in `includes/config.local.php`). Se vuote, il contatore resta nascosto.
2. **Modulo contatti → Discord**: imposta `EMERALS_WEBHOOK` con l'URL di un webhook Discord.
   Senza webhook i messaggi vengono salvati in `cache/messages.log`.
3. **Invito Discord**: costante `DISCORD_INVITE` in `includes/config.php`.

## Avvio in locale
```
php -S localhost:8000 -t sito-emerals-rp
```
Requisiti hosting: PHP 8.1+ con estensione `curl`, cartella `cache/` scrivibile.
