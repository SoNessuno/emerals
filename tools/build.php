<?php
// Genera le pagine statiche per Cloudflare Pages a partire dal sito PHP.
$src = $argv[1]; $out = $argv[2];
chdir($src);
$_SERVER['REQUEST_METHOD'] = 'GET';
foreach (['index' => 'index.php', '404' => '404.php'] as $name => $file) {
    ob_start();
    @include $src . '/' . $file;
    $html = ob_get_clean();
    // niente CSRF/sessioni su hosting statico
    $html = preg_replace('/<meta name="csrf"[^>]*>\n?/', '', $html);
    $html = preg_replace('/<input type="hidden" name="csrf"[^>]*>\n?/', '', $html);
    $html = str_replace(['"api\/stats.php"', '"api/stats.php"'], '"api/stats"', $html);
    $html = str_replace('"api/contact.php"', '"api/contact"', $html);
    file_put_contents("$out/$name.html", $html);
}
