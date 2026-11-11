<?php
declare(strict_types=1);
require __DIR__ . '/includes/header.php';

// Geometria della mappa: [x, y, larghezza, altezza] per ogni distretto
$MAP = [
    'polizia'     => [30, 30, 220, 150],
    'soccorso'    => [270, 30, 220, 150],
    'istituzioni' => [510, 30, 220, 150],
    'imprese'     => [30, 230, 160, 150],
    'banca'       => [210, 230, 160, 150],
    'supporto'    => [390, 230, 160, 150],
    'moderazione' => [570, 230, 160, 150],
];
?>
<main id="main">

<!-- ============ HERO ============ -->
<section class="hero" id="top">
  <canvas id="skyline" aria-hidden="true"></canvas>
  <div class="hero__fade" aria-hidden="true"></div>
  <div class="wrap hero__in">
    <div class="hero__copy">
      <p class="chip"><span class="dot dot--live"></span> Città aperta · Roleplay italiano su Roblox</p>
      <h1>La città che <span class="grad">non dorme</span>.<br>E ti aspetta.</h1>
      <p class="lead">EMERALS RP è un roleplay organizzato come una vera città: polizia, soccorso, istituzioni, banca e imprese. Regole chiare, documenti veri, staff che risponde.</p>
      <div class="hero__cta">
        <a class="btn btn--primary" href="<?= e(DISCORD_URL) ?>" target="_blank" rel="noopener">Entra nel Discord
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg></a>
        <a class="btn btn--ghost" href="#citta">Esplora la città</a>
      </div>
      <dl class="live" id="live">
        <div><dt>Membri Discord</dt><dd data-stat="members">—</dd></div>
        <div><dt>Online ora</dt><dd data-stat="online">—</dd></div>
        <div hidden data-roblox><dt>In gioco</dt><dd data-stat="playing">—</dd></div>
        <div><dt>Distretti</dt><dd data-count="<?= count($DISTRICTS) ?>"><?= count($DISTRICTS) ?></dd></div>
      </dl>
    </div>

    <aside class="radio" aria-label="Centrale operativa (simulazione)">
      <div class="radio__bar">
        <span class="dot dot--live"></span> CENTRALE OPERATIVA
        <span class="radio__tag">simulazione</span>
      </div>
      <ol class="radio__feed" id="feed" aria-live="off"></ol>
      <div class="radio__foot mono"><span id="clock">--:--:--</span><span>CANALE 1 · ITALIA</span></div>
    </aside>
  </div>
</section>

<!-- ============ VALORI ============ -->
<section class="band">
  <div class="wrap band__in">
    <div class="band__i"><b>Regole uguali per tutti</b><span>Sanzioni con storico e log, anche per lo staff.</span></div>
    <div class="band__i"><b>Processi digitali</b><span>Ticket, richieste e turni gestiti da un bot dedicato.</span></div>
    <div class="band__i"><b>Economia in euro</b><span>Portafoglio, conto, bonifici e premio giornaliero.</span></div>
    <div class="band__i"><b>Staff presente</b><span>Un team con gerarchia chiara e supporto via ticket.</span></div>
  </div>
</section>

