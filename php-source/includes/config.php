<?php
declare(strict_types=1);

/**
 * Configurazione del sito EMERALS RP.
 * Nessun segreto qui dentro: i valori sensibili (webhook) vanno impostati
 * come variabili d'ambiente del server (EMERALS_WEBHOOK) oppure in includes/config.local.php
 * (file ignorato dal controllo versione, vedi .gitignore).
 */

if (is_file(__DIR__ . '/config.local.php')) {
    require __DIR__ . '/config.local.php';
}

defined('SITE_NAME')          || define('SITE_NAME', 'EMERALS RP');
defined('SITE_TAGLINE')       || define('SITE_TAGLINE', 'Community Roleplay italiana su Roblox');
// URL pubblico del sito (es. https://emerals.pages.dev): serve solo per l'immagine di anteprima sui social.
defined('SITE_URL')           || define('SITE_URL', getenv('SITE_URL') ?: '');
defined('DISCORD_INVITE')     || define('DISCORD_INVITE', 'RyScfryWxt');
defined('DISCORD_URL')        || define('DISCORD_URL', 'https://discord.gg/' . DISCORD_INVITE);

// Imposta questi due valori per mostrare i giocatori online in tempo reale.
defined('ROBLOX_UNIVERSE_ID') || define('ROBLOX_UNIVERSE_ID', getenv('ROBLOX_UNIVERSE_ID') ?: '');
defined('ROBLOX_PLACE_ID')    || define('ROBLOX_PLACE_ID', getenv('ROBLOX_PLACE_ID') ?: '');

// Webhook Discord che riceve i messaggi del modulo contatti (opzionale).
defined('CONTACT_WEBHOOK')    || define('CONTACT_WEBHOOK', getenv('EMERALS_WEBHOOK') ?: '');

defined('CACHE_DIR')          || define('CACHE_DIR', dirname(__DIR__) . '/cache');
defined('STATS_TTL')          || define('STATS_TTL', 60);

/** Versione asset per il cache busting (data di modifica del file). */
function asset(string $path): string
{
    $full = dirname(__DIR__) . '/' . ltrim($path, '/');
    return $path . (is_file($full) ? '?v=' . filemtime($full) : '');
}

function e(string $s): string
{
    return htmlspecialchars($s, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function json_out(array $data, int $status = 200): never
{
    http_response_code($status);
    header('Content-Type: application/json; charset=utf-8');
    header('X-Content-Type-Options: nosniff');
    echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}

/** GET JSON con timeout breve. Restituisce null in caso di errore. */
function http_json(string $url, int $timeout = 4): ?array
{
    $body = null;
    if (function_exists('curl_init')) {
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT        => $timeout,
            CURLOPT_CONNECTTIMEOUT => 3,
            CURLOPT_USERAGENT      => 'EmeralsSite/2.0',
            CURLOPT_HTTPHEADER     => ['Accept: application/json'],
        ]);
        $res  = curl_exec($ch);
        $code = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        $body = ($res !== false && $code >= 200 && $code < 300) ? $res : null;
    } else {
        $ctx  = stream_context_create(['http' => ['timeout' => $timeout, 'header' => "Accept: application/json\r\nUser-Agent: EmeralsSite/2.0\r\n"]]);
        $res  = @file_get_contents($url, false, $ctx);
        $body = $res === false ? null : $res;
    }
    if ($body === null) {
        return null;
    }
    $json = json_decode($body, true);
    return is_array($json) ? $json : null;
}
