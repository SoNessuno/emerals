<footer class="foot">
  <div class="wrap">
    <div class="foot__grid">
      <div>
        <a class="brand" href="#top"><img src="assets/img/logo-mark.png" width="38" height="38" class="mark" alt=""><span><?= e(SITE_NAME) ?></span></a>
        <p class="foot__txt">La città roleplay italiana su Roblox. Reparti organizzati, economia in euro e uno staff che risponde.</p>
      </div>
      <div>
        <h4>Esplora</h4>
        <ul>
          <li><a href="#citta">La città</a></li>
          <li><a href="#iniziare">Come iniziare</a></li>
          <li><a href="#carta">Carta d'identità</a></li>
          <li><a href="#staff">Staff</a></li>
        </ul>
      </div>
      <div>
        <h4>Aiuto</h4>
        <ul>
          <li><a href="#faq">Domande frequenti</a></li>
          <li><a href="#contatti">Scrivici</a></li>
          <li><a href="<?= e(DISCORD_URL) ?>" target="_blank" rel="noopener">Apri un ticket</a></li>
        </ul>
      </div>
      <div>
        <h4>Community</h4>
        <ul>
          <li><a href="<?= e(DISCORD_URL) ?>" target="_blank" rel="noopener">Discord ufficiale</a></li>
          <li><button class="linklike" type="button" id="copyInvite" data-copy="<?= e(DISCORD_URL) ?>">Copia invito</button></li>
        </ul>
      </div>
    </div>
    <div class="foot__bot">
      <span>© <?= date('Y') ?> <?= e(SITE_NAME) ?>. Tutti i diritti riservati.</span>
      <span>Progetto di fantasia, non affiliato a Roblox Corporation.</span>
    </div>
  </div>
</footer>
<div class="toast" id="toast" role="status" aria-live="polite"></div>
<script>window.EMERALS = <?= json_encode([
    'districts' => $DISTRICTS,
    'discord'   => DISCORD_URL,
    'robloxPlace' => ROBLOX_PLACE_ID,
    'api' => ['stats' => 'api/stats.php', 'contact' => 'api/contact.php'],
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG) ?>;</script>
<script src="<?= e(asset('assets/js/main.js')) ?>" defer></script>
</body>
</html>
