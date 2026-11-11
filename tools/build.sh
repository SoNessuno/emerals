#!/usr/bin/env bash
# Rigenera public/ dal sorgente PHP (serve PHP in locale). Non tocca public/_worker.js e public/_headers.
set -e
cd "$(dirname "$0")/.."
php -d session.save_path=/tmp -d display_errors=0 tools/build.php "$PWD/php-source" "$PWD/public"
rm -rf public/assets && cp -r php-source/assets public/assets
python3 - <<'PY'
import re
p='public/404.html'; s=open(p).read()
s=re.sub(r'(href|src)="assets/', r'\1="/assets/', s)
s=s.replace('href="#','href="/#').replace('href="./"','href="/"')
open(p,'w').write(s)
PY
echo "public/ aggiornata"
