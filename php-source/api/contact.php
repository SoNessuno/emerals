<?php
declare(strict_types=1);
require __DIR__ . '/../includes/config.php';

session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    json_out(['ok' => false, 'error' => 'Metodo non consentito.'], 405);
}

$raw  = file_get_contents('php://input') ?: '';
$data = json_decode($raw, true);
if (!is_array($data)) {
    $data = $_POST;
}

// Anti-CSRF + honeypot
$token = (string) ($data['csrf'] ?? '');
if (empty($_SESSION['csrf']) || !hash_equals($_SESSION['csrf'], $token)) {
    json_out(['ok' => false, 'error' => 'Sessione scaduta. Ricarica la pagina e riprova.'], 403);
}
if (!empty($data['website'])) {
    json_out(['ok' => true]); // bot: fingiamo successo
}

// Rate limit: 1 invio ogni 60 secondi per IP
@mkdir(CACHE_DIR, 0750, true);
$ipHash = hash('sha256', ($_SERVER['REMOTE_ADDR'] ?? '0') . '|emerals');
$rlFile = CACHE_DIR . '/rl_' . substr($ipHash, 0, 24);
if (is_file($rlFile) && (time() - filemtime($rlFile)) < 60) {
    json_out(['ok' => false, 'error' => 'Attendi un minuto prima di inviare un altro messaggio.'], 429);
}

$name    = trim((string) ($data['name'] ?? ''));
$discord = trim((string) ($data['discord'] ?? ''));
$topic   = trim((string) ($data['topic'] ?? ''));
$message = trim((string) ($data['message'] ?? ''));

$topics = ['info' => 'Informazioni', 'partnership' => 'Partnership', 'staff' => 'Candidatura staff', 'altro' => 'Altro'];

$errors = [];
if (mb_strlen($name) < 2 || mb_strlen($name) > 40)       $errors[] = 'Inserisci un nome valido (2-40 caratteri).';
if (mb_strlen($discord) < 2 || mb_strlen($discord) > 40) $errors[] = 'Inserisci il tuo username Discord.';
if (!isset($topics[$topic]))                             $errors[] = 'Scegli un argomento.';
if (mb_strlen($message) < 10 || mb_strlen($message) > 1000) $errors[] = 'Il messaggio deve avere tra 10 e 1000 caratteri.';
if ($errors) {
    json_out(['ok' => false, 'error' => implode(' ', $errors)], 422);
}

// Neutralizza menzioni Discord (@everyone, @here, <@id>)
$clean = static fn(string $s): string => str_replace(['@', '`'], ['@\u{200B}', "'"], $s);

$delivered = false;
if (CONTACT_WEBHOOK !== '' && str_starts_with(CONTACT_WEBHOOK, 'https://discord.com/api/webhooks/')) {
    $payload = json_encode([
        'username' => 'Sito EMERALS RP',
        'allowed_mentions' => ['parse' => []],
        'embeds' => [[
            'title'       => 'Nuovo messaggio dal sito · ' . $topics[$topic],
            'description' => $clean($message),
            'color'       => 0x10D98A,
            'fields'      => [
                ['name' => 'Nome',    'value' => $clean($name),    'inline' => true],
                ['name' => 'Discord', 'value' => $clean($discord), 'inline' => true],
            ],
            'timestamp'   => gmdate('c'),
        ]],
    ], JSON_UNESCAPED_UNICODE);

    $ch = curl_init(CONTACT_WEBHOOK);
    curl_setopt_array($ch, [
        CURLOPT_POST           => true,
        CURLOPT_POSTFIELDS     => $payload,
        CURLOPT_HTTPHEADER     => ['Content-Type: application/json'],
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT        => 6,
    ]);
    curl_exec($ch);
    $delivered = ((int) curl_getinfo($ch, CURLINFO_HTTP_CODE)) < 300;
    curl_close($ch);
}

if (!$delivered) {
    // Fallback: salva su file (cartella non pubblica, vedi .htaccess)
    $line = json_encode(['t' => gmdate('c'), 'topic' => $topic, 'name' => $name, 'discord' => $discord, 'message' => $message], JSON_UNESCAPED_UNICODE) . "\n";
    @file_put_contents(CACHE_DIR . '/messages.log', $line, FILE_APPEND | LOCK_EX);
}

@touch($rlFile);
$_SESSION['csrf'] = bin2hex(random_bytes(16));
json_out(['ok' => true, 'csrf' => $_SESSION['csrf']]);
