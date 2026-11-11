<?php
declare(strict_types=1);
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/data.php';
if (session_status() !== PHP_SESSION_ACTIVE) { session_start(); }
if (empty($_SESSION['csrf'])) { $_SESSION['csrf'] = bin2hex(random_bytes(16)); }
$pageTitle = $pageTitle ?? SITE_NAME . ' — ' . SITE_TAGLINE;
$pageDesc  = $pageDesc  ?? 'EMERALS RP: la città roleplay italiana su Roblox. Reparti, documenti, economia in euro e staff sempre presente.';
?><!DOCTYPE html>
<html lang="it" data-theme="dark">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= e($pageTitle) ?></title>
<meta name="description" content="<?= e($pageDesc) ?>">
<meta name="theme-color" content="#0c0a10">
<meta name="csrf" content="<?= e($_SESSION['csrf']) ?>">
<meta property="og:type" content="website">
<meta property="og:title" content="<?= e($pageTitle) ?>">
<meta property="og:description" content="<?= e($pageDesc) ?>">
<meta property="og:locale" content="it_IT">
<?php if (SITE_URL !== ''): ?><meta property="og:image" content="<?= e(rtrim(SITE_URL, '/')) ?>/assets/img/hero.png">
<meta name="twitter:card" content="summary_large_image">
<?php endif; ?>
<link rel="icon" type="image/png" href="assets/img/favicon.png">
<link rel="apple-touch-icon" href="assets/img/apple-touch-icon.png">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,500;12..96,700;12..96,800&family=Inter:wght@400;500;600&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
<link rel="stylesheet" href="<?= e(asset('assets/css/style.css')) ?>">
</head>
<body>
<a class="skip" href="#main">Vai al contenuto</a>
<div class="progress" id="progress" aria-hidden="true"></div>

<header class="nav" id="nav">
  <div class="wrap nav__in">
    <a class="brand" href="#top" aria-label="<?= e(SITE_NAME) ?> — inizio pagina">
      <img src="assets/img/logo-mark.png" width="38" height="38" class="mark" alt="">
      <span><?= e(SITE_NAME) ?></span>
    </a>
    <nav class="nav__links" id="navLinks" aria-label="Principale">
      <a href="#citta">La città</a>
      <a href="#iniziare">Come iniziare</a>
      <a href="#carta">Carta d'identità</a>
      <a href="#staff">Staff</a>
      <a href="#faq">FAQ</a>
      <a href="#contatti">Contatti</a>
    </nav>
    <div class="nav__cta">
      <a class="btn btn--primary btn--sm" href="<?= e(DISCORD_URL) ?>" target="_blank" rel="noopener">Entra nel Discord</a>
      <button class="burger" id="burger" aria-label="Apri menu" aria-expanded="false" aria-controls="navLinks"><span></span><span></span></button>
    </div>
  </div>
</header>