<!-- ============ CITTÀ / MAPPA ============ -->
<section class="sec" id="citta">
  <div class="wrap">
    <header class="sec__head reveal">
      <p class="eyebrow">La città</p>
      <h2>Sette distretti. Una sola città.</h2>
      <p>Seleziona un distretto sulla mappa per scoprire cosa si fa, come funziona e quali comandi usa lo staff.</p>
    </header>

    <div class="city reveal">
      <div class="city__map">
        <svg viewBox="0 0 760 420" role="group" aria-label="Mappa interattiva della città" id="cityMap">
          <defs>
            <pattern id="grid" width="20" height="20" patternUnits="userSpaceOnUse"><path d="M20 0H0V20" fill="none" stroke="currentColor" stroke-opacity=".06"/></pattern>
          </defs>
          <rect width="760" height="420" fill="url(#grid)"/>
          <!-- strade -->
          <g class="road">
            <rect x="0" y="180" width="760" height="50"/>
            <rect x="250" y="0" width="20" height="180"/><rect x="490" y="0" width="20" height="180"/>
            <rect x="190" y="230" width="20" height="150"/><rect x="370" y="230" width="20" height="150"/><rect x="550" y="230" width="20" height="150"/>
          </g>
          <path class="lane" d="M0 205H760"/>
          <!-- fiume -->
          <path class="river" d="M0 398C90 382 160 412 260 398S430 382 520 398 680 412 760 394V420H0Z"/>
          <!-- auto -->
          <circle r="4" class="car car--a"><animateMotion dur="14s" repeatCount="indefinite" path="M-10 195H770"/></circle>
          <circle r="4" class="car car--b"><animateMotion dur="19s" repeatCount="indefinite" path="M770 215H-10"/></circle>
          <circle r="4" class="car car--c"><animateMotion dur="11s" repeatCount="indefinite" path="M260 -10V190"/></circle>
          <circle r="4" class="car car--a"><animateMotion dur="16s" repeatCount="indefinite" path="M500 190V-10"/></circle>

          <?php foreach ($MAP as $key => [$x, $y, $w, $h]):
              $d = $DISTRICTS[$key]; ?>
          <g class="dist" data-d="<?= e($key) ?>" tabindex="0" role="button" aria-label="<?= e($d['name']) ?>" transform="translate(<?= $x ?> <?= $y ?>)">
            <rect class="dist__bg" width="<?= $w ?>" height="<?= $h ?>" rx="14"/>
            <g class="dist__bld" aria-hidden="true">
              <?php
              // piccoli edifici deterministici per ogni distretto
              mt_srand(crc32($key));
              $n = (int) floor(($w - 24) / 26);
              for ($i = 0; $i < $n; $i++) {
                  $bh = mt_rand(24, 62);
                  echo '<rect x="' . (14 + $i * 26) . '" y="' . ($h - 14 - $bh) . '" width="18" height="' . $bh . '" rx="3"/>';
              }
              ?>
            </g>
            <text class="dist__code" x="16" y="30"><?= e($d['code']) ?></text>
            <text class="dist__name" x="16" y="52"><?= e($d['name']) ?></text>
          </g>
          <?php endforeach; ?>
        </svg>
        <p class="city__hint mono"><span class="dot"></span> Clicca o usa Tab + Invio sui distretti</p>
      </div>

      <article class="panel" id="panel" aria-live="polite">
        <div class="panel__top">
          <span class="panel__ico" id="pIcon"></span>
          <div><p class="mono panel__tag" id="pTag"></p><h3 id="pName"></h3></div>
        </div>
        <p class="panel__txt" id="pText"></p>
        <ul class="panel__list" id="pPoints"></ul>
        <p class="panel__cmdt mono">Comandi del bot</p>
        <div class="panel__cmds" id="pCmds"></div>
      </article>
    </div>
  </div>
</section>

<!-- ============ COME INIZIARE ============ -->
<section class="sec sec--alt" id="iniziare">
  <div class="wrap">
    <header class="sec__head reveal">
      <p class="eyebrow">Il percorso</p>
      <h2>Da nuovo arrivato a cittadino in quattro passi.</h2>
    </header>
    <ol class="path">
      <?php foreach ($STEPS as $i => [$t, $d]): ?>
      <li class="path__i reveal">
        <span class="path__n"><?= str_pad((string) ($i + 1), 2, '0', STR_PAD_LEFT) ?></span>
        <h3><?= e($t) ?></h3>
        <p><?= e($d) ?></p>
      </li>
      <?php endforeach; ?>
    </ol>
    <div class="center reveal"><a class="btn btn--primary" href="<?= e(DISCORD_URL) ?>" target="_blank" rel="noopener">Inizia dal Discord</a></div>
  </div>
</section>

