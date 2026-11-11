<?php
declare(strict_types=1);
require __DIR__ . '/../includes/config.php';

/**
 * Statistiche live: membri Discord e giocatori Roblox.
 * Risposta in cache su file per STATS_TTL secondi, così il sito non martella le API esterne.
 */
header('Cache-Control: public, max-age=30');

if (!is_dir(CACHE_DIR)) {
    @mkdir(CACHE_DIR, 0750, true);
}
$cacheFile = CACHE_DIR . '/stats.json';

if (is_file($cacheFile) && (time() - filemtime($cacheFile)) < STATS_TTL) {
    $cached = json_decode((string) file_get_contents($cacheFile), true);
    if (is_array($cached)) {
        json_out($cached);
    }
}

$out = ['discord' => null, 'roblox' => null, 'updated' => time()];

$invite = http_json('https://discord.com/api/v10/invites/' . rawurlencode(DISCORD_INVITE) . '?with_counts=true');
if ($invite && isset($invite['approximate_member_count'])) {
    $out['discord'] = [
        'members' => (int) $invite['approximate_member_count'],
        'online'  => (int) ($invite['approximate_presence_count'] ?? 0),
    ];
}

if (ROBLOX_UNIVERSE_ID !== '' && ctype_digit((string) ROBLOX_UNIVERSE_ID)) {
    $game = http_json('https://games.roblox.com/v1/games?universeIds=' . ROBLOX_UNIVERSE_ID);
    if ($game && !empty($game['data'][0])) {
        $g = $game['data'][0];
        $out['roblox'] = [
            'playing' => (int) ($g['playing'] ?? 0),
            'visits'  => (int) ($g['visits'] ?? 0),
        ];
    }
}

// Se le API non rispondono, riusa l'ultimo valore valido invece di mostrare vuoto.
if ($out['discord'] === null && $out['roblox'] === null && is_file($cacheFile)) {
    $stale = json_decode((string) file_get_contents($cacheFile), true);
    if (is_array($stale)) {
        $stale['stale'] = true;
        json_out($stale);
    }
}

@file_put_contents($cacheFile, json_encode($out), LOCK_EX);
json_out($out);
