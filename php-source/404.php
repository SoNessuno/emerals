<?php
http_response_code(404);
$pageTitle = '404 — EMERALS RP';
require __DIR__ . '/includes/header.php';
?>
<main id="main" class="nf">
  <div class="wrap">
    <p class="mono">ERRORE 404 · FUORI MAPPA</p>
    <h1>Questa strada non esiste in città.</h1>
    <p>La pagina che cerchi è stata spostata o non c'è mai stata.</p>
    <a class="btn btn--primary" href="./">Torna al centro</a>
  </div>
</main>
<?php require __DIR__ . '/includes/footer.php';
