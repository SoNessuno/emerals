<?php
declare(strict_types=1);

/** Distretti della città = reparti e servizi, con i comandi reali del bot. */
$DISTRICTS = [
    'polizia' => [
        'name' => 'Polizia', 'code' => 'PD', 'icon' => 'shield',
        'tag' => 'Ordine pubblico',
        'text' => 'Pattuglie, controlli e interventi sul territorio. Ogni arresto e ogni multa vengono registrati e finiscono nello storico del cittadino.',
        'points' => ['Arresti e multe registrati con un comando', 'Statistiche per singolo agente', 'Registro consultabile dallo staff'],
        'cmds' => ['/arresto', '/multa', '/registro_arresti'],
    ],
    'soccorso' => [
        'name' => 'Soccorso', 'code' => 'SS', 'icon' => 'cross',
        'tag' => 'SSU · SSD',
        'text' => 'Turni di servizio online e offline con pannelli dedicati: la città sa sempre chi è operativo e chi no.',
        'points' => ['Pannelli SSU/SSD con stato in tempo reale', 'Elenco operatori attualmente in servizio', 'Annunci di apertura e chiusura sessione'],
        'cmds' => ['/ssu_online', '/ssd_online'],
    ],
    'istituzioni' => [
        'name' => 'Istituzioni', 'code' => 'IS', 'icon' => 'landmark',
        'tag' => 'Documenti',
        'text' => 'Cittadinanza, patente e porto d\'armi: richiedi tutto da un modulo, lo staff revisiona e i ruoli vengono assegnati automaticamente.',
        'points' => ['Tre tipi di richiesta con modulo guidato', 'Revisione dello staff con esito tracciato', 'Ruolo assegnato in automatico all\'approvazione'],
        'cmds' => ['/registra', '/profilo', '/richieste_aperte'],
    ],
    'imprese' => [
        'name' => 'Attività', 'code' => 'AT', 'icon' => 'briefcase',
        'tag' => 'Imprese',
        'text' => 'Apri la tua attività e fai crescere un\'impresa nella città, con richieste dedicate e supporto dello staff.',
        'points' => ['Richiesta di fondazione dedicata', 'Gestione e approvazione dallo staff', 'Partnership con altre community'],
        'cmds' => ['/partnership', '/partnership_lista'],
    ],
    'banca' => [
        'name' => 'Banca', 'code' => 'BK', 'icon' => 'coins',
        'tag' => 'Economia in euro',
        'text' => 'Portafoglio, conto corrente, bonifici e premio giornaliero. Un\'economia vera, con livelli ed esperienza.',
        'points' => ['Contanti e conto separati', 'Bonifici tra cittadini', 'Premio giornaliero, livelli e classifica'],
        'cmds' => ['/conto', '/bonifico', '/daily'],
    ],
    'supporto' => [
        'name' => 'Supporto', 'code' => 'CS', 'icon' => 'headset',
        'tag' => 'Ticket',
        'text' => 'Il centro assistenza della città: apri un ticket, vieni preso in carico da un membro dello staff e segui la risoluzione.',
        'points' => ['Pannello ticket con categorie', 'Presa in carico tracciata', 'Statistiche per singolo staff'],
        'cmds' => ['/ticket_pannello', '/ticket_stats'],
    ],
    'moderazione' => [
        'name' => 'Moderazione', 'code' => 'MD', 'icon' => 'scale',
        'tag' => 'Regole & sanzioni',
        'text' => 'Avvisi con escalation, silenziamenti, ban e storico completo. Le regole valgono per tutti, nello stesso modo.',
        'points' => ['Avvertimenti con escalation automatica', 'Log di ogni azione rilevante', 'Sanzioni anche per lo staff'],
        'cmds' => ['/warn', '/mute', '/warnings'],
    ],
];