<!-- ============ CARTA D'IDENTITÀ ============ -->
<section class="sec" id="carta">
  <div class="wrap idc">
    <div class="idc__copy reveal">
      <p class="eyebrow">Anteprima</p>
      <h2>Crea la tua carta d'identità RP.</h2>
      <p>Prova il tuo personaggio prima ancora di entrare. Inserisci i dati, guarda la carta prendere forma e scaricala. Poi, nel Discord, usi gli stessi dati per la richiesta di cittadinanza.</p>
      <form class="form idc__form" id="idForm" autocomplete="off">
        <label>Nome RP<input name="nome" maxlength="18" value="Marco" required></label>
        <label>Cognome RP<input name="cognome" maxlength="18" value="Rossi" required></label>
        <label>Data di nascita<input name="nascita" type="date" value="1998-05-14" required></label>
        <label>Nazione<input name="nazione" maxlength="20" value="Italia" required></label>
        <label class="full">Username Roblox<input name="roblox" maxlength="20" value="MarcoRossi_RP" required></label>
      </form>
      <div class="idc__btns">
        <button class="btn btn--primary" type="button" id="idDownload">Scarica la carta (PNG)</button>
        <button class="btn btn--ghost" type="button" id="idCopy">Copia i dati per la richiesta</button>
      </div>
      <p class="fine">Documento di fantasia, valido solo all'interno del roleplay. Nessun dato viene inviato o salvato: tutto resta sul tuo dispositivo.</p>
    </div>
    <div class="idc__view reveal">
      <canvas id="idCanvas" width="856" height="540" aria-label="Anteprima della carta d'identità RP"></canvas>
    </div>
  </div>
</section>

<!-- ============ STAFF ============ -->
<section class="sec sec--alt" id="staff">
  <div class="wrap">
    <header class="sec__head reveal">
      <p class="eyebrow">Chi gestisce la città</p>
      <h2>Una gerarchia chiara, dall'alto al basso.</h2>
      <p>Ogni grado ha compiti e responsabilità definiti. Le promozioni e le retrocessioni vengono annunciate e registrate.</p>
    </header>
    <div class="ranks">
      <?php foreach ($RANKS as $i => [$group, $roles]): ?>
      <div class="rank reveal" style="--w: <?= 100 - $i * 9 ?>%">
        <p class="rank__g mono"><?= e($group) ?></p>
        <div class="rank__r">
          <?php foreach ($roles as $r): ?><span><?= e($r) ?></span><?php endforeach; ?>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ============ FAQ ============ -->
<section class="sec" id="faq">
  <div class="wrap wrap--narrow">
    <header class="sec__head reveal">
      <p class="eyebrow">FAQ</p>
      <h2>Le domande che ci fanno più spesso.</h2>
    </header>
    <div class="faq reveal">
      <?php foreach ($FAQ as [$q, $a]): ?>
      <details>
        <summary><?= e($q) ?></summary>
        <p><?= e($a) ?></p>
      </details>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ============ CONTATTI ============ -->
<section class="sec sec--alt" id="contatti">
  <div class="wrap contact">
    <div class="contact__copy reveal">
      <p class="eyebrow">Contatti</p>
      <h2>Hai una domanda o una proposta?</h2>
      <p>Per assistenza immediata il canale migliore è un ticket nel Discord. Per partnership, candidature e informazioni puoi scriverci anche da qui.</p>
      <a class="btn btn--ghost" href="<?= e(DISCORD_URL) ?>" target="_blank" rel="noopener">Apri un ticket nel Discord</a>
    </div>
    <form class="form contact__form reveal" id="contactForm" novalidate>
      <input type="hidden" name="csrf" value="<?= e($_SESSION['csrf']) ?>">
      <input class="hp" type="text" name="website" tabindex="-1" autocomplete="off" aria-hidden="true">
      <label>Nome<input name="name" maxlength="40" required></label>
      <label>Username Discord<input name="discord" maxlength="40" required placeholder="es. utente"></label>
      <label class="full">Argomento
        <select name="topic" required>
          <option value="info">Informazioni</option>
          <option value="partnership">Partnership</option>
          <option value="staff">Candidatura staff</option>
          <option value="altro">Altro</option>
        </select>
      </label>
      <label class="full">Messaggio<textarea name="message" rows="5" maxlength="1000" required></textarea></label>
      <div class="full form__foot">
        <button class="btn btn--primary" type="submit" id="cSend">Invia messaggio</button>
        <p class="form__msg" id="cMsg" role="status"></p>
      </div>
    </form>
  </div>
</section>

<!-- ============ CTA ============ -->
<section class="cta">
  <div class="wrap cta__in reveal">
    <div>
      <h2>Pronto a entrare in città?</h2>
      <p>Unisciti alla community e scegli chi vuoi essere.</p>
    </div>
    <a class="btn btn--light" href="<?= e(DISCORD_URL) ?>" target="_blank" rel="noopener">Entra nel Discord</a>
  </div>
</section>

</main>
<?php require __DIR__ . '/includes/footer.php';