/** Gerarchia staff (solo nomi dei gradi, dal più alto al più basso). */
$RANKS = [
    ['Direzione', ['Fondatore del Progetto', 'Fondatore Generale', 'Founder', 'Co-Founder', 'CEO', 'CO-CEO']],
    ['Gestione',  ['Community Manager', 'Coordinatore Staff']],
    ['Moderazione', ['Capo Moderazione', 'Major Moderatore', 'Senior Moderatore', 'Moderatore Junior']],
    ['Supervisione', ['Head Supervisore', 'Senior Supervisore', 'Junior Supervisore']],
    ['Supporto', ['Head Helper', 'Major Helper', 'Senior Helper', 'Helper']],
];

$STEPS = [
    ['Entra nel Discord', 'Unisciti al server ufficiale e leggi il regolamento prima di tutto.'],
    ['Collega Roblox', 'Con /registra colleghi il tuo account Roblox al profilo Discord.'],
    ['Ottieni i documenti', 'Cittadinanza, patente e porto d\'armi: li chiedi con un modulo e li ritiri dallo staff.'],
    ['Scegli il tuo ruolo', 'Lavora in un reparto, apri un\'attività o vivi la città da cittadino.'],
];

$FAQ = [
    ['È gratis?', 'Sì. Entrare nella community e giocare non costa nulla.'],
    ['Serve un account Roblox?', 'Sì, ti serve un account Roblox. Dopo l\'ingresso nel Discord lo colleghi al tuo profilo con il comando /registra.'],
    ['Come ottengo cittadinanza, patente e porto d\'armi?', 'Dal Discord compili il modulo di richiesta. Lo staff lo revisiona e, se approvato, il ruolo ti viene assegnato automaticamente.'],
    ['Come funziona l\'economia?', 'Hai un portafoglio e un conto in euro: puoi depositare, prelevare, fare bonifici, riscuotere il premio giornaliero e salire di livello.'],
    ['Come entro nello staff o in un reparto?', 'Controlla gli annunci nel Discord: le candidature si aprono quando servono nuove persone. Intanto, partecipa e fatti conoscere.'],
    ['Ho un problema o voglio segnalare qualcuno.', 'Apri un ticket dal pannello nel Discord. Lo staff lo prende in carico e ti aggiorna nel ticket stesso.'],
];

$ICONS = [
    'shield'    => '<path d="M12 3l8 3v6c0 4.5-3.2 8-8 9-4.8-1-8-4.5-8-9V6l8-3z"/><path d="M9 12l2 2 4-4"/>',
    'cross'     => '<rect x="4" y="4" width="16" height="16" rx="4"/><path d="M12 8v8M8 12h8"/>',
    'landmark'  => '<path d="M3 10l9-6 9 6"/><path d="M5 10v8M9.5 10v8M14.5 10v8M19 10v8"/><path d="M3 21h18"/>',
    'briefcase' => '<rect x="3" y="7" width="18" height="13" rx="2"/><path d="M9 7V5a2 2 0 012-2h2a2 2 0 012 2v2M3 13h18"/>',
    'coins'     => '<ellipse cx="12" cy="6" rx="7" ry="3"/><path d="M5 6v6c0 1.7 3.1 3 7 3s7-1.3 7-3V6M5 12v6c0 1.7 3.1 3 7 3s7-1.3 7-3v-6"/>',
    'headset'   => '<path d="M4 14v-2a8 8 0 0116 0v2"/><rect x="3" y="14" width="4" height="6" rx="1.5"/><rect x="17" y="14" width="4" height="6" rx="1.5"/><path d="M19 20c0 1.5-2 2-5 2"/>',
    'scale'     => '<path d="M12 3v18M6 21h12M5 7h14"/><path d="M5 7l-3 7a3 3 0 006 0L5 7zM19 7l-3 7a3 3 0 006 0l-3-7z"/>',
];

function icon(string $name, int $size = 24): string
{
    global $ICONS;
    $p = $ICONS[$name] ?? '';
    return '<svg class="ico" width="' . $size . '" height="' . $size . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">' . $p . '</svg>';
}
